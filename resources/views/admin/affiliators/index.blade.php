@extends('layouts.app', ['activePage' => 'affiliators', 'titlePage' => __('Products')])
@section('content')
@include('admin.affiliators.sidebar')
<div class="ps-main__wrapper">
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
    @if ($errors->any())
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Affiliators</h3>
        </div>
        <div class="header__center">
        </div>
        <div class="header__right">
            @if(Auth::user()->department_id == 3 || Auth::user()->role == 0 || Auth::user()->role == 1)
            <a class="ps-btn success" href="{{ url('admin/affiliators/create') }}"><i class="icon icon-plus mr-2"></i>New Affiliator</a>
            {{-- href="{{ url('admin/lead/create') }}" --}}
            {{-- href="{{ route('affiliators.create') }}" --}}
            @endif
        </div>
    </header>
    <section class="ps-items-listing">
        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{ url('admin/affiliators/search') }}" method="post">
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
                                    <input class="form-control" name="phone" id="input-name" type="text" placeholder="{{ __('Enter Cell') }}" value="{{old('phone')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group"><label>&nbsp; </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="ps-btn success form-control" type="submit" name="submit" value="Filter">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if(!empty($affiliators))
        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Type</th>
                            <th>Total Leads</th>
                            <th>Address</th>
                            <th>Added</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($affiliators as $affiliator)
                        <tr>
                            <td>{{ $affiliator->id }}</td>
                            <td><a href="{{route('affiliators.edit',$affiliator->id)}}"><strong>{{ $affiliator->name }}</strong></a></td>
                            <td>{{ $affiliator->email }}</td>
                            <td>{{ $affiliator->phone }}</td>
                            <td>{{ $affiliator->type }}</td>
                            <td><a href="{{url('affiliator/leads', $affiliator->id)}}">{{ $affiliator->leads_count()->count() }}</a></td>
                            <td>{{ $affiliator->address }}</td>
                            <td>{{date('M d, Y', strtotime($affiliator->created_at))}}</td>
                            <td>
                                @if($affiliator->status == 1)
                                Active
                                @else
                                Inactive
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('affiliators.destroy', $affiliator->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    @if($affiliator->status == 1)
                                    {{--<a rel="tooltip" href="{{ route('affiliators.edit', $affiliator->id) }}" data-original-title="" title="">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>--}}
                                    <a rel="tooltip" href="{{ url('affiliator/lead', $affiliator->id) }}" data-original-title="" title="">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>
                                    @endif
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
            {{ $affiliators->links() }}
        </div>
        @else
        No Record Found!
        @endif
    </section>
</div>
@endsection
