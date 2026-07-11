@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])

@section('content')

@include('users.sidebar')

<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Staff</h3>
        </div>
        <div class="header__center">

        </div>
        <div class="header__right">
            
        </div>
        @if (session('status'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Success! </strong> {{ session('status') }}
                </div> 
            </div>
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
                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Enter Name') }}" value="{{old('name')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="email" id="input-name" type="email" placeholder="{{ __('Enter Email') }}" value="{{old('email')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Cell </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="telephone1" id="input-name" type="text" placeholder="{{ __('Enter Cell') }}" value="{{old('telephone1')}}">
                                </div>
                            </div>
                        </div>
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
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Team Lead</th>
                            <th>Designation</th>
                            <th>Phone</th>
                            <th>Leads</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            
                            <td>
                                @if($user->parentname)
                                {{ $user->parentname->name }}
                                @else
                                Null
                                @endif
                                
                            </td>
                            <td>
                                {{$user->designation->name}}
                            </td>
                            <td>{{ $user->telephone1 }}</td>
                            <td>
                                @if($user->status == 1)
                                Active
                                @else
                                Inactive
                                @endif
                            </td>

                            <td>{{$user->created_at}}</td>
                            <td>
                                @if(Auth::user()->id ==  $user->id && Auth::user()->role == 3 || Auth::user()->role == 4)
                                    <a rel="tooltip" href="{{ url('profile/edit') }}" data-original-title="" title="">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                        <div class="ripple-container"></div>
                                    </a>
                                @endif
                                @if(Auth::user()->role == 1)
                                <a rel="tooltip" href="{{ url('user/edit', $user->id) }}" data-original-title="" title="">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                        <div class="ripple-container"></div>
                                    </a>
                                @endif
                            </td>
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