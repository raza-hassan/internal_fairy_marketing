@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.rolepermissions.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Permissions</h3>
        </div>
        <div class="header__center"></div>

        @if(Auth::user()->can('permission.create'))
            <div class="header__right">
                <a class="ps-btn success btn-role-permission " href="{{ url('permission/create') }}"><i class="icon icon-plus mr-2"></i>new Permission</a>
            </div>
        @endif

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

    @if (count($errors) > 0)
        <div class = "alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .ps-table tbody tr td{
            width: auto !important;
        }
    </style>
    <section class="ps-items-listing">

        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Name </th>
                            <th>Group Name </th>
                            @if(Auth::user()->can('permission.edit') || Auth::user()->can('permission.delete'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $key => $item)

                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->group_name }}</td>
                                @if(Auth::user()->can('permission.edit') || Auth::user()->can('permission.delete'))
                                    <td>
                                        @if(Auth::user()->can('permission.edit'))
                                            <a href="{{ url('permission/edit',$item) }}" class="mr-10"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        @endif

                                        @if(Auth::user()->can('permission.delete'))
                                            <a href="{{url('permission/delete', $item)}}" onclick="return confirm('{{ __('Are you sure you want to delete this record?') }}') ? true : false;"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                @endif
                            </tr>

                        @empty
                            <tr>
                               <td  colspan="4"> No Record Found </td>
                            </tr>
                        @endforelse

                    </table>
            </div>
        </div>

        <div class="ps-section__footer">
            <p>Showing {{($permissions->currentpage()-1)*$permissions->perpage()+1}} to {{$permissions->currentpage()*$permissions->perpage()}}
                of  {{$permissions->total()}} entries
            </p>
            {{ $permissions->appends(request()->except('page'))->links() }}
        </div>

    </section>
</div>
@endsection
