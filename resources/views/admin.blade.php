@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                        <br/>
                        {{ Auth::user()->authorized ? 'You are authorized to access data.' : 'You are not authorized to access data - another administrator must authorize you!' }}
                    </div>
                </div>
                @if (Auth::user()->authorized && Gate::allows('manage-users'))
                    <br/>
                    <div class="card">
                        <div class="card-header">Users Awaiting Authorization</div>

                        <div class="card-body">
                            @if (!$users->isEmpty())
                                The following unauthorized users require authorization, or deletion if they should not have access:
                                <table style="width:100%">
                                    <tr><th>ID</th><th>Username</th><th>Email</th><th colspan=2>Status / Actions</th></tr>
                                    @foreach ($users as $user)
                                        <tr><td>{{ $user->id }}</td><td>{{ $user->name }}</td><td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->authorized)
                                                    Authorized
                                                    @if (Auth::user() != $user)
                                                        (<a href="{{ route ('admin_users.deauthorize', $user->id) }}">Deauthorize</a>)
                                                    @endif
                                                @else
                                                    Not Authorized
                                                    @if (Auth::user() != $user)
                                                        (<a href="{{ route ('admin_users.authorize', $user->id) }}">Authorize</a>)
                                                    @endif
                                                @endif
                                            </td>

                                            @if (Auth::user() != $user)
                                                <td>
                                                    <a href="{{ route ('admin_users.destroy', $user->id) }}" onclick="return confirm('Are you sure you wish to delete this user? This cannot be undone!')">Delete User</a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                There are no users awaiting authorization.
                            @endif
                            <br/><br/>
                            <a href="{{ route('admin_users.index') }}">Go to the User Management Page</a>
                        </div>
                    </div>
                @endif
                @if (Auth::user()->authorized && Gate::allows('manage-data'))
                    <br/>
                    <div class="card">
                        <div class="card-header">Submissions</div>
                        <div class="card-body">
                            @if (collect($consent_forms)->map(function ($item, $key) {return strpos(strtolower(Auth::user()->name), strtolower($item->elicitor)) !== false;})->reduce(function ($carry, $item) {return $carry + $item;}) > 0)
                                <table style="width:100%">
                                    <tr><th>ID</th><th>Elicitor</th><th>Consultant</th><th>Date</th><th>Player</th><th>Actions</th></tr>
                                    @foreach ($consent_forms as $consent_form)
                                        @if(strtolower(Auth::user()->name) === strtolower($consent_form->elicitor) || in_array(strtolower(Auth::user()->name), ['ylo', 'mdschwan', 'mollybabel']))
                                            <tr>
                                                <td>
                                                    {{$consent_form->id}}</td><td>{{$consent_form->elicitor}}</td><td>{{$consent_form->consultant}}</td><td>{{$consent_form->local_time}}</td><td>
                                                    <audio controls preload="metadata" style="width:300px;">
                                                        <source src="{{ Storage::url('audio/' . $consent_form->date . '/' . $consent_form->recording_filename) }}" type="audio/wav">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </td>
                                                <td>
                                                    <a href="{{ Storage::url('audio/' . $consent_form->date . '/' . $consent_form->recording_filename) }}" class="btn btn-info" title="Download"><i class="fas fa-download"></i></a>
                                                    <a href="{{ route ('consent_forms.destroy-get', $consent_form->id) }}" class="btn btn-info" title="Delete recording" onclick="return confirm('Are you sure you wish to delete this recording? This cannot be undone!')"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            @else
                                There have been no new submissions.
                                <br/>
                            @endif
{{--                            <br/>--}}
{{--                            <a href="{{ route('consent_forms.index') }}">View All Submissions</a>--}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
