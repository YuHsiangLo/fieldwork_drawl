<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    // Store recordings as JSON
    use \Okipa\LaravelModelJsonStorage\ModelJsonStorage;

    public $fillable = [
        'date',
        'local_time',
        'elicitor',
        'consultant',
        'recording_filename'
    ];

    public function consentForm()
    {
        return $this->belongsTo('App\ConsentForm');
    }
}
