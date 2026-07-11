@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.sidebar')

<div class="ps-main__wrapper">

    <header class="header--dashboard">

        <div class="header__left">
            <h3>Staff In Trash </h3>
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

                <form class="ps-form--filter" action="{{url('user/search-in-trash')}}" method="post">
                    @csrf
                    @method('post')

                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

                            <div class="form-group">

                                <label>Name </label>

                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Enter Name') }}" value="{{old('name' , app('request')->input('name') ) }}">
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
                                    <input class="form-control" name="telephone1" id="input-name" type="text" placeholder="{{ __('Enter Cell') }}" value="{{old('telephone1' , app('request')->input('telephone1'))}}">
                                </div>
                            </div>
                        </div>



                        @if(Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14)

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

                                <div class="form-group">

                                    <label>Offices </label>

                                    <div class="bootstrap-select fm-cmp-mg">

                                        <?php

                                            $office_id = 0;

                                            if(app('request')->input('office_id') != ''){

                                                $office_id = app('request')->input('office_id');

                                            }else{

                                                $office_id = Auth::user()->office_id;

                                            }

                                        ?>

                                        <select class="ps-select  {{ $errors->has('office_id') ? ' is-invalid' : '' }}" id="input-office_id" style="width: 100%"  title="office Name" name="office_id" required="true" aria-required="true">

                                            <option selected disabled >Select Office</option>

                                                @foreach($offices as $office)

                                                    <option <?php if($office_id == $office->id){ echo 'selected'; } ?> value="{{$office->id}}" @if(old('office_id')==$office->id ) selected  @endif  >{{$office->name}}</option>

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

                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Office</th>
                            <th>Status</th>
                            <th> Clients</th>
                            <th> Affilators</th>
                            <th> Leads</th>
                            @if(Auth::user()->can('trashed.staff.restore') || Auth::user()->can('trashed.staff.delete'))
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

                                        <div class="inner-side"><label>Designation: </label><span>{{ $user->designation->name }}</span></div>

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

                            @if(Auth::user()->can('trashed.staff.restore') || Auth::user()->can('trashed.staff.delete'))
                                <td>
                                    @if(Auth::user()->can('trashed.staff.restore'))
                                        <a rel="tooltip" href="{{ url('staff/active/again',$user->id) }}" style="margin: 8px;" title="Active-It" onclick="return confirm('{{ __('Are you sure you want to Active this record?') }}') ? true : false;"><i class="fa fa-undo" aria-hidden="true"></i></a>
                                    @endif

                                    @if(Auth::user()->can('trashed.staff.delete'))
                                        <a rel="tooltip" href="{{ url('staff/delete/permanently',$user->id) }}" data-original-title="" title="Delete" onclick="return confirm('{{ __('Are you sure you want to delete this record?') }}') ? true : false;"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    @endif
                                </td>
                            @endif
                            {{-- ============================================================================================== --}}



                            <td></td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        {{ $users->links() }}

        @else

        No Record Found!

        @endif

    </section>

</div>


@endsection

