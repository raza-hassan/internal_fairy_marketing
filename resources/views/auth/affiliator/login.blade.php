<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="author" content="">	<meta name="keywords" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <!--        <link href="apple-touch-icon.png" rel="apple-touch-icon">
        <link href="favicon.png" rel="icon">-->
        <title>Fairy Marketing</title>
        <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ asset('public/plugins/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.css') }}" />
        <!-- Theme CSS -->	<link rel="stylesheet" href="{{ asset('assets/stylesheets/theme.css') }}" />
        <!-- Skin CSS -->	<link rel="stylesheet" href="{{ asset('assets/stylesheets/skins/default.css') }}" />
        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme-custom.css') }}">
        <!-- Head Libs -->	<link rel="stylesheet" href="{{ asset('public/css/style.css')}}">
    </head>
    <body class="login-page">
        <div class="ps-site-overlay"></div>
        <main class="ps-main">
            <section class="body-sign">
                <div class="center-sign">
                    <a href="#" class="logo pull-left">
                        <img src="{{asset('public/img/logo.png')}}" height="54" alt="User" />
                    </a>
                    <div class="panel panel-sign">
                        <div class="panel-title-sign mt-xl text-right">
                            <h2 class="title text-uppercase text-bold m-none">
                                <i class="fa fa-user mr-xs"></i> Sign In
                            </h2>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            {{-- @if(Session::has('flash'))
                                <div class="alert alert-danger">
                                    <em> {!! session('message') !!}</em></div>
                            @endif --}}

                            @if(session()->has('message'))
                                <div class="alert alert-danger">
                                    {{ session()->get('message') }}
                                </div>
                            @endif

                            <div class="panel-body">
                                <form class="form" method="POST" action="{{ route('affiliator.login') }}">
                                    @csrf
                                    <div class="form-group mb-lg">
                                        <label>Email</label>
                                        <div class="input-group input-group-icon">
                                            <span class="input-group-addon">
                                                <span class="icon icon-lg">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                            </span>
                                            <input type="email" name="email" class="form-control input-lg" placeholder="{{ __('Email...') }}" required> </div>
                                            @if ($errors->has('email'))
                                                <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group mb-lg">
                                            <div class="clearfix">
                                                <label class="pull-left">Password</label>
                                                <a href="{{url('password/reset')}}" class="pull-right">Lost Password?</a>
                                                <!--<input type="checkbox" onclick="myFunction()" class="show_pass">Show Password </div>-->
                                                <span class="fa fa-fw fa-eye field-icon toggle-password" onclick="myFunction()"></span>

                                                <div class="input-group input-group-icon">
                                                    <span class="input-group-addon">
                                                        <span class="icon icon-lg">
                                                            <i class="fa fa-lock"></i>
                                                        </span>
                                                    </span>
                                                    <input type="password" name="password" id="password" class="form-control input-lg" placeholder="{{ __('Password...') }}" required> </div>

                                                    @if ($errors->has('password'))
                                                        <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <div class="checkbox-custom checkbox-default">
                                                            <input id="RememberMe" type="checkbox" name="remember" id="remember" {{ old( 'remember') ? 'checked' : '' }}>
                                                            <label for="RememberMe">Remember Me</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 text-right">
                                                        <button type="submit" class="btn btn-primary hidden-xs">Sign In</button>
                                                        <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Sign In</button>
                                                    </div>
                                                </div>

                                                <span class="mt-lg mb-lg line-thru text-center text-uppercase">
                                                    <span>or</span>
                                                </span>
                                                <!--<p class="text-center">Don't have an account yet? <a href="{{url('register')}}">Sign Up!</a>-->
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>

                                    <script>
                                        function myFunction()
                                        {
                                        	var x = document.getElementById("password");
                                            if(x.type === "password")
                                            {
                                                x.type = "text";
                                            }
                                            else
                                            {
                                                x.type = "password";
                                            }
                                        }
                                    </script>
