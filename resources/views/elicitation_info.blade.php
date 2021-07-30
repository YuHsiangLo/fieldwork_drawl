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
        <div class="text-center">
            <button type="submit" class="btn btn-primary">@lang('messages.Next')</button>
        </div>
    </form>
@endsection
