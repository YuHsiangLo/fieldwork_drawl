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
        $recordings = [];
        foreach (FieldWorkRecording::all()->sortByDesc("created_at") as $recording) {
            $recordings[] = $recording;
        }

        return view('admin',
            [ 'users' => $users, 'consent_forms' => $recordings ]);
    }
}
