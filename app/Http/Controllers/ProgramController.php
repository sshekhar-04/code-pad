<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;
use Session;
use Redirect;
use App\ProgramRecord;
use App\ProgramDetails;
use App\Teacher;
use App\Student;

use App\Result;
use Validator;
use View;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\MessageBag;
use Illuminate\Database\Eloquent\SoftDeletes;
use GuzzleHttp\Client;
use App\Services\CodeExecutionService;
use App\Helpers\SecurityHelper;

class ProgramController extends Controller
{

    public function compile(Request $request, $code, $id)
    {
        $this->validate(
            $request,
            [
            'program' => 'required',
            ]
        );

        $source = strval($request->input('program'));
        $lang = $request->input('lang', 'c');

        $executor = new CodeExecutionService();
        $result = $executor->compile($source, $lang);

        return Redirect::back()->withInput()->with('res', $result);
    }




    public function runstatus(Request $request, $code, $id)
    {
        $this->validate(
            $request,
            [
            'program' => 'required',
            ]
        );

        $record = ProgramRecord::where('code', $code)->first();
        if (!$record) {
            return Redirect::back()->withErrors(['Record not found']);
        }
        $record_id = $record->id;

        $program_details = ProgramDetails::where(
            [
            [
            'record_id', $record_id
            ],
            [
            'id',$id
            ]
            ]
        )->first();

        if (!$program_details) {
            return Redirect::back()->withErrors(['Problem not found']);
        }

        $inp = strip_tags($program_details['testcases_input']); //Getting Input
        $oup = strip_tags($program_details['testcases_output']); //Getting Output

        $coded = Input::all(); // Getting user input
        $output = strval($coded['program']); // Getting program
        $lang = isset($coded['lang']) ? $coded['lang'] : 'c';

        $input = $inp;
        if (isset($coded['input']) && $coded['input'] != "") {
            if (isset($coded['name']) && $coded['name'] == "true") {
                $input = $coded['input'];
            }
        }

        $executor = new CodeExecutionService();
        $result = $executor->execute($output, $input, $lang);

        $result['input'] = $input;
        $result['expected_output'] = $oup;

        $attempt = [];
        $event = null;
        if (Auth::guard('student')->check()) {
            $event = Result::where(
                [
                [
                'record_id', $record_id
                ],
                [
                'student_id', Auth::guard('student')->user()->id
                ]
                ]
            )->first();

            if ($event) {
                $attempt = SecurityHelper::safeDecode($event['attempt']);
            }
        }

        if ($result['compile_status'] == 'OK') {
            $cleanExpected = trim(str_replace("\r\n", "\n", (string)$result['expected_output']));
            $cleanActual = trim(str_replace("\r\n", "\n", (string)$result['output_html']));

            if ($cleanExpected === $cleanActual && Auth::guard('student')->check() && $event) {
                $marksKey = 'marks-' . $id;
                $currentMarks = isset($attempt[$marksKey]) ? (int)$attempt[$marksKey] : 0;
                $targetMarks = (int)$program_details['marks'];

                if ($currentMarks != $targetMarks) {
                    $event->score = $event->score - $currentMarks + $targetMarks;
                    $attempt[$marksKey] = $targetMarks;
                    $attempt['done-' . $id] = 1;
                }

                $attemptCountKey = 'program-id' . $id;
                $attempt[$attemptCountKey] = (isset($attempt[$attemptCountKey]) ? (int)$attempt[$attemptCountKey] : 0) + 1;

                $event->attempt = SecurityHelper::safeEncode($attempt);
                $event->save();
            }
        }

        return Redirect::back()->withInput()->with('out', $result);
    }

    public function writeFile()
    {
        Storage::append('record/PHPH.txt', "Ankit1");
    }

    public function leaderboard(Request $request, $code)
    {
        $id = ProgramRecord::where('code', $code)->first()->id;
        // return $id;
        $result = Result::where('record_id', $id)->orderBy('score', 'desc')->orderBy('updated_at', 'asc')->get();
        // return $result;

        foreach ($result as $key) {
            // code....
            $stdname = Student::find($key->student_id);
            $key['name']= $stdname->name;
        }
        return view('master.leaderboard')->with('data', $result);
    }

}
