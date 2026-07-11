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


                    {{--=============== Notification Bell Icon Code================ --}}
                {{-- <div  id="fairy_notification">
                    @php
                        $notifications = App\Models\Notification::where('show_to' , Auth::guard('affiliator')->user()->id)->where('user_read_at' , Null)->where('read_by_user' , Null)->orderBy('id' , 'desc')->get();
                        $count=count($notifications);
                    @endphp
                    <li class="dropdown" >
                        <a href="#" class="add-leads-bell bottom-icon" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-bell" aria-hidden="true"></i>
                            <span class=" badge badge-light count" id="count" >{{$count}}</span>
                        </a>
                        <ul class="dropdown-menu"  >
                            <div class="form-style-2 scrol notification-sec">
                                <form action="" method="post" >
                                    @foreach ($notifications as $notification)

                                    @if (Auth::guard('affiliator')->user()->id==$notification->show_to)
                                        <li>
                                            <div class="main-container" id="remove_card" >
                                                <div class="cards">
                                                    <div class="card card-4">
                                                        <p class="card__exit mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                            <a href="#" ><i class="fas fa-times" style="font-size: 17px;"> </i></a>
                                                        </p>
                                                        <div class="card__icon">
                                                            <p style=" font-size:18px; color: rgb(145, 0, 113)"><b>  {{$notification->type}}  </b></p>
                                                        </div>
                                                        <div class="card__title">
                                                                <p class="card__title" style="margin-left: 5%; margin-top: 5px;">

                                                                    @if($notification->redirect == 'leads')
                                                                    {{$notification->msg_body}}
                                                                    @else
                                                                    <?php echo base64_decode($notification->msg_body); ?>
                                                                    @endif
                                                                </p>
                                                            </a>
                                                        </div>
                                                        @if($notification->redirect=='leads')
                                                            <p class="card__apply"  >
                                                                <a href="{{url('/leads')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                                    <strong> View Lead </strong><i class="fas fa-arrow-right" ></i>
                                                                </a>
                                                            </p>
                                                        @elseif($notification->redirect=='todolist')
                                                            <p class="card__apply"  >
                                                                <a href="{{url('todos/today')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                                    <strong> View Meeting </strong><i class="fas fa-arrow-right" ></i>
                                                                </a>
                                                            </p>
                                                        @else
                                                            <p class="card__apply" >
                                                                <a href="{{url('/affiliators')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                                    <strong> View Affiliator </strong><i class="fas fa-arrow-right" ></i>
                                                                </a>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @else
                                        {{'No New Notification'}}
                                    @endif
                                    @endforeach
                                </form>
                            </div>
                        </ul>
                    </li>
                    <style>
                        .scrol {
                            overflow-x: hidden;
                            overflow-y: scroll;
                            min-height: 200px;
                            max-height: 400px;
                            background: white;
                        }
                        .dropdown-menu {
                            position: absolute;
                            top: 100%;
                            left: auto !important;
                            right: -50% !important;
                            z-index: 1000;
                            float: left;
                            min-width: 160px;
                            padding: 5px 0;
                            margin: 2px 0 0;
                            list-style: none;
                            font-size: 14px;
                            text-align: left;
                            background-color: #ffffff;
                            border: 1px solid #cccccc;
                            border: 1px solid rgba(0, 0, 0, 0.15);
                            border-radius: 4px;
                            -webkit-box-shadow: 0 6px 12px rgb(0 0 0 / 18%);
                            box-shadow: 0 6px 12px rgb(0 0 0 / 18%);
                            background-clip: padding-box;
                        }
                        .main-container {padding: 15px;}
                        #remove_card .heading {text-align: center;}
                        #remove_card .heading__title { font-weight: 600;}
                        #remove_card .heading__credits {
                            margin: 10px 0px;
                            color: #888888;
                            font-size: 25px;
                            transition: all 0.5s;}
                        #remove_card .heading__link {text-decoration: none;}
                        #remove_card .heading__credits .heading__link {color: inherit;}
                        #remove_card .cards {
                            margin-left: 5px;
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: space-between; }
                        #remove_card  .card {
                            padding: 10px;
                            width: 380px;
                            z-index: 1000;
                            min-height: 100px;
                            max-height: 250px;
                            /* margin-right: 15px; */
                            display: grid;
                            grid-template-rows: 14px 23px 1fr 30px;
                            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.25);
                            border-radius: 10px;
                            transition: all 0.2s;
                        }
                        #remove_card .card:hover { box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.4); transform: scale(1.05); }
                        #remove_card .card__link, .card__exit, .card__icon {
                            position: relative;
                            text-decoration: none;
                            color: rgba(255, 255, 255, 0.9);}
                        #remove_card  .card__link::after {
                            position: absolute; top: 25px;
                            left: 0; content: "";
                            width: 0%; height: 3px;
                            background-color: rgba(255, 255, 255, 0.6);
                            transition: all 0.5s;
                        }
                        #remove_card .card__link:hover::after {width: 100%;}
                        #remove_card .card__exit {grid-row: 1/2; justify-self: end;}
                        #remove_card .card__icon { grid-row: 2/3; font-size: 15px;}
                        #remove_card .card__title { grid-row: 3/5; font-weight: 400; color: #ffffff;}
                        #remove_card .card__title2 { grid-row: 5/7; font-weight: 400; color: #ffffff;}
                        #remove_card .card__apply {grid-row: 7/8; align-self: center;}
                        /* CARD BACKGROUNDS */
                        #remove_card .card-1 {background: radial-gradient(#1fe4f5, #3fbafe);}
                        #remove_card .card-2 { background: radial-gradient(#fbc1cc, #fa99b2);}
                        #remove_card .card-3 {background: radial-gradient(#76b2fe, #b69efe);}
                        #remove_card .card-4 { background: radial-gradient(#60efbc, #58d5c9);}
                        #remove_card .card-5 { background: radial-gradient(#446161, #446161);}
                        /* RESPONSIVE */
                        @media (max-width: 1600px) { .cards {  justify-content: center;} }
                    </style>
                </div> --}}
                    {{--=============== End Notification Bell Icon Code================ --}}


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
