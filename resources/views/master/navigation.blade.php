<?php
$name = 'User';
if(Auth::guard('admin')->check()):
    $name = Auth::guard('admin')->user()->name;
elseif(Auth::guard('teacher')->check()):
    $name = Auth::guard('teacher')->user()->name;
elseif(Auth::guard('student')->check()):
    $name = Auth::guard('student')->user()->name;
endif;
?>
<!-- *** NAVBAR *** -->

<nav class="navbar navbar-default navbar-fixed-top">

  <div class="container">
    <div class="navbar-header">

      <a class="navbar-brand home" href="{{ url('/') }}">
        <img src="{{URL::asset('public/assets/img/logo.png')}}" alt="Universal logo" class="hidden-xs hidden-sm">
      </a>
      <div class="navbar-buttons">
        <button type="button" class="navbar-toggle btn-template-main" data-toggle="collapse" data-target="#navigation">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-align-justify"></i>
        </button>
      </div>
    </div>
    <!--/.navbar-header -->

    <div class="navbar-collapse collapse" id="navigation">

      <ul class="nav navbar-nav navbar-right">
        @if (!(Auth::guard('student')->check() || Auth::guard('teacher')->check() || Auth::guard('admin')->check()))
        <li>
          <a href="{{ url('/login') }}">Login</a>
        </li>
        <li>
          <a href="{{ url('/register') }}">Register</a>
        </li>

        @else
        <li class="">
          <a href="{{ url('/home') }}"> Dashboard </a>
        </li>
        @if(Auth::guard("student")->check())
        <li><a href="{{ url('/students/profile/edit') }}">Edit Profile</a></li>
        @elseif(Auth::guard('admin')->check())
        <li><a href="{{ url('events/create') }}">Create Event</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            Show Users <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            @if(isset(Auth::guard('admin')->user()->type) && Auth::guard('admin')->user()->type == 1)
            <li><a href="{{ url('admin/Admin/Show') }}">Show Admin</a></li>
            @endif
            <li><a href="{{ url('admin/Student/Show') }}">Show Students</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            Add Users <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            @if(isset(Auth::guard('admin')->user()->type) && Auth::guard('admin')->user()->type == 1)
            <li><a href="{{ url('admin/Admin') }}">Add Admin</a></li>
            @endif
            <li><a href="{{ url('admin/Student') }}">Add Students</a></li>
          </ul>
        </li>
        @endif
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            {{ $name }} <span class="caret"></span>
          </a>

          <ul class="dropdown-menu" role="menu">
            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
          </ul>
        </li>

        @endif

    </div>

    <!--/.nav-collapse -->
  </div>
  <!-- /#navbar -->
</nav>
<!-- *** NAVBAR END *** -->