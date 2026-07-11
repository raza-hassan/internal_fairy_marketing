@extends('freelancer.layouts.app', ['activePage' => 'clients', 'titlePage' => __('Products')])
@section('content')
@include('freelancer.leads.sidebar')
<div class="ps-main__wrapper">

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

    <header class="header--dashboard">
        <div class="header__left">
            <h3>Clients ({{$clients->total()}})</h3>
        </div>
        <div class="header__center">
        </div>
        <div class="header__right">
            <a class="ps-btn success" href="{{ url('affiliator/client/create') }}"><i class="icon icon-plus mr-2"></i>New Client</a>
        </div>
    </header>
    <section class="ps-items-listing">
        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('affiliator/clients-search')}}" method="get">

                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Name </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Enter Name') }}" value="{{app('request')->input('name')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="email" id="input-name" type="email" placeholder="{{ __('Enter Email') }}" value="{{app('request')->input('email')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Cell </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="phone" id="input-name" type="text" placeholder="{{ __('Enter Cell') }}" value="{{app('request')->input('phone')}}">
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
        @if(!empty($clients))
        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Allocated To</th>
                            <th>Total Leads</th>
                            <th>Address</th>
                            <th>Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>
                                {{-- <a href="{{url('affiliator/client/edit',$client)}}"> --}}
                                    <strong>{{ $client->name }}</strong>
                                {{-- </a> --}}
                            </td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>
                                @if($client->assigned != '')
                                {{$client->assigned->name}}
                                @endif
                            </td>
                            <td>
                                {{-- <a href="{{url('affiliator/clients/leads',$client)}}"> --}}
                                    {{ $client->leads_count()->count() }}
                                {{-- </a> --}}
                            </td>
                            <td>{{ $client->address }}</td>
                            <td>{{date('M d, Y', strtotime($client->created_at))}}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
<!--        <div class="ps-section__footer">
            {{ $clients->links() }}
        </div>-->
        <div class="ps-section__footer">
            <p>Showing {{($clients->currentpage()-1)*$clients->perpage()+1}} to {{$clients->currentpage()*$clients->perpage()}}
                of  {{$clients->total()}} entries
            </p>
            {{ $clients->appends(request()->except('page'))->links() }}
        </div>
        @else
        No Record Found!
        @endif
    </section>
</div>
@endsection
