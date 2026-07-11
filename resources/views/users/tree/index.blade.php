@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.sidebar')

<div class="ps-main__wrapper custom_scoll_div">


    @if (session('status'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <strong>Success! </strong> {{ session('status') }}
                </div>
            </div>
        </div>
    @endif


    @if (count($errors) > 0)
        <div class = "alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <header class="header--dashboard">

        <div class="header__left">
            <h3> Staff Tree </h3>
        </div>

        @if (session('status'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        <strong>Success! </strong> {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif

        @if (count($errors) > 0)
            <div class = "alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </header>

    <section class="ps-items-listing">

        @if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
            <div style="background-color:white; margin-bottom:5px;">
                <div class="container-fluid">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec mb-4" >
                        <form action="{{url('search/user/tree')}}" method="post" >
                            @csrf
                            @method('post')


                            <div class="col-lg-5 col-md-4 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Offices </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <?php
                                            $id_office = 0;
                                            if(app('request')->input('id_office') != ''){
                                                $id_office = app('request')->input('id_office');
                                            }
                                        ?>
                                        <select class="ps-select {{ $errors->has('id_office') ? ' is-invalid' : '' }}" id="this_id_office" style="width: 100%"  title="office Name" name="id_office" required aria-required="true">
                                                <option value="" selected disabled >Select Office</option>
                                                @foreach($offices as $office)
                                                    <option <?php if($id_office == $office->id){ echo 'selected'; } ?> value="{{$office->id}}" @if(old('id_office')==$office->id ) selected  @endif  >{{$office->name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>User </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <select class="form-control" name="user_id" id="get_office_mangers" required>
                                            <?php
                                            $user_id = 0;
                                            if(app('request')->input('user_id') != ''){
                                                $user_id = app('request')->input('user_id');
                                            }else{
                                                $user_id = Auth::user()->id;
                                            }
                                            ?>
                                            @foreach($users as $user)
                                                <option <?php if($user_id == $user->id){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group"><label>&nbsp; </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <input class="ps-btn success form-control" type="submit" name="submit" value="Filter">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="ps-section__content" style="margin-top:0px !important;">
            <div class="table-responsive ">

                {{-- Tree Work Start From Here --}}
                    <div class="body genealogy-body genealogy-scroll">
                        <div class="genealogy-tree">

                            {{-- =====Parents Of Auth User===== --}}
                            @foreach($parentUsers as $parent)
                                <ul style="margin: 0px !important; padding-top:5px;" >
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="{{ asset('public/img/tree-user.png') }}"  alt="Member">
                                                    <div class="member-details">
                                                        <h4>{{ $parent->name }}</h4>
                                                        {{-- <h6>({{ $user->designation->name  }})</h6> --}}

                                                        @if ($parent->designation_name)
                                                            <h6>({{ $parent->designation_name  }})</h6>
                                                        @endif

                                                        <ul class="active"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            @endforeach

                            {{-- =====Childs Of Auth User===== --}}
                            <ul>
                                <li>
                                    {{-- Root Node --}}
                                    <a href="javascript:void(0);">
                                        <div class="member-view-box">
                                            <div class="member-image">
                                                <img src="{{ asset('public/img/tree-user.png') }}"  alt="Member">
                                                <div class="member-details">
                                                    <h4>{{  $coming_user->name }} @if (count($childUsers) > 0) ({{ count($childUsers) }}) @endif</h4>
                                                    {{-- <h6>({{ $user->designation->name  }})</h6> --}}
                                                    @if ($coming_user->designation_name)
                                                        <h6>({{ $coming_user->designation_name  }})</h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>

                                    {{-- Show All Child of Root Node --}}
                                    @if(count($childUsers) > 0)
                                        @include('users.tree.level-1-child',['childs' => $childUsers])
                                    @endif
                                </li>
                            </ul>

                        </div>
                    </div>

                    <style>

                        .custom_scoll_div{
                            overflow:auto;
                        }


                        /*----------------genealogy-scroll----------*/

                        .genealogy-scroll::-webkit-scrollbar {
                            width: 5px;
                            height: 8px;
                            overflow-y: auto; /* Add vertical scrollbar */

                        }
                        .genealogy-scroll::-webkit-scrollbar-track {
                            border-radius: 10px;
                            background-color: #e4e4e4;
                                    overflow-y: auto; /* Add vertical scrollbar */

                        }
                        .genealogy-scroll::-webkit-scrollbar-thumb {
                            background: #212121;
                            border-radius: 10px;
                            transition: 0.5s;
                                    overflow-y: auto; /* Add vertical scrollbar */

                        }
                        .genealogy-scroll::-webkit-scrollbar-thumb:hover {
                            background: #d5b14c;
                            transition: 0.5s;
                                    overflow-y: auto; /* Add vertical scrollbar */

                        }


                        /*----------------genealogy-tree----------*/
                        .genealogy-body{
                            white-space: nowrap;
                            overflow-y: auto;
                            padding: 50px;
                            min-height: 500px;
                            padding-top: 10px;
                            text-align: center;
                        }
                        .genealogy-tree{
                        display: inline-block;
                        }
                        .genealogy-tree ul {
                            padding-top: 20px;
                            position: relative;
                            padding-left: 0px;
                            display: flex;
                            justify-content: center;
                        }
                        .genealogy-tree li {
                            float: left;
                            text-align: center;
                            list-style-type: none;
                            position: relative;
                            padding: 20px 5px 0 5px;
                        }
                        .genealogy-tree li::before, .genealogy-tree li::after{
                            content: '';
                            position: absolute;
                            top: 0;
                            right: 50%;
                            border-top: 2px solid #ccc;
                            width: 50%;
                            height: 18px;
                        }
                        .genealogy-tree li::after{
                            right: auto; left: 50%;
                            border-left: 2px solid #ccc;
                        }
                        .genealogy-tree li:only-child::after, .genealogy-tree li:only-child::before {
                            display: none;
                        }
                        .genealogy-tree li:only-child{
                            padding-top: 0;
                        }
                        .genealogy-tree li:first-child::before, .genealogy-tree li:last-child::after{
                            border: 0 none;
                        }
                        .genealogy-tree li:last-child::before{
                            border-right: 2px solid #ccc;
                            border-radius: 0 5px 0 0;
                            -webkit-border-radius: 0 5px 0 0;
                            -moz-border-radius: 0 5px 0 0;
                        }
                        .genealogy-tree li:first-child::after{
                            border-radius: 5px 0 0 0;
                            -webkit-border-radius: 5px 0 0 0;
                            -moz-border-radius: 5px 0 0 0;
                        }
                        .genealogy-tree ul ul::before{
                            content: '';
                            position: absolute; top: 0; left: 50%;
                            border-left: 2px solid #ccc;
                            width: 0; height: 20px;
                        }
                        .genealogy-tree li a{
                            text-decoration: none;
                            color: #666;
                            font-family: arial, verdana, tahoma;
                            font-size: 11px;
                            display: inline-block;
                            border-radius: 5px;
                            -webkit-border-radius: 5px;
                            -moz-border-radius: 5px;
                        }

                        .genealogy-tree li a:hover+ul li::after,
                        .genealogy-tree li a:hover+ul li::before,
                        .genealogy-tree li a:hover+ul::before,
                        .genealogy-tree li a:hover+ul ul::before{
                            border-color:  #fbba00;
                        }

                        /*--------------memeber-card-design----------*/
                        .member-view-box{
                            padding:0px 20px;
                            text-align: center;
                            border-radius: 4px;
                            position: relative;
                        }
                        .member-image{
                            width: 100%;
                            position: relative;
                        }
                        .member-image img{
                            width: 60px;
                            height: 60px;
                            border-radius: 6px;
                            background-color :#000;
                            z-index: 1;
                        }

                    </style>

                {{-- Tree Work End At Here --}}

            </div>
        </div>

    </section>
</div>


<script>

    // // By Default Hide... and Show Data on Clcik
    // $(function ()
    // {
    //     $('.genealogy-tree ul').hide();
    //     $('.genealogy-tree>ul').show();

    //     $('.genealogy-tree ul.active').show();
    //     $('.genealogy-tree li').on('click', function (e) {
    //         var children = $(this).find('> ul');
    //         if (children.is(":visible")) children.hide('fast').removeClass('active');
    //         else children.show('fast').addClass('active');
    //         e.stopPropagation();
    //     });
    // });

    // // By Default Show... and Hide Data on Clcik
    // $(function ()
    // {
    //     // $('.genealogy-tree ul').hide(); // By Default Hide... and Show Data on Clcik
    //     $('.genealogy-tree>ul').show(); // By Default Show... and Hide Data on Clcik

    //     $('.genealogy-tree ul.active').show();
    //     $('.genealogy-tree li').on('click', function (e) {
    //         var children = $(this).find('> ul');
    //         if (children.is(":visible")) children.hide('fast').removeClass('active');
    //         else children.show('fast').addClass('active');
    //         e.stopPropagation();
    //     });
    // });


    // // By Default Shwowing Directlly Connected Users only
    $(function ()
    {
        $('.genealogy-tree ul').hide();
        $('.genealogy-tree > ul').show();
        $('.genealogy-tree > ul > li > ul').show(); // Shwowing Directlly Connected Users only

        $('.genealogy-tree ul.active').show();
        $('.genealogy-tree li').on('click', function (e) {
            var children = $(this).find('> ul');
            if (children.is(":visible")) children.hide('fast').removeClass('active');
            else children.show('fast').addClass('active');
            e.stopPropagation();
        });
    });


</script>
@endsection


