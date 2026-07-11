@extends('freelancer.layouts.app', ['activePage' => 'leads', 'titlePage' => __('Products')])

@section('content')
{{-- @include('freelancer.layouts.sidebar') --}}
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

    @if (session('error'))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                <strong>Error! </strong> {{ session('error') }}
            </div>
        </div>
    </div>
    @endif

    <header class="header--dashboard">

        <div class="header__left">
            <h3>Leads ({{$leads->total()}})</h3>
        </div>
        <div class="header__center">

        </div>

        <div class="header__right">
            <a class="ps-btn success" href="{{ url('affiliator/lead/create') }}"><i class="icon icon-plus mr-2"></i>New Lead</a>
        </div>

        <div class="header__right">
            <a class="ps-btn success" href="{{ url('affiliator/leads-import') }}"><i class="icon icon-plus mr-2"></i>Leads Import</a>
        </div>


    </header>
    <section class="ps-items-listing">
        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('affiliator/leads-search')}}" method="get">

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Lead ID </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="lead_id" id="input-name" type="text" placeholder="Enter Lead ID" value="{{app('request')->input('lead_id')}}" >
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 select-date">
                            <div class="form-group">
                                <label>Date Added </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" name="startDate" class="datepicker form-control" placeholder="Enter Start Date" value="{{app('request')->input('startDate')}}">
                                </div>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" name="endDate" class="datepicker form-control" placeholder="Enter End Date" value="{{app('request')->input('endDate')}}">
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Phone </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="phone" id="input-name" type="text" placeholder="{{ __('Enter Phone') }}" value="{{app('request')->input('phone')}}">
                                </div>
                            </div>
                        </div>
                        @if(count($projects) > 0)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Projects </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="project_id">
                                        <option value="0">--All--</option>
                                        @foreach($projects as $project)
                                        <option <?php if(app('request')->input('project_id') == $project->id){ echo 'selected'; } ?> value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- @if(count($sources) > 0)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Sources </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="source_id">
                                        <option value="0">--All--</option>
                                        @foreach($sources as $source)
                                        <option <?php if(app('request')->input('source_id') == $source->id){ echo 'selected'; } ?> value="{{$source->id}}">{{$source->type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif --}}


                        {{-- <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Offices </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <?php
                                        $office_id = 0;
                                            $office_id = app('request')->input('office_id');
                                    ?>
                                    <select class="ps-select  {{ $errors->has('office_id') ? ' is-invalid' : '' }}" id="input_office_id" style="width: 100%"  title="office Name" name="office_id" required="true" aria-required="true">
                                        <option value="0">--All--</option>
                                            @foreach($offices as $office)
                                                <option <?php if($office_id == $office->id){ echo 'selected'; } ?> value="{{$office->id}}" @if(old('office_id')==$office->id ) selected  @endif  >{{$office->name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}


                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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

        @if(!empty($leads))

            {{-- <?php  $todo='';   ?> --}}

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec">
                <form class="ps-form--filter" action="{{url('affiliator/lead/sorting')}}" method="post">
                    @csrf
                    @method('post')
                    <ul class="lead_sorting leads-filter">
                        <li>
                            <span>Show</span>
                            <select name="records" class="form-control">
                                <option value="20"  >20</option>
                                <option value="50"  >50</option>
                                <option value="100" >100</option>
                                <option value="200" >200</option>
                                <option value="500" >500</option>
                                <option value="700" >700</option>
                                <option value="1000" >1000</option>
                                <option value="all" >All</option>
                            </select>
                        </li>
                        <li>
                            <label> Sort by</label>
                            <select name="status" class="form-control">
                                <option value="id">ID</option>
                                <option value="user_id">Allocation</option>
                                <option value="client_id">Client ID</option>
                                <option value="updated_at">Last Updated</option>
                            </select>
                        </li>
                        <li>
                            <label>Order</label>
                            <select name="sort" class="form-control">
                                <option value="ASC">Assending</option>
                                <option value="DESC">Decending</option>
                            </select>
                        </li>
                        <li>
                            <input class="ps-btn success form-control" type="submit" name="submit" value="Go">
                        </li>
                    </ul>
                </form>

                <style>
                    ul.lead_status li.active
                    {
                        background-color: #446161 !important;
                        color: white;
                    }
                </style>

                <ul class="lead_status">
                    <li class="<?php
                    if ($todo == 'today') {
                        echo 'active';
                    }
                    ?>" >
                        <a href="{{url('affiliator/today/lead') }}">Today</a>

                    </li>

                    <li class="<?php
                    if ($todo == 'yesterday') {
                        echo 'active';
                    }
                    ?>"><a href="{{url('affiliator/yesterday/lead')}}">Yesterday</a></li>
                    <li class="<?php
                    if ($todo == 'thisweek') {
                        echo 'active';
                    }
                    ?>"><a href="{{url('affiliator/thisweek/lead')}}">This Week</a></li>
                    <li class="<?php
                    if ($todo == 'lastweek') {
                        echo 'active';
                    }
                    ?>"><a href="{{url('affiliator/lastweek/lead')}}">Last Week</a></li>
                    <li class="<?php
                    if ($todo == 'thismonth') {
                        echo 'active';
                    }
                    ?>"><a href="{{url('affiliator/thismonth/lead')}}">This Month</a></li>
                    <li class="<?php
                    if ($todo == 'lastmonth') {
                        echo 'active';
                    }
                    ?>"><a href="{{url('affiliator/lastmonth/lead')}}">Last Month</a></li>

                </ul>
            </div>

            <div class="ps-section__content">
                <div class="table-responsive">
                    <table class="table ps-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="checkbox[]"></th>
                                <th>Client</th>
                                {{-- <th>Allocated To</th> --}}
                                <th> Designation </th>
                                <th>Lead-ID</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Interest</th>
                                {{-- <th>Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $lead)
                            <tr>
                                <td><input type="checkbox" name="checkbox[]" value="{{$lead->id}}"></td>
                                <td>
                                    @if(!empty($lead->client))
                                    <div class="client-title tooltip">

                                        <div class="tooltiptext client-info">
                                            <div class="tooltip-head">Client Information:</div>
                                            <div class="inner-side"><label>Client Name: </label><span>{{ $lead->client->name }}</span></div>
                                            <div class="inner-side"><label>Client Number: </label><span>{{ str_replace(' ','',$lead->client->phone) }}</span></div>
                                            <div class="inner-side"><label>Client Email: </label><span>{{ $lead->client->email }}</span></div>
                                            <div class="inner-side"><label>City: </label><span>{{ $lead->client->address }}</span></div>

                                        </div>
                                        {{-- <a href="{{url('affiliator/client/edit',$lead->client_id)}}" class="client-title"> --}}
                                            <div class="title-flag">
                                                <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">
                                                <strong>{{ $lead->client->name }}</strong>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </div>
                                        {{-- </a> --}}
                                        <i class="fa fa-th" aria-hidden="true"></i>

                                        {{-- <a href="{{url('affiliator/clients/leads',$lead->client_id)}}" class="client-title"> --}}
                                            {{ $lead->client->leads_count()->count() }} Lead
                                        {{-- </a> --}}


                                        @else
                                        Nothing
                                        @endif

                                    </div>
                                </td>

                                <td>
                                    {{ $lead->leadAgent->name }} @if($lead->share_id > 0) | {{ $lead->sharePerson->name}} @endif    </br>
                                    <span style="color: grey;">{{ $lead->leadAgent->designation_name }} </span>
                                </td>


                                <td>
                                    {{-- <a style="color: #fcb800;" href="{{url('affiliator/lead/edit',$lead->id)}}"> --}}
                                        {{ $lead->id }}
                                    {{-- </a> --}}
                                    </br>
                                    {{date('M d, Y', strtotime($lead->created_at))}}

                                </td>

                                <td>
                                    @if( $lead->client->leadSource->subtype == 'user' )
                                        {{ $lead->client->leadSource->type }}
                                    @else
                                    {{ucfirst($lead->client->leadSource->subtype)}}
                                    @if($lead->client->afflilate_id > 0)
                                    <?php
                                    $affiliator = $lead->client->leadAffiliator;
                                    ?>
                                    <br>
                                    <div class="client-title tooltip">
                                        <div class="tooltiptext client-info">
                                            <div class="tooltip-head">Affiliator Information:</div>
                                            <div class="inner-side"><label>Name: </label><span>{{ ucfirst($affiliator->name) }}</span></div>
                                            <div class="inner-side"><label>Number: </label><span>{{ str_replace(' ','',$affiliator->phone) }}</span></div>
                                            <div class="inner-side"><label>Email: </label><span>{{ $affiliator->email }}</span></div>
                                        </div>
                                        <a href="#" class="client-title">
                                            <div class="title-flag">
                                                <strong>{{ ucfirst($affiliator->name )}}</strong>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    </div>
                                    @endif

                                    @endif
                                </td>


                                @if(!empty($lead->taskStatus))
                                <td>
                                    <?php
                                    $note = $lead->taskStatus->note;
                                    ?>
                                    {{$lead->taskStatus->subtype}}</br>
                                    @if($lead->taskStatus->subtype == 'Token Payment')
                                    ({{$lead->token_amount}})</br>
                                    @endif
                                    @if($lead->taskStatus->subtype == 'Down Payment')
                                    {{$lead->down_payment}}</br>
                                    @endif
                                    @if($note != '')
                                    @if(strlen($note) > 40)
                                    {{substr($note, 0, 40).'...'}}
                                    @else
                                    {{$note}}
                                    @endif
                                    </br>
                                    @endif
                                    <span style="color: grey;">{{date('M d, Y', strtotime($lead->taskStatus->created_at))}}</span>
                                </td>
                                @else
                                <td>No Action</td>
                                @endif
                                <td>
                                    @if(!empty($lead->project_id > 0))
                                    {{$lead->project->name}}
                                    @endif
                                    @if(!empty($lead->category))
                                        </br>{{$lead->category->name}}
                                    @endif

                                    @if(!empty($lead->item))
                                    {{$lead->item->name}}</br>
                                    Buy Primar
                                    @else
                                    </br>Unit Not Selected
                                    @endif
                                </td>


                                {{-- <td style="width: 9%;">
                                    <?php
                                    if (!empty($lead->item)) {
                                        $item_id = $lead->item->id;
                                    } else {
                                        $item_id = 0;
                                    }
                                    ?>

                                    <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" data-client="<?php echo $lead->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#tasklog" id="getTask">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </a>

                                    <button type="button" class="btn btn-info waves-effect create_task tooltip add_icon" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" data-client="<?php echo $lead->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#myModalthree" >
                                        <i class="fa fa-plus"></i>
                                        <span class="tooltiptext">Add Task</span>
                                    </button>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="ps-section__footer">
                <p>Showing {{($leads->currentpage()-1)*$leads->perpage()+1}} to {{$leads->currentpage()*$leads->perpage()}}
                    of  {{$leads->total()}} entries
                </p>
                {{ $leads->appends(request()->except('page'))->links() }}
            </div>

        @else
            No Record Found!
        @endif

    </section>
</div>


    </section>
</div>

@endsection






