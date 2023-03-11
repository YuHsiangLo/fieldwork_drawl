@extends('layouts.wizard')

@section('title')
    @lang('messages.DemoTitle')
@endsection

@section('content')
    <form class="w-50 mx-auto" action="{{ route('generate.url') }}" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="elicitation-date">Elicitation date*</label>
                <input type="date" id="elicitation-date" name="elicitation-date" class="form-control" required>
            </div>
            <div class="form-group col-md-4">
                <label for="elicitor-name">Elicitor name*</label>
                <input type="text" id="elicitor-name" name="elicitor-name" class="form-control" required>
            </div>
            <div class="form-group col-md-4">
                <label for="consultant-name">Consultant name*</label>
                <input type="text" id="consultant-name" name="consultant-name" class="form-control" required>
            </div>
        </div>
        <div class="form-row">
            <label for="zoom-link">Zoom link*</label>
            <input type="text" id="zoom-link" name="zoom-link" class="form-control" required>
        </div>
        <br>
        <div class="text-info text-center">
            Note that all fields are required, and please make sure that the <strong>elicitor name</strong> matches the one you used for
            <a href="https://fieldworkdrawl.linguistics.ubc.ca/register">registration</a>. If you forget this information or have any questions,
            please reach out to Roger Lo at roger.lo[at]ubc.ca.
        </div>
        <br>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">@lang('messages.DemoTitle')</button>
        </div>
    </form>
@endsection
