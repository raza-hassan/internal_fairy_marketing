@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Inactive Staff</h3>
        </div>
        <div class="header__center">
        </div>
        <div class="header__right">
            @if(Auth::user()->can('staff.create'))
                <a class="ps-btn success" href="{{ url('user/create') }}"><i class="icon icon-plus mr-2"></i>New User</a>
            @endif
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
        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('user/search')}}" method="post">
                    @csrf
                    @method('post')
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Name </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Enter Name') }}" value="{{old('name' , app('request')->input('name'))}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="email" id="input-name" type="email" placeholder="{{ __('Enter Email') }}" value="{{old('email' , app('request')->input('email'))}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Cell </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="telephone1" id="input-name" type="text" placeholder="{{ __('Enter Cell') }}" value="{{old('telephone1'  , app('request')->input('telephone1'))}}">
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14)
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Offices </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <?php
                                            $id_office = 0;
                                            if(app('request')->input('id_office') != ''){
                                                $id_office = app('request')->input('id_office');
                                            }
                                        ?>
                                        <select class="ps-select {{ $errors->has('id_office') ? ' is-invalid' : '' }}" id="get_office_id" style="width: 100%"  title="office Name" name="id_office" required aria-required="true">
                                                <option value="0" selected disabled >Select Office</option>
                                                @foreach($offices as $office)
                                                    <option <?php if($id_office == $office->id){ echo 'selected'; } ?> value="{{$office->id}}" @if(old('id_office')==$office->id ) selected  @endif  >{{$office->name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>User </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <select class="form-control" name="user_id" id="set_office_mangers">
                                            <?php
                                            $user_id = 0;
                                            if(app('request')->input('user_id') != ''){
                                                $user_id = app('request')->input('user_id');
                                            }
                                            ?>
                                            <option value="0">All</option>
                                            @foreach($managers as $user)
                                                <option <?php if($user_id == $user->id){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>&nbsp; </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="ps-btn success form-control" type="submit" name="submit" value="Filter">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if(!empty($users))
        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table staff-table">
                    <thead>
                        <tr>
                            <!--<th>Staff ID</th>-->
                            <th>Name</th>
                            <th>Email</th>
<!--                            <th>Team Lead</th>-->
                            <th>Phone</th>
                            <th>Office</th>
                            <th>Status</th>
                            <!--<th>Created At</th>-->
                            <th> Clients</th>
                            <th> Affilators</th>
                            <th> Leads</th>
                            @if(Auth::user()->can('staff.trash'))
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <!--<td>{{ $user->id }}</td>-->
                            <td>
                                <div class="client-title tooltip">
                                    <div class="tooltiptext client-info">
                                        <div class="tooltip-head">User Information:</div>
                                        <div class="inner-side"><label>ID: </label><span>{{ $user->id }}</span></div>
                                        <div class="inner-side"><label>Name: </label><span>{{ $user->name }}</span></div>
                                        <div class="inner-side"><label>Designation: </label><span> @if($user->designation)  {{ $user->designation->name }} @endif </span></div>
                                        <div class="inner-side"><label>Manager: </label>
                                            <span>
                                                @if($user->parentname)
                                                {{ $user->parentname->name }}
                                                @else
                                                Null
                                                @endif
                                            </span>
                                        </div>
                                        <div class="inner-side"><label>Created At: </label><span>{{date('M d, Y', strtotime($user->created_at))}}</span></div>

                                        @if(Auth::user()->can('staff.trash'))
                                            @if ($user->role != 5 && $user->role != 13 && $user->role != 14)
                                                @if ($user->leads_count()->orWhere("share_id",$user->id)->count() == 0 && $user->client_count()->count() == 0 && $user->affiliator_count()->count() == 0)
                                                    <div class="inner-side">
                                                        <label>Delete Account: </label>
                                                        <span><a href="{{url('trash/staff/user', $user->id)}}" onclick="return confirm('{{ __('Are you sure you want to trash this record?') }}') ? true : false;">Yes, Delete it.</a></span>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    </div>

                                    @if(Auth::user()->can('staff.edit'))
                                        <a href="{{ url('user/edit', $user->id) }}" class="client-title">
                                            <div class="title-flag">
                                                <strong>{{ $user->name }}</strong>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    @else
                                        <strong>{{ $user->name }}</strong>
                                    @endif

                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telephone1 }}</td>
                            <td>{{ $user->office->name }}</td>
                            <td>
                                @if($user->status == 1)
                                Active
                                @else
                                Inactive
                                @endif
                            </td>
                            {{-- ============================================================================================== --}}
                            <td><a href="{{url('all/clients', $user->id)}}" target="_blank">{{ $user->client_count()->count() }}</a></td>
                            <td><a href="{{url('all/affiliator', $user->id)}}" target="_blank">{{ $user->affiliator_count()->count() }}</a></td>
                            <td><a href="{{url('all/leads', $user->id)}}" target="_blank">{{ $user->leads_count()->orWhere("share_id",$user->id)->count() }}</a></td>

                            @if(Auth::user()->can('staff.trash'))
                                @if ($user->leads_count()->orWhere("share_id",$user->id)->count() == 0 && $user->client_count()->count() == 0 && $user->affiliator_count()->count() == 0)
                                    <td style="text-align: center">
                                        @if ($user->role != 5 && $user->role != 13 && $user->role != 14)
                                           <a href="{{url('trash/staff/user', $user->id)}}" onclick="return confirm('{{ __('Are you sure you want to trash this record?') }}') ? true : false;"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                @else
                                    <td style="text-align: center">
                                        @if ($user->role != 5 && $user->role != 13 && $user->role != 14)
                                            <a href="#" onclick="return confirm('{{ __('You have to transfer this user data to someone first.') }}') ? false : false;"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                @endif
                            @endif
                            {{-- ============================================================================================== --}}
                            <td>
                                @if(Auth::user()->id ==  $user->id && Auth::user()->role == 3 || Auth::user()->role == 4)
                                {{-- <a rel="tooltip" href="{{ url('profile/edit') }}" data-original-title="" title="">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                                <div class="ripple-container"></div>
                                </a> --}}
                                @endif
                                @if(Auth::user()->role == 1)
                                {{-- <a rel="tooltip" href="{{ url('user/edit', $user->id) }}" data-original-title="" title="">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                                <div class="ripple-container"></div>
                                </a> --}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- {{ $users->links() }} --}}
        <div class="ps-section__footer">
            <p>Showing {{($users->currentpage()-1)*$users->perpage()+1}} to {{$users->currentpage()*$users->perpage()}}
                of  {{$users->total()}} entries
            </p>
            {{ $users->appends(request()->except('page'))->links() }}
        </div>
        @else
        No Record Found!
        @endif
    </section>
</div>
@endsection
