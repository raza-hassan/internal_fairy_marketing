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
        <title>Fairy Marketing</title>
        <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('public/plugins/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset('public/fonts/Linearicons/Font/demo-files/demo.css')}}">
        <link rel="stylesheet" href="{{ asset('public/plugins/owl-carousel/assets/owl.carousel.css')}}">
        <link rel="stylesheet" href="{{ asset('public/plugins/summernote/summernote-bs4.min.css')}}">
        <link rel="stylesheet" href="{{ asset('public/plugins/apexcharts-bundle/dist/apexcharts.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/magnific-popup/magnific-popup.css') }}" />
        <!-- Specific Page Vendor CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css') }}" />
        <link href="{{ asset('public/css/select2.min.css') }}" rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" />
        <!-- Specific Page Vendor CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
        {{-- ============================================================================ --}}
        <!-- Theme CSS -->
        {{-- <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme.css') }}" /> --}}
        {{-- ============================================================================ --}}
        <!-- Skin CSS -->
        <link rel="stylesheet" href="{{ asset('assets/stylesheets/skins/default.css') }}" />
        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme-custom.css') }}">
        <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
        <!-- Head Libs -->
        <link rel="stylesheet" href="{{ asset('public/css/style.css')}}">
        <link rel="stylesheet" href="{{ asset('public/css/custom.css')}}">
        <script src="{{ asset('public/js/jquery-3.1.6.js') }}"></script>
        <script src="{{ asset('public/js/jquery-ui.js') }}"></script>
    </head>
    <body>
        <input type="hidden" name="site_url" value="{{url('/')}}" id="base_url">
        <header class="header--mobile">
            <div class="header__left">
                <button class="ps-drawer-toggle"><i class="icon icon-menu"></i></button>
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
                    <a class="navbar-toggler openbtn" onclick="openNav()" data-toggle="open-navbar1">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </a>

                    <a href="{{url('/')}}">
                        <img src="{{ asset('public/img/logo.png')}}" alt="">
                    </a>
                </div>
                    <div class="navbar-menu" id="open-navbar1">
                        <ul class="navbar-nav">

                            @if(Auth::guard('affiliator')->user()->type=='Freelancer')

                                <li class="{{ $activePage == 'dashboard' ? 'active' : '' }}"><a href="{{url('affiliator')}}">Dashboard</a></li>
                                <li class="{{ $activePage == 'leads' ? 'active' : '' }}"><a href="{{url('affiliator/leads')}}">Leads</a></li>
                                <li class="{{ $activePage == 'clients' ? 'active' : '' }}"><a href="{{url('affiliator/clients')}}">Clients</a></li>
                                <li class="{{ $activePage == 'inventory' ? 'active' : '' }}"><a href="{{url('affiliator/inventory')}}">Inventory</a></li>

                            @elseif(Auth::guard('affiliator')->user()->type=='Dealer')

                                <li class="{{ $activePage == 'dashboard' ? 'active' : '' }}"><a href="{{url('affiliator')}}">Dashboard</a></li>
                                <li class="{{ $activePage == 'inventory' ? 'active' : '' }}"><a href="{{url('affiliator/inventory')}}">Inventory</a></li>

                            @endif


                        </ul>
                    </div>


                <div  class="bottom-header">


                    <a href="{{url('affiliator/lead/create')}}" class="add-leads-icon bottom-icon">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>


                    {{-- Notification bell intentionally disabled for affiliators/freelancers.
                         Re-enable by including @include('load_notification'), same as resources/views/layouts/app.blade.php,
                         once this guard (Auth::guard('affiliator')) is supported by Helper::notificationsForCurrentUser(). --}}

                    {{-- <a href="{{url('affiliator')}}" class="add-leads-bell bottom-icon">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </a> --}}
                    <a href="#" class="add-leads-user bottom-icon">
                        <div class="dropdown">
                            @if(Auth::guard('affiliator')->user()->profile != '')
                                <img src="{{ url('storage/app/public/'.Auth::guard('affiliator')->user()->profile) }}">
                            @else
                                <img src="{{ asset('public/img/user.png')}}" />
                            @endif
                            <span class="caret"></span>
                            <div class="dropdown-content">
                            <p><a href="{{url('affiliator/profile/edit')}}" class="add-leads-bell bottom-icon">Profile</a></p>
                            <p><a href="{{url('affiliator/logout')}}" class="add-leads-bell bottom-icon">Log Out</a></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </nav>
        <div class="ps-site-overlay">
            <img src="{{ asset('public/img/loading.gif')}}" alt="">
        </div>
        <main class="ps-main">
            @yield('content')
        </main>
        <div class="footer">&COPY;{{date('Y')}} Fairy Marketing.All rights reversed.</div>
        <div class="footer-menu">
            <ul>
                <li><a href="{{url('/#')}}">Policies</a></li>
            </ul>
        </div>
        <script src="{{ asset('assets/vendor/modernizr/modernizr.js') }}"></script>
        <!--<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>-->
        <script src="{{ asset('public/js/select2.min.js') }}" type='text/javascript'></script>
        <!-- Vendor -->
        <!--<script src="{{ asset('assets/vendor/jquery/jquery.js')}}"></script>-->
        <script src="{{ asset('assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.js')}}"></script>
        <script src="{{ asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
        <script src="{{ asset('assets/vendor/magnific-popup/magnific-popup.js')}}"></script>
        <script src="{{ asset('assets/vendor/jquery-placeholder/jquery.placeholder.js')}}"></script>
        <!-- Specific Page Vendor -->
        <script src="{{ asset('assets/vendor/summernote/summernote.js')}}"></script>
        <script src="https://kit.fontawesome.com/4cfbdf6d01.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="{{ asset('public/js/lead.js')}}"></script>
        <script>

    </script>
    </body>
</html>
