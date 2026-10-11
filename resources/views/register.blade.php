@extends('layouts.layout')
    @section('body')
        <div class="custom-flash {{ Session::get('class') }} ">{{ Session::get('message') }}</div>
        <div id="background"></div>
    @endsection

    @section('content')
            <div class="container-fluid">
                <div class="row">
                    <div class="login col-xs-12 col-sm-6 col-sm-push-3 col-md-4 col-md-push-4">
                        <div class="panel panel-default text-center" id="panel">
                            <div class="panel-heading text-center">
                                <h3>CodePad <small>logo</small></h3>
                                <p id="typein">Create your Student Account</p>
                            </div>
                            <div class="panel-body text-center">
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('/students') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <div class="col-sm-10 col-sm-push-1">
                                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Your Name" required>

                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('roll_number') ? ' has-error' : '' }}">
                                        <div class="col-sm-10 col-sm-push-1">
                                            <input id="roll_number" type="text" class="form-control" name="roll_number" value="{{ old('roll_number') }}" placeholder="Roll Number (e.g. 19001001001)" required>

                                            @if ($errors->has('roll_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('roll_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('admision_no') ? ' has-error' : '' }}">
                                        <div class="col-sm-10 col-sm-push-1">
                                            <input id="admision_no" type="text" class="form-control" name="admision_no" value="{{ old('admision_no') }}" placeholder="Admission Number (e.g. 15CSE075)" required>

                                            @if ($errors->has('admision_no'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('admision_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
                                        <div class="col-sm-10 col-sm-push-1">
                                            <select id="year" class="form-control" name="year" required>
                                                <option value="" disabled {{ old('year') ? '' : 'selected' }}>Select Year</option>
                                                <option value="1st Year" {{ old('year') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                                <option value="2nd Year" {{ old('year') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                                <option value="3rd Year" {{ old('year') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                                <option value="4th Year" {{ old('year') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                            </select>

                                            @if ($errors->has('year'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('year') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div class="col-sm-10 col-sm-push-1">
                                            <input id="password" type="password" class="form-control" name="password" placeholder="Enter Password" required>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <div class="col-sm-10 col-sm-push-1">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>

                                            @if ($errors->has('password_confirmation'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-btn fa-user"></i> Register
                                            </button>
                                            <br>
                                            <a class="btn btn-link" href="{{ url('/login') }}">Already have an account? Login</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
