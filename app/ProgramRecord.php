<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramRecord extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'compiler_record';

    protected $fillable = [
        'name',
        'code',
        'description',
        'instructions',
        'starttime',
        'endtime',
        'start',
        'end',
        'uploaded_by',
        'upload_id'
    ];
}
