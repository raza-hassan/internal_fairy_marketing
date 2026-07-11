@extends('layouts.app', ['activePage' => 'newleads', 'titlePage' => __('Products')])

@section('content')
@include('leads.sidebar')

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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="ps-items-listing">

            @if(count($clients_added) > 0)
                <div class="header__left">
                    <h3>New Record Added</h3>
                </div>

                <div class="ps-section__content" style="margin-top: 0px !important">
                    <div class="table-responsive">
                        <table class="table ps-table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Source</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients_added as $client)
                                <tr>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{$client->address}}</td>
                                    <td>{{$client->leadSource->type}}</td>
                                    <td>
                                        {{$client->assigned->name}}<br>
                                        <span style="color: grey;">{{$client->assigned->designation_name}}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(count($clients_exist) > 0)

                <div class="header__left" style="margin-top: 10px !important">
                    <h3>Record Already Exist</h3>
                </div>

                <div class="ps-section__content" style="margin-top: 0px !important">

                    <div class="table-responsive">
                        <table class="table ps-table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Source</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients_exist as $client)
                                <tr>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{$client->address}}</td>
                                    <td>{{$client->leadSource->type}}</td>
                                    <td>
                                        {{$client->assigned->name}}<br>
                                        <span style="color: grey;">{{$client->assigned->designation_name}}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

        </section>

    </div>

@endsection
