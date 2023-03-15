<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElicitationInfoController extends Controller
{
    public function create(Request $request)
    {
        return view('elicitation_info');
    }

    public function generate_url(Request $request)
    {
        $this->validate($request, [
            'name' => 'exists:users'
        ],
        [
            'name.exists' => 'Username not found in the database. Please double-check or register first.'
        ]);

        return view('generate_url',
            [
                'elicitation_date' => $request['elicitation-date'],
                'elicitor_name' => $request['name'],
                'consultant_name' => $request['consultant-name'],
                'zoom_link' => $request['zoom-link']
            ]);
    }
}
