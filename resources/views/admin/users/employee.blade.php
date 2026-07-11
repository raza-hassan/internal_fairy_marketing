@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])

@section('content')
@include('users.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Manage Staff</h3>
        </div>
        <div class="header__center">

        </div>
        <div class="header__right"></div>
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
                            <th>Manager</th>
                            <th>Phone</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td><a href="#"><strong>{{ $user->name }}</strong></a></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->parent != 0)
                                {{$user->parentname->name}}
                                @endif
                            </td>
                            <td>{{ $user->telephone1 }}</td>
                            <td>
                                @if($user->role == 1)
                                <span class="ps-badge success">Manager</span>
                                @else
                                <span class="ps-badge gray">Employee</span>
                                @endif
                            </td>
                            <td>{{ $user->department }}</td>
                            <td>
                                @if($user->status == 1)
                                Active
                                @else
                                Inactive
                                @endif
                            </td>
                            
                            <td>{{$user->created_at}}</td>
                            
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