@extends('layouts.wizard')

@section('title')
    @lang('messages.DemoTitle')
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div>Please pass the following URL to the consultant:</div>
                <strong>https://fieldworkdrawl.linguistics.ubc.ca/record/create?date={{$elicitation_date}}&elicitor={{$elicitor_name}}&consultant={{$consultant_name}}&zoom={{$zoom_link}}</strong>
            </div>
        </div>
    </div>
@endsection
