<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="author" content="">
        <meta name="keywords" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <!--        <link href="apple-touch-icon.png" rel="apple-touch-icon">
                <link href="favicon.png" rel="icon">-->
        <title>Fairy Marketing Admin</title>
        <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('public/plugins/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset('public/fonts/Linearicons/Font/demo-files/demo.css')}}">
        <link rel="stylesheet" href="{{ asset('public/plugins/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset('public/plugins/owl-carousel/assets/owl.carousel.css')}}">
        <link rel="stylesheet" href="{{ asset('public/plugins/select2/dist/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{ asset('public/plugins/summernote/summernote-bs4.min.css')}}">
        <link rel="stylesheet" href="{{ asset('public/plugins/apexcharts-bundle/dist/apexcharts.css')}}">
        <link rel="stylesheet" href="{{ asset('public/css/style.css')}}">
    </head>
    <body>
        <header class="header--mobile">
            <div class="header__left">
                <button class="ps-drawer-toggle"><i class="icon icon-menu"></i></button>
                <img src="" alt="">
            </div>
            <div class="header__center">
                <a class="ps-logo" href="#">
                    <img src="{{ asset('public/img/logo.png')}}" alt="">
                </a>
            </div>
            <div class="header__right">
                <a class="header__site-link" href="#"><i class="icon-exit-right"></i></a>
            </div>
        </header>
        @include('layouts.mobile-menu')
        <nav class="navbar">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-toggler" data-toggle="open-navbar1">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </a>
                    <a href="{{url('admin')}}">
                        <img src="{{ asset('public/img/logo.png')}}" alt="">
                    </a>
                </div>
                <div class="navbar-menu" id="open-navbar1">
                    <ul class="navbar-nav">
                        <li class="{{ $activePage == 'dashboard' ? 'active' : '' }}"><a href="{{url('admin')}}">Dashboard</a></li>
                        <li class="{{ $activePage == 'leads' ? 'active' : '' }}"><a href="{{url('admin/leads')}}">  Leads</a></li>
                        <li class="{{ $activePage == 'clients' ? 'active' : '' }}"><a href="{{url('admin/clients')}}">Clients</a></li>
                        <li class="{{ $activePage == 'users' ? 'active' : '' }}"><a href="{{url('admin/users')}}">Staff</a></li>
                        <li class="{{ $activePage == 'affiliates' ? 'active' : '' }}"><a href="{{url('admin/#')}}">Affiliates</a></li>
                        <li class="{{ $activePage == 'inventory' ? 'active' : '' }}"><a href="{{url('admin/inventory')}}">Inventory</a></li>
                        <li class="{{ $activePage == 'accounts' ? 'active' : '' }}"><a href="{{url('admin/#')}}">Accounts</a></li>
                        <li class="{{ $activePage == 'targets' ? 'active' : '' }}"><a href="{{url('admin/#')}}">Targets</a></li>
                        <li class="{{ $activePage == 'reports' ? 'active' : '' }}"><a href="{{url('admin/#')}}">Reports</a></li>
                        <li class="{{ $activePage == 'trainings' ? 'active' : '' }}"><a href="{{url('admin/#')}}">Training</a></li>
                        <li class="{{ $activePage == 'policies' ? 'active' : '' }}"><a href="{{url('admin/#')}}">Policies</a></li>
                        <li class="{{ $activePage == 'documentations' ? 'active' : '' }}"><a href="{{url('admin/#')}}">Documentation</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="ps-site-overlay"></div>
        <main class="ps-main">
            @yield('content')
        </main>
        <script src="{{ asset('public/plugins/jquery.min.js')}}"></script>
        <script src="{{ asset('public/plugins/popper.min.js')}}"></script>
        <script src="{{ asset('public/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset('public/plugins/jquery.matchHeight-min.js')}}"></script>
        <script src="{{ asset('public/plugins/select2/dist/js/select2.full.min.js')}}"></script>
        <script src="{{ asset('public/plugins/summernote/summernote-bs4.min.js')}}"></script>
        @if($activePage == 'dashboard')
        <script src="{{ asset('public/plugins/apexcharts-bundle/dist/apexcharts.min.js')}}"></script>
        <script src="{{ asset('public/js/chart.js')}}"></script>
        @endif
        <script src="{{ asset('public/js/main.js')}}"></script>
    </body>
</html>
