@extends('layouts.app', ['activePage' => 'clients', 'titlePage' => __('Products')])
@section('content')
@include('clients.sidebar')
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

    <header class="header--dashboard">
        <div class="header__left">
            <h3>Clients ({{$clients->total()}})</h3>
        </div>
        <div class="header__center">
        </div>

        {{-- @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 ) --}}
        @if(Auth::user()->can('client.create'))
            <div class="header__right">
      		<a class="ps-btn success" href="{{ url('client/create') }}"><i class="icon icon-plus mr-2"></i>New Client</a>
            </div>
        @endif

	{{-- @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 )
            <div  class="header__right">
                <a class="ps-btn success" href="{{url('client-leads-import') }}"><i class="icon icon-plus mr-2"></i>Clients Import</a>
            </div>
        @endif --}}



    </header>
    <section class="ps-items-listing">
        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('clients-search')}}" method="get">
                    <!-- @csrf
                    @method('post')-->
                    <!-- <input type="hidden" name="search_person" value="{{$search_person}}">
                    <input type="hidden" name="current_user" value="{{$id}}">-->
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

                        @if(Auth::user()->role != 11 && Auth::user()->role != 12 )
                            @if(count($users) > 0)
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Allocation </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <select class="form-control" name="user_id" id="user_id">
                                                @if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                                                <option value="0">All</option>
                                                @endif
                                                <?php
                                                $user_id = 0;
                                                if(app('request')->input('user_id') != ''){
                                                    $user_id = app('request')->input('user_id');
                                                }else{
                                                    $user_id = Auth::user()->id;
                                                }
                                                ?>
                                                @foreach($users as $user)
                                                <option <?php if($user_id == $user->id ){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif


                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Records Show </label>
                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="ps-select form-control" style="width: 100%" name="records">
                                        <option value="30"   <?php if (app('request')->input('records') == '30')  {echo 'selected';} ?> > 30   </option>
                                        <option value="50"   <?php if (app('request')->input('records') == '50')  {echo 'selected';} ?> > 50   </option>
                                        <option value="100"  <?php if (app('request')->input('records') == '100') {echo 'selected';} ?> > 100  </option>
                                        <option value="200"  <?php if (app('request')->input('records') == '200') {echo 'selected';} ?> > 200  </option>
                                        <option value="500"  <?php if (app('request')->input('records') == '500') {echo 'selected';} ?> > 500  </option>
                                        <option value="700"  <?php if (app('request')->input('records') == '700') {echo 'selected';} ?> > 700  </option>
                                        <option value="1000" <?php if (app('request')->input('records') == '1000'){echo 'selected';} ?> > 1000 </option>
                                        <option value="all"  <?php if (app('request')->input('records') == 'all') {echo 'selected';} ?> > All  </option>
                                    </select>

                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Offices </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <?php
                                            $office_id = 0;
                                            if(app('request')->input('office_id') != ''){
                                                $office_id = app('request')->input('office_id');
                                            }
                                            // else{
                                            //     $office_id = Auth::user()->office_id;
                                            // }
                                        ?>
                                        <select class="ps-select  {{ $errors->has('office_id') ? ' is-invalid' : '' }}" id="input-office_id" style="width: 100%"  title="office Name" name="office_id" required="true" aria-required="true">
                                            <option value="0" selected disabled >Select Office</option>
                                            {{-- <option value="0">--All--</option> --}}
                                                @foreach($offices as $office)
                                                    <option <?php if($office_id == $office->id){ echo 'selected'; } ?> value="{{$office->id}}" @if(old('office_id')==$office->id ) selected  @endif  >{{$office->name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
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

                            @if(Auth::user()->can('lead.create') || Auth::user()->can('client.trash'))
                                <th>Actions</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>

                            <td>
                                @if(Auth::user()->can('client.edit'))
                                    <a href="{{url('client/edit',$client)}}"><strong>{{ $client->name }}</strong></a>
                                @else
                                    <strong>{{ $client->name }}</strong>
                                @endif
                            </td>

                            <td style="word-break: break-all;">{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>
                                @if($client->assigned != '')
                                {{$client->assigned->name}}
                                @endif
                            </td>
                            <td>
                                @if(Auth::user()->can('lead.view'))
                                    <a href="{{url('clients/leads',$client)}}">{{ $client->leads_count()->count() }}</a>
                                @else
                                    {{ $client->leads_count()->count() }}
                                @endif
                            </td>
                            <td style="word-break: break-all;">{{ $client->address }}</td>
                            <td>{{date('M d, Y', strtotime($client->created_at))}}</td>



                            @if(Auth::user()->can('lead.create') || Auth::user()->can('client.trash'))
                                <td style="width: 10px;">

                                    @if ($client->is_delete=='0')

                                        <form action="{{ url('client/destroy', $client) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <div class="icons-list">
                                                {{-- <a rel="tooltip" href="{{ url('client/edit', $client) }}" data-original-title="" title="">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a> --}}
                                                <!-- <a rel="tooltip" href="{{ url('client/lead', $client) }}" data-original-title="" title="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </a>-->

                                                @if(Auth::user()->can('lead.create'))
                                                    <a rel="tooltip" href="{{ url('client/lead', $client) }}" data-original-title="" title="" class="tooltip add_icon">
                                                        <i class="fa fa-plus " aria-hidden="true"></i>
                                                        <span class="tooltiptext">Add Leads</span>
                                                    </a>
                                                @endif

                                            </div>

                                            @if(Auth::user()->can('client.trash'))
                                                <button type="button" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                    <div class="ripple-container"></div>
                                                </button>
                                            @endif
                                        </form>

                                    @else
                                        {{-- <b>Client In Trash</b> --}}
                                        <div class="text-center">
                                            <i class="fa-solid fa-trash-arrow-up fa-lg" aria-hidden="true"></i>
                                        </div>

                                    @endif
                                </td>
                            @endif

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
