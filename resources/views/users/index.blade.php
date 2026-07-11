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
            @if(Auth::user()->role == 0)
            <a class="ps-btn success" href="{{ url('admin/user/create') }}"><i class="icon icon-plus mr-2"></i>New User</a>
            @endif
        </div>

    </header>
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
    <section class="ps-items-listing">

        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="index.html" method="get">
                    <div class="ps-form__left">

                        <div class="form-group">
                            <select class="ps-select">
                                <option value="0">User Type</option>
                                <option value="1">Manager</option>
                                <option value="2">Employee</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="ps-select">
                                <option value="0">User Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="ps-form__right">
                        <button class="ps-btn ps-btn--gray"><i class="icon icon-funnel mr-2"></i>Filter</button>
                    </div>
                </form>



            </div>

        </div>
        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Date</th>
			    <th>Actions</th>

                            @if(Auth::user()->role == 0)
                            <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td><a href="{{ url('admin/user/edit', $user) }}"><strong>{{ $user->name }}</strong></a></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telephone1 }}</td>
                            <td>
                                {{$user->designation->name}}
                            </td>
                            <td>{{ $user->department->name }}</td>
                            <td>
                                @if($user->status == 1)
                                Active
                                @else
                                Inactive
                                @endif
                            </td>

                            <td>{{$user->created_at}}</td>

			    <td>
                                <form action="{{ url('admin/user/destroy', $user) }}" method="post">
                                    @csrf
                                    @method('delete')

                                    {{--<a rel="tooltip" href="{{ url('admin/user/edit', $user) }}" data-original-title="" title="">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                        <div class="ripple-container"></div>
                                    </a>--}}
                                    @if(Auth::user()->role == 0)
                                    <button type="button" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        <div class="ripple-container"></div>
                                    </button>
                                    @endif
                                </form>
                            </td>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ps-section__footer">
            <p>Show 10 in 30 items.</p>
            <ul class="pagination">
                <li><a href="#"><i class="icon icon-chevron-left"></i></a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#"><i class="icon-chevron-right"></i></a></li>
            </ul>
        </div>
    </section>
</div>

@endsection
