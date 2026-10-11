<?php

use Illuminate\Database\Seeder;
use App\Student;
use App\StudentDetails;
use App\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fixed Admin Credentials
        $adminEmail = 'ncs@jssaten.ac.in';
        $adminPassword = 'Jssaten.ac.in@011235';

        $admin = Admin::where('email', $adminEmail)->first();
        if (!$admin) {
            $admin = new Admin;
            $admin->name = "Admin";
            $admin->email = $adminEmail;
            $admin->password = Hash::make($adminPassword);
            $admin->type = '1';
            $admin->save();
        } else {
            $admin->password = Hash::make($adminPassword);
            $admin->save();
        }

        // Default test student if none exists
        $studentPassword = env('SEED_STUDENT_PASSWORD', 'StudentSecure@CodePad2026!');
        if (!Student::where('admision_no', '15cse075')->first()) {
            $student = new Student;
            $student->name = "Student";
            $student->roll_number = "15001001075";
            $student->admision_no = "15cse075";
            $student->password = Hash::make($studentPassword);
            if ($student->save()) {
                $student_details = new StudentDetails;
                $student_details->student_id = $student->id;
                $student_details->year = "4th Year";
                $student_details->save();
            }
        }
    }
}
