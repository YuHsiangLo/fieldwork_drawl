<?php

namespace App\Http\Controllers;

use App\FieldWorkRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\ConsentForm;
use App\Recording;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = \App\User::where('authorized', '0')->get();

        // $consent_forms = \App\ConsentForm::where('created_at', '>', Carbon::now()->subDays(7))->get();

        // $consentForms = app(\App\ConsentForm::class)->where('created_at', '>', Carbon::now()->subDays(1))->get();

        //$consent_forms = app(\App\ConsentForm::class)::all();

        $weekbefore = Carbon::now()->subDays(7);

        $consentForms = [];
        foreach (ConsentForm::all()->sortByDesc("created_at") as $consentForm) {
            if ($consentForm->created_at > $weekbefore) {
                $consentForm->has_demographic_questionnaire = true;
                if (app(\App\DemographicQuestionnaire::class)->where('consent_form_id',$consentForm->id)->get()->count() == 0) {
                    $consentForm->has_demographic_questionnaire = false;
                }
                $consentForm->has_recording = true;
                if (app(\App\Recording::class)->where('consent_form_id',$consentForm->id)->get()->count() == 0) {
                    $consentForm->has_recording = false;
                }
                $consentForms[] = $consentForm;
            }
        }

        $recordings = [];
        foreach (FieldWorkRecording::all()->sortByDesc("created_at") as $recording) {
            if ($recording->created_at > $weekbefore) {
                $recording->has_demographic_questionnaire = true;
                $recording->has_recording = true;
                //if (app(\App\Recording::class)->where('consent_form_id',$consentForm->id)->get()->count() == 0) {
                //    $consentForm->has_recording = false;
                //}
                $recordings[] = $recording;
            }
        }

        //Log::info("Consent forms content is " . json_encode($consentForms));

        return view('admin',
            [ 'users' => $users, 'consent_forms' => $recordings ]);
    }
}
