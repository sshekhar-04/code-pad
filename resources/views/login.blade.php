
@extends('layouts.layout')
    @section('body')
        <div class="custom-flash {{ Session::get('class') }} ">{{ Session::get('message') }}</div>
        <div id="background"></div>

    @endsection
    @section('content')

        <div class="container-fluid">
            <div class="row">
                <div class="login col-xs-12 col-sm-6 col-sm-push-3 col-md-4 col-md-push-4 ">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#student" aria-controls="student" role="tab" data-toggle="tab">Student</a></li>
                        <li role="presentation"><a href="#admin" aria-controls="admin" role="tab" data-toggle="tab">Admin</a></li>
                    </ul>
                    <div class="tab-content" style="padding:0;">
                        <div role="tabpanel" class="tab-pane fade in active" id="student">
                            <div class="panel panel-default text-center" id="panel">
                                <div class="panel-heading text-center">
                                    <h3>CodePad <small>logo</small></h3>
                                    <p id="typein">Type in your Roll Number and Password</p>
                                </div>
                                <div class="panel-body text-center">

                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/student/login') }}">
                                            {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('roll_number') ? ' has-error' : '' }}">

                                            <div class="col-sm-10 col-sm-push-1">
                                                <input id="roll_number" type="text" class="form-control" name="roll_number" value="{{ old('roll_number') }}" placeholder="Roll Number" required>

                                                @if ($errors->has('roll_number'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('roll_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                                            <div class="col-sm-10 col-sm-push-1">
                                                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="remember"> Remember Me
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-8 col-md-offset-2">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                                </button>

                                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Password?</a>
                                                <br>
                                                <a class="btn btn-link" href="{{ url('/register') }}">Don't have an account? Register</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="admin">
                            <div class="panel panel-default text-center" id="panel">
                                <div class="panel-heading text-center">
                                    <h3>CodePad <small>logo</small></h3>
                                    <p id="typein">Type in your Admin Email and Password</p>
                                </div>
                                <div class="panel-body text-center">
                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin_login') }}">
                                        {{ csrf_field() }}
                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                            <div class="col-sm-10 col-sm-push-1">
                                                <input id="admin_email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Admin Email ID" required>

                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                                            <div class="col-sm-10 col-sm-push-1">
                                                <input id="admin_password" type="password" class="form-control" name="password" placeholder="Admin Password" required>
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="remember"> Remember Me
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-8 col-md-offset-2">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-btn fa-sign-in"></i> Admin Login
                                                </button>

                                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Password?</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

