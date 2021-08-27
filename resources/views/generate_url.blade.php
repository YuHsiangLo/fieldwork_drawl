@extends('layouts.wizard')

@section('title')
    @lang('messages.DemoTitle')
@endsection

@section('content')
    <div>Please pass the following URL to the consultant:</div>
    <strong>https://fieldworkdrawl.linguistics.ubc.ca/record/create?date={{$elicitation_date}}&elicitor={{$elicitor_name}}&consultant={{$consultant_name}}&zoom={{$zoom_link}}</strong>
@endsection
