<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldWorkRecording extends Model
{
    // Store recordings as JSON
    use \Okipa\LaravelModelJsonStorage\ModelJsonStorage;

    public $fillable = [
        'date',
        'elicitor',
        'consultant',
        'recording_filename'
    ];
}
