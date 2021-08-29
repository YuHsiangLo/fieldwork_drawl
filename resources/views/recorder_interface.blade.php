@extends('layouts.wizard')

@section('title')
    @lang('messages.RecorderTitle')
@endsection

@section('extra-headers')
    <script src="{{ asset('js/audiodisplay.js') }}"></script>
    <script src="{{ asset('js/recorderjs/recorder.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/web-audio-peak-meter.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/jquery.scrolling-tabs.min.css') }}">
    <script src="{{ asset('js/jquery.scrolling-tabs.min.js') }}"></script>
    <script src="{{ asset('messages.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // $("#activateAudio").modalWizard();
            $('#activateAudio').modal('show');
            $('.nav-tabs').scrollingTabs({
                bootstrapVersion: 4,
                cssClassLeftArrow: 'fa fa-chevron-left',
                cssClassRightArrow: 'fa fa-chevron-right'
            });
        });

        function ShowProgressAnimation(message){
            var progresstext = document.getElementById('progresstext');
            progresstext.innerHTML = message;
            $("#statusWindow").modal('show');
            //$("#my-peak-meter").hide();
        }

        function EndProgressAnimation(){
            $("#statusWindow").modal('hide');
            //$("#my-peak-meter").show();
        }
    </script>
    <style>
        canvas {
            display: inline-block;
            background: #202020;
            width: 90%;
            height: 55px;
        {{-- box-shadow: 0px 0px 10px blue; --}}
        }

        #progresstext {
            padding: 10px 0px;
            text-align: center;
            margins: auto;
        }

        #progressimg {
            height:32px;
            width:32px;
            margin:30px;
        }
        @media (orientation: portrait) {
            .limitsize { max-height: 40vh;
                overflow-y:auto; }
        }
    </style>
    <meta name="csrf-token" content="{{csrf_token()}}" />
@endsection

@section('content')
    <div id="activateAudio" class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><div id="wizard-title">@lang('messages.RecorderActivateAudioTitle')</div></h5>
                </div>
                <div class="modal-body">
                    <div id="first-step">
                        <p>@lang('messages.RecorderActivateAudioPrompt')</p>
                    </div>
                </div>
                <div id="wizard-footer" class="modal-footer">
                    <p id="activate-audio" class="text-center"><button type="button" class="btn btn-primary" onclick="initAudio('{{$locale}}');">@lang('messages.RecorderActivateAudioButton')</button></p>
                </div>
            </div>
        </div>
    </div>
    <div id="statusWindow" class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><div id="wizard-title">@lang('messages.RecorderStatusDialogTitle')</div></h5>
                </div>
                <div class="modal-body text-center">
                    <div id="progressimage"><img id="progressimg" src="/images/please_wait.gif" alt="Loading.." /><br /></div>
                    <div id="progresstext">@lang('messages.RecorderEncoding')</div>
                </div>
                <div id="status-footer" class="modal-footer invisible">
                    <button type="button" class="btn btn-info" data-dismiss="modal" onclick="$('#status-footer').addClass('invisible');">@lang('messages.OK')</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#instructions">@lang('messages.RecorderInstructionsTitle')</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body limitsize">
                        <div class="tab-content mt-3">
                            <div class="tab-pane active" id="instructions">
                                <div class="card-text">
                                    <div>Please use the following link to go to Zoom:</div>
                                    <div><a href="{{$zoom}}" target="_blank">{{$zoom}}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 text-center">
                <div class="card">
                    <div class="card-header">
                        @lang('messages.RecorderToolTitle')
                    </div>
                    <div class="card-body">
                        <div class="text-center">@lang('messages.RecorderInputLevelLabel')</div>
                        <div id="my-peak-meter" style="width: 90%; height: 50px; display: inline-block; background: #202020;"></div>
                        {{-- <canvas id="analyser" width="1000" height="150"></canvas> --}}
                        <button id="recordButton" class="btn btn-secondary">
                            {{-- <img id="record" src="{{ asset('images/mic128.png') }}" width="75" height="75"> --}}
                            <i class="fas fa-microphone"></i>
                            <span id="rectext">@lang('messages.RecorderRec')</span>
                        </button>
                        <h4 id="rectime"><time>00:00:00</time></h4>
                        <audio src="{{ asset('audios/ding.mp3') }}" id="reminder-audio"></audio>
                        <div class="text-center">@lang('messages.RecorderPlaybackSubmitLabel')</div>
                        <canvas id="wavedisplay" width="1000" height="150"></canvas>
                        <div id="audioplayer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after-body')
    <script>
        const user_id = '{{$date}}' + '_' + '{{$consultant}}' + '_' + '{{$elicitor}}';
        const consultant = '{{$consultant}}';
        const date = '{{$date}}';
        const h1 = document.getElementById('rectime');
        const start = document.getElementById('recordButton');
        const reminder_audio = document.getElementById('reminder-audio');
        var recording_in_progress;
        var seconds = 0;
        var minutes = 0;
        var hours = 0;
        var t;

        function add() {
            t++;
            seconds++;
            if (seconds >= 60) {
                seconds = 0;
                minutes++;
                if (minutes >= 60) {
                    minutes = 0;
                    hours++;
                }
            }

            if (t % 600 === 0) {
                start.classList.add('automatic');
                continueRecording(start);
                //reminder_audio.play();
                h1.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);
                timer();
            } else {
                h1.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);
                timer();
            }
        }

        function timer() {
            t = setTimeout(add, 1000);
        }

        timer();

        window.onload=function() {
            clearTimeout(t);
            h1.textContent = "00:00:00";
            seconds = 0;
            minutes = 0;
            hours = 0;
            recording_in_progress = false;
        }

        const beforeUnloadListener = (event) => {
            event.preventDefault();
            return event.returnValue = 'Please stop the recording before closing the page?';
        };

        start.onclick = () => {
            toggleRecording(start);

            if (start.classList.contains('recording')) {
                addEventListener("beforeunload", beforeUnloadListener, {capture: true});
            } else {
                removeEventListener("beforeunload", beforeUnloadListener, {capture: true});
            }

            if (recording_in_progress) {
                clearTimeout(t);
                h1.textContent = '00:00:00';
                seconds = 0;
                minutes = 0;
                hours = 0;
                recording_in_progress = false;
            } else {
                timer();
                recording_in_progress = true;
            }
        }
    </script>
@endsection
