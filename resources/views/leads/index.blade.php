@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Products')])

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

        @if (count($errors) > 0)
            <div class = "alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <header class="header--dashboard">

            <div class="header__left">
                <h3>Leads ({{$leads->total()}})</h3>
            </div>
            <div class="header__center">

            </div>

            @if(Auth::user()->can('lead.create'))
                {{-- @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 ) --}}
                    <div class="header__right">
                        <a class="ps-btn success" href="{{ url('lead/create') }}"><i class="icon icon-plus mr-2"></i>New Lead</a>
                    </div>
                {{-- @endif --}}
            @endif
        </header>

        <section class="ps-items-listing">
            @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 )
                <div class="ps-section__header">
                    <div class="ps-section__filter">
                        <form class="ps-form--filter" action="{{url('leads-search')}}" method="get">
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

                                @if(Auth::user()->role != 11 && Auth::user()->role != 12 )

                                    @if(count($users) > 0)
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Allocation </label>
                                                <div class="bootstrap-select fm-cmp-mg">
                                                    <select class="form-control user_id" name="user_id" id="user_id">
                                                        @if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                                                            <option value="0">All</option>
                                                        @endif
                                                        <?php
                                                            $user_id = 0;
                                                            if(app('request')->input('user_id') != ''){
                                                                $user_id = app('request')->input('user_id');
                                                            }elseif($id != '')
                                                            {
                                                                $user_id = $id;
                                                            }else{
                                                                $user_id = Auth::user()->id;
                                                            }
                                                        ?>
                                                        @foreach($allocation as $user)
                                                            <option <?php if($user_id == $user->id){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <?php $user_id = Auth::user()->id; ?>
                                    @endif
                                @endif

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

                                @if(Auth::user()->role != 11 && Auth::user()->role != 12 )
                                    @if(count($sources) > 0)
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
                                    @endif
                                @endif


                                @if(Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14)
                                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
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
                                                <select class="ps-select  {{ $errors->has('office_id') ? ' is-invalid' : '' }}" id="input_office_id" style="width: 100%"  title="office Name" name="office_id" required="true" aria-required="true">
                                                    {{-- <option value="0">--All--</option> --}}
                                                        <option value="0" selected disabled >Select Office</option>
                                                        @foreach($offices as $office)
                                                            <option <?php if($office_id == $office->id){ echo 'selected'; } ?> value="{{$office->id}}" @if(old('office_id')==$office->id ) selected  @endif  >{{$office->name}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <?php $office_id = Auth::user()->office_id; ?>		{{-- ================ --}}
                                    <input name="office_id" value="{{Auth::user()->office_id}}" type="hidden">
                                @endif


                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Records Show </label>
                                        <div class="bootstrap-select fm-cmp-mg">

                                            <select class="ps-select form-control" style="width: 100%" name="records">
                                                <option value="20"   <?php if (app('request')->input('records') == '20')  {echo 'selected';} ?> > 20   </option>
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

                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Records Sort By </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <select class="ps-select form-control" style="width: 100%" name="status">
                                                <option value="id" <?php if (app('request')->input('status') == 'id') {echo 'selected';} ?> >ID</option>
                                                <option value="user_id" <?php if (app('request')->input('status') == 'user_id') {echo 'selected';} ?> >Allocation</option>
                                                <option value="client_id" <?php if (app('request')->input('status') == 'client_id') {echo 'selected';} ?> >Client ID</option>
                                                <option value="updated_at" <?php if (app('request')->input('status') == 'updated_at') {echo 'selected';} ?> >Last Updated</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Records Order </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <select class="ps-select form-control" style="width: 100%" name="sort">
                                                <option value="ASC" <?php if (app('request')->input('sort') == 'ASC') {echo 'selected';} ?>   >
                                                    Assending
                                                </option>
                                                <option value="DESC"  <?php if (app('request')->input('sort') == 'DESC') {echo 'selected';} ?> >
                                                    Decending
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Status Type </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <select class="ps-select form-control" style="width: 100%" name="status_type">
                                                <option value="0" <?php if (app('request')->input('status_type') == '0') {echo 'selected';} ?> >--All--</option>

                                                <option value="1" <?php if (app('request')->input('status_type') == '1') {echo 'selected';} ?>   >
                                                    Attended
                                                </option>
                                                <option value="2"  <?php if (app('request')->input('status_type') == '2') {echo 'selected';} ?> >
                                                    Not Attended
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

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
            @endif

            @if(!empty($leads))
                <?php $todo = ''; ?>
            @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 )
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec">

                        <?php

                            $today=Carbon\Carbon::today();
                            $yesterday=Carbon\Carbon::yesterday();
                            // This Week (Current Week)
                            $this_week_start=Carbon\Carbon::now()->startOfWeek();
                            $this_week_end=Carbon\Carbon::now()->endOfWeek();
                            // Last Week (Previous/Past Week)
                            $last_week_start=Carbon\Carbon::now()->subWeek()->startOfWeek();
                            $last_week_end=Carbon\Carbon::now()->subWeek()->endOfWeek();
                            // This Month (Current Month)
                            $this_month_start=Carbon\Carbon::now()->startOfMonth();
                            $this_month_end=Carbon\Carbon::now()->lastOfMonth();
                            // Last Month (Previous/Past Month)
                            $last_month_start=Carbon\Carbon::now()->subMonth()->startOfMonth();
                            $last_month_end=Carbon\Carbon::now()->subMonth()->lastOfMonth();

                            // echo $this_month_end; echo "<br>";
                        ?>

                        <ul class="lead_status">
                            <li class="<?php
                            if ($todo == 'today' || $todo == '') {
                                echo 'active';
                            }
                            ?>">
                                {{-- <a href="{{ url('lead/today') }}">Today</a> --}}
                                <a href="{{url('leads-search?lead_id='.app('request')->input('lead_id').'&startDate='.date("Y-m-d", strtotime($today)).'&endDate='.date("Y-m-d", strtotime($today)).'&user_id='.$user_id.'&phone='.app('request')->input('phone').'&project_id='.app('request')->input('project_id').'&source_id='.app('request')->input('source_id').'&office_id='.$office_id.'&records='.app('request')->input('records').'&status='.app('request')->input('status').'&sort='.app('request')->input('sort').'&submit=Filter')}}">Today </a>
                            </li>
                            <li class="<?php
                                if ($todo == 'yesterday') {
                                    echo 'active';
                                }
                                ?>">
                                {{-- <a href="{{url('lead/yesterday')}}">Yesterday</a> --}}
                                <a href="{{url('leads-search?lead_id='.app('request')->input('lead_id').'&startDate='.date("Y-m-d", strtotime($yesterday)).'&endDate='.date("Y-m-d", strtotime($yesterday)).'&user_id='.$user_id.'&phone='.app('request')->input('phone').'&project_id='.app('request')->input('project_id').'&source_id='.app('request')->input('source_id').'&office_id='.$office_id.'&records='.app('request')->input('records').'&status='.app('request')->input('status').'&sort='.app('request')->input('sort').'&submit=Filter')}}">Yesterday </a>
                            </li>
                            <li class="<?php
                                if ($todo == 'thisweek') {
                                    echo 'active';
                                }
                                ?>">
                                {{-- <a href="{{url('lead/thisweek')}}">This Week</a> --}}
                                <a href="{{url('leads-search?lead_id='.app('request')->input('lead_id').'&startDate='.date("Y-m-d", strtotime($this_week_start)).'&endDate='.date("Y-m-d", strtotime($this_week_end)).'&user_id='.$user_id.'&phone='.app('request')->input('phone').'&project_id='.app('request')->input('project_id').'&source_id='.app('request')->input('source_id').'&office_id='.$office_id.'&records='.app('request')->input('records').'&status='.app('request')->input('status').'&sort='.app('request')->input('sort').'&submit=Filter')}}">This Week </a>
                            </li>
                            <li class="<?php
                                if ($todo == 'lastweek') {
                                    echo 'active';
                                }
                                ?>">
                                {{-- <a href="{{url('lead/lastweek')}}">Last Week</a> --}}
                                <a href="{{url('leads-search?lead_id='.app('request')->input('lead_id').'&startDate='.date("Y-m-d", strtotime($last_week_start)).'&endDate='.date("Y-m-d", strtotime($last_week_end)).'&user_id='.$user_id.'&phone='.app('request')->input('phone').'&project_id='.app('request')->input('project_id').'&source_id='.app('request')->input('source_id').'&office_id='.$office_id.'&records='.app('request')->input('records').'&status='.app('request')->input('status').'&sort='.app('request')->input('sort').'&submit=Filter')}}">Last Week </a>
                            </li>
                            <li class="<?php
                                if ($todo == 'thismonth') {
                                    echo 'active';
                                }
                                ?>">
                                {{-- <a href="{{url('lead/thismonth')}}">This Month</a> --}}
                                <a href="{{url('leads-search?lead_id='.app('request')->input('lead_id').'&startDate='.date("Y-m-d", strtotime($this_month_start)).'&endDate='.date("Y-m-d", strtotime($this_month_end)).'&user_id='.$user_id.'&phone='.app('request')->input('phone').'&project_id='.app('request')->input('project_id').'&source_id='.app('request')->input('source_id').'&office_id='.$office_id.'&records='.app('request')->input('records').'&status='.app('request')->input('status').'&sort='.app('request')->input('sort').'&submit=Filter')}}">This Month </a>
                            </li>
                            <li class="<?php
                                if ($todo == 'lastmonth') {
                                    echo 'active';
                                }
                                ?>">
                                {{-- <a href="{{url('lead/lastmonth')}}">Last Month</a> --}}
                                <a href="{{url('leads-search?lead_id='.app('request')->input('lead_id').'&startDate='.date("Y-m-d", strtotime($last_month_start)).'&endDate='.date("Y-m-d", strtotime($last_month_end)).'&user_id='.$user_id.'&phone='.app('request')->input('phone').'&project_id='.app('request')->input('project_id').'&source_id='.app('request')->input('source_id').'&office_id='.$office_id.'&records='.app('request')->input('records').'&status='.app('request')->input('status').'&sort='.app('request')->input('sort').'&submit=Filter')}}">Last Month </a>
                            </li>
                        </ul>
                </div>
            @endif

            <div class="ps-section__content">
                <div class="table-responsive">

                    @if(Auth::user()->can('lead.share') || Auth::user()->can('lead.transfer'))
                        <div class="row" style="margin-bottom: 10px; text-align:center;">
                            <div class="col-md-4">
                                <span >Total Leads : {{count($leads)}}</span>
                            </div>
                            <div class="col-md-3">
                                <div class="count-checkboxes-wrapper">
                                <span id="count-checked-checkboxes">0</span> Assign
                                    </div>
                            </div>

                            <div class="col-md-3">
                                <div class="count-uncheckboxes-wrapper">
                                    <span id="count-unchecked-checkboxes">{{count($leads)}}</span> Un-Assign
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="count-uncheckboxes-wrapper">

                                    @if(Auth::user()->can('lead.share'))
                                        <a style="margin-right: 10px;" rel="tooltip" href="#" data-original-title="" title="Share-Lead" data-toggle="modal" data-target="#multileadshare" id="multi_shareLead">
                                            <i class="fa fa-share-alt" aria-hidden="true"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->can('lead.transfer'))
                                        <a rel="tooltip" href="#" data-original-title="" title="Transfer-Lead" data-toggle="modal" data-target="#multileadtransfer" id="multi_transferLead">
                                            <i class="fa fa-share" aria-hidden="true"></i>
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endif

                    <style>
                            #count-checked-checkboxes{color:red;}
                            #count-unchecked-checkboxes{color:red;}
                            tr.marked-row{background: #777;}
                    </style>

                    {{-- =========================Multiple Lead Share Model===================================== --}}
                    <div class="modal fade" id="multileadshare" role="dialog">
                        <div class="modal-dialog modal-default">
                            <form method="post" action="{{ url('multi/lead/share/store') }}" class="form-horizontal" id="share_lead_from" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <figure class="ps-block--form-box">
                                            <figcaption>Multipe Lead Share</figcaption>
                                            <div class="ps-block__content">
                                                <div class="form-group">
                                                    <label>Shared With<sup>*</sup></label>
                                                    <select class="form-control" name="to_user" required="">
                                                        <option value="">--Select Person--</option>
                                                        @foreach($users as $user)
                                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" name="lead_ids" id="multi_slead_ids" value="">
                                                <input type="hidden" name="from_user" id="multi_from_users" value="">
                                                <input type="hidden" name="shared_by" id="multi_shared_by" value="{{Auth::user()->id}}">
                                                <div class="form-group">
                                                    <label>Comments<sup>*</sup></label>
                                                    <textarea class="form-control" rows="2" name="note" required="">{{ old('note') }}</textarea>
                                                </div>
                                            </div>
                                        </figure>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success button" id="share_task" >Share Lead</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- ======================Multiple Lead Trnasfer Model================================== --}}

                    <div class="modal fade" id="multileadtransfer" role="dialog">
                        <div class="modal-dialog modal-default">
                            <form method="post" action="{{ url('multi/lead/transfer/store') }}" class="form-horizontal" id="transfer_lead_from" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <figure class="ps-block--form-box">
                                            <figcaption>Multiple Lead Transfer </figcaption>
                                            <div class="ps-block__content">
                                                <div class="form-group">
                                                    <label>Transfer To<sup>*</sup></label>
                                                    <select class="form-control" name="to_user" required="">
                                                        <option value="">--Select Person--</option>
                                                        @foreach($users as $user)
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" name="lead_ids" id="multi_tlead_id" value="">
                                                <input type="hidden" name="from_users" id="multi_tfrom_user" value="">
                                                <input type="hidden" name="transfer_by" id="multi_transfer_by" value="{{Auth::user()->id}}">
                                                <input type="hidden" name="client_ids" id="multi_client_id_transfer" value="">

                                                <div class="form-group">
                                                    <label>Comments<sup>*</sup></label>
                                                    <textarea class="form-control" rows="2" name="note" required="">{{ old('note') }}</textarea>
                                                </div>
                                            </div>
                                        </figure>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success button" id="transfer_task" >Transfer</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table ps-table count_form_checkboxes" id="tbl" >
                        <thead>
                            <tr>
                                @if(Auth::user()->can('lead.share') || Auth::user()->can('lead.transfer'))
                                    <th><input type="checkbox" id="check_all_records" name="multi_records_checkbox"></th>
                                @endif
                                <th>Client</th>
                                {{-- <th>Allocated To</th> --}}
                                <th> Designation </th>
                                <th>Lead-ID</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Interest</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)

                                <tr>
                                    @if(Auth::user()->can('lead.share') || Auth::user()->can('lead.transfer'))
                                        <td>
                                            <input id="a_chkDelete" {{-- @if ($lead->is_delete != '0') @disabled(true) @endif--}} class="chkDelete" type="checkbox" name="leadids" value="{{$lead->id}}" data-client="<?php echo $lead->client_id; ?>" data-bind="<?php echo $lead->id; ?>" from-user='<?php echo $lead->user_id; ?>'>
                                        </td>
                                    @endif

                                    <td>
                                        @if(!empty($lead->client))
                                            <div class="client-title tooltip">
                                                <div class="tooltiptext client-info">
                                                    <div class="tooltip-head">Client Information:</div>
                                                    <div class="inner-side"><label>Client Name: </label><span>{{ $lead->client->name }}</span></div>
                                                        {{-- <div class="inner-side"><label>Client Number: </label><span>{{ str_replace(' ','',$lead->client->phone) }}</span></div>
                                                            @if ($lead->client->telephone)
                                                                <div class="inner-side"><label>Client TelePhone 1: </label><span>{{ str_replace(' ','',$lead->client->telephone) }}</span></div>
                                                            @endif
                                                            @if ($lead->client->telephone1)
                                                                <div class="inner-side"><label>Client TelePhone 2: </label><span>{{ str_replace(' ','',$lead->client->telephone1) }}</span></div>
                                                            @endif

                                                            @if ($lead->client->clientNumbers)
                                                                @foreach ($lead->client->clientNumbers as $key => $phone)
                                                                    @if($lead->client->phone != $phone->number)
                                                                        <div class="inner-side"><label>Client Number {{ $key+1 }}: </label><span>{{ str_replace(' ','',$phone->number) }}</span></div>
                                                                    @endif
                                                                @endforeach
                                                            @endif --}}

                                                        {{-- =======Showing Phone Numbers --}}
                                                            @php
                                                                $counter = 1;
                                                            @endphp

                                                            @if ($lead->client->phone)
                                                                <div class="inner-side">
                                                                    <label>Client Number {{ $counter }}: </label>
                                                                    <span>{{ str_replace(' ', '', $lead->client->phone) }}</span>
                                                                </div>
                                                                @php
                                                                    $counter++;
                                                                @endphp
                                                            @endif

                                                            @if ($lead->client->clientNumbers)
                                                                @foreach ($lead->client->clientNumbers as $phone)
                                                                    @if($lead->client->phone != $phone->number)
                                                                        <div class="inner-side">
                                                                            <label>Client Number {{ $counter }}: </label>
                                                                            <span>{{ str_replace(' ', '', $phone->number) }}</span>
                                                                        </div>
                                                                        @php
                                                                            $counter++;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            @endif

                                                        {{-- =======Showing Phone Numbers --}}



                                                        <div class="inner-side"><label>Client Email: </label><span>{{ $lead->client->email }}</span></div>
                                                        <div class="inner-side"><label>City: </label><span>{{ $lead->client->address }}</span></div>

                                                    </div>

                                                    @if(Auth::user()->can('client.edit'))
                                                        <a href="{{url('client/edit',$lead->client_id)}}" class="client-title">
                                                            <div class="title-flag">
                                                                <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">
                                                                <strong>{{ $lead->client->name }}</strong>
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <div class="title-flag">
                                                            <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">
                                                            <strong>{{ $lead->client->name }}</strong>
                                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        </div>
                                                    @endif

                                                    <i class="fa fa-th" aria-hidden="true"></i>


                                                    <a href="{{url('clients/leads',$lead->client_id)}}" class="client-title">
                                                        {{ $lead->client->leads_count()->count() }} Lead
                                                    </a>

                                                </div>
                                            </div>
                                        @else
                                            Nothing
                                        @endif
                                    </td>

                                    <td>
                                        {{ optional($lead->leadAgent)->name ?? 'No User Assigned' }}
                                        @if($lead->share_id > 0)
                                            |   {{ optional($lead->sharePerson)->name ?? 'No Shared User' }}
                                        @endif </br>

                                        {{-- <span style="color: grey;">{{ $lead->leadAgent->designation->name }} {{ $lead->leadAgent->department->name }}</span><br> --}}
                                        <span style="color: grey;"> {{ optional($lead->leadAgent)->designation_name ?? 'No Designation' }}</span>

                                        {{--  Lead Share With [User Name] --}}

                                        {{-- @if ($lead->leadshare)
                                                <span style="color: grey;">{{ $lead->leadshare->name }} </span><br>
                                            @endif --}}

                                            {{-- Lead Share With How much users [User Count] --}}

                                            {{-- @if ($lead->shared_leads_count()->count() > 0 )
                                                <span style="color: grey;">{{ $lead->shared_leads_count()->count() }}</span>
                                            @endif --}}
                                    </td>

                                    <td>
                                        @if(Auth::user()->can('lead.edit'))
                                            <a style="color: #fcb800;" href="{{url('lead/edit',$lead->id)}}">
                                                {{ $lead->id }}
                                            </a>
                                        @else
                                            <strong>{{ $lead->id }} </strong>
                                        @endif
                                        <br>
                                        {{date('M d, Y', strtotime($lead->created_at))}}
                                    </td>

                                    <td>
                                        @if(optional(optional($lead->client)->leadSource)->subtype == 'user')
                                        {{ optional(optional($lead->client)->leadSource)->type ?? 'No Source' }}
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
                                            {{$lead->taskStatus->subtype}}<br>
                                            @if($lead->taskStatus->subtype == 'Token Payment')
                                            ({{$lead->token_amount}})<br>
                                            @endif
                                            @if($lead->taskStatus->subtype == 'Down Payment')
                                            {{$lead->down_payment}}<br>
                                            @endif
                                            @if($note != '')
                                            @if(strlen($note) > 40)
                                            {{substr($note, 0, 40).'...'}}
                                            @else
                                            {{$note}}
                                            @endif
                                            <br>
                                            @endif
                                            <span style="color: grey;">{{date('M d, Y', strtotime($lead->taskStatus->created_at))}}</span>
                                        </td>
                                        @else
                                        <td>No Action</td>
                                    @endif


                                    <td>
                                        @if(!empty($lead->project_id > 0))
                                        {{ optional($lead->project)->name ?? 'No Project' }}
                                        @endif
                                        @if(!empty($lead->category))
                                            <br>{{ optional($lead->category)->name ?? 'No Category' }}
                                        @endif

                                        @if(!empty($lead->item_unit))
                                        {{ optional($lead->item_unit)->name ?? 'Unit Not Selected' }}
                                        Buy Primar
                                        @else
                                        <br>Unit Not Selected
                                        @endif
                                    </td>

                                    <td style="width: 9%;">
                                        @php
                                            if (!empty($lead->item)) {
                                                $item_id = $lead->item->id;
                                            } else {
                                                $item_id = 0;
                                            }
                                        @endphp

                                        @if ($lead->is_delete=='0')

                                            <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" data-client="<?php echo $lead->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#tasklog" id="getTask">
                                                <i class="fa fa-calendar mr-5" aria-hidden="true"></i>
                                            </a>

                                            <button type="button" class="btn btn-info waves-effect create_task tooltip add_icon" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" data-client="<?php echo $lead->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#myModalthree" >
                                                <i class="fa fa-plus mr-2"></i>
                                                <span class="tooltiptext">Add Task</span>
                                            </button>


                                            @if(Auth::user()->can('lead.share'))
                                                @if($lead->share_id == 0)
                                                    <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" from-user='<?php echo $lead->user_id; ?>' data-target="#leadshare" id="shareLead">
                                                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            @endif

                                            <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-lead_id="<?php echo $lead->id; ?>" data-target="#leadFeedBack" id="leadFeedBackBtn">
                                                <i class="fa fa-commenting mr-5" aria-hidden="true"></i>
                                            </a>

                                            @if(Auth::user()->can('lead.transfer'))
                                                <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-client="<?php echo $lead->client_id; ?>" data-bind="<?php echo $lead->id; ?>" from-user='<?php echo $lead->user_id; ?>' data-target="#leadtransfer" id="transferLead">
                                                    <i class="fa fa-share mr-5" aria-hidden="true"></i>
                                                </a>
                                            @endif

                                            @if(Auth::user()->can('lead.trash'))
                                                <form action="{{ url('lead/delete', $lead->id) }}" method="post" style="display:inline-flex">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" class="fa fa-trash mr-5" data-original-title="" title="Delete Data" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''"></button>
                                                </form>
                                            @endif

                                            {{-- @if(Auth::user()->role == 1)
                                                <a rel="tooltip" href="{{ url('lead/edit', $lead->id) }}" data-original-title="" title="">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                            @endif --}}

                                        @else
                                            <!-- <b>Lead In Trash</b> -->
                                            <div class="text-center">
                                                <i class="fa-solid fa-trash-arrow-up fa-lg" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                            @empty

                                @if ($find_result != 0)
                                    <td colspan="8">{!! $find_result !!}</td>
                                @else
                                    <td colspan="8">No Record Found!</td>
                                @endif

                            @endforelse
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

            {{-- ======Lead Feedback Model========= --}}

                <div class="modal fade" id="leadFeedBack" role="dialog">
                    <div class="modal-dialog modal-default">
                        <form method="post" action="{{ url('lead/feedback/store') }}" class="form-horizontal" id="feedback_lead_from" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">

                                    <figure class="ps-block--form-box">
                                        <figcaption>FeedBack</figcaption>
                                        <div class="ps-block__content">
                                            <div class="form-group">
                                                <label>Status<sup>*</sup></label>
                                                <select class="form-control" name="status" required id="feedback_status">
                                                    @foreach(\App\Enums\LeadFeedBackStatus::cases() as $status)
                                                        <option value="{{ $status->value }}"
                                                            {{ old('status') == $status->value ? 'selected' : '' }}>
                                                            {{ $status->label() }}
                                                        </option>
                                                        {{-- for edit use  {{ old('status', $leadFeedback->status?->value) == $status->value ? 'selected' : '' }}> --}}
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="form-group" id="amount_div" style="display: none">
                                                <label>Amount<sup>*</sup></label>
                                                <input type="number" class="form-control" name="amount" id="feedback_amount" min="0" step="0.01"
                                                    value="{{ old('amount') }}">
                                            </div>

                                            <input type="hidden" name="lead_id" id="feedback_lead_id" value="">

                                            <div class="form-group">
                                                <label>Remarks<sup>*</sup></label>
                                                <textarea class="form-control" rows="2" name="remarks" required>{{ old('remarks') }}</textarea>
                                            </div>


                                        </div>
                                    </figure>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success button submit_feedBack">Submit FeedBack</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


            {{-- =========Lead Share Model========= --}}


                <div class="modal fade" id="leadshare" role="dialog">
                    <div class="modal-dialog modal-default">
                        <form method="post" action="{{ url('lead/share/store') }}" class="form-horizontal" id="share_lead_from" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">

                                    <figure class="ps-block--form-box">
                                        <figcaption>Lead Share</figcaption>
                                        <div class="ps-block__content">
                                            <div class="form-group">
                                                <label>Shared With<sup>*</sup></label>
                                                <select class="form-control" name="to_user" required="">
                                                    <option value="">--Select Person--</option>
                                                    @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <input type="hidden" name="lead_id" id="slead_id" value="">
                                            <input type="hidden" name="from_user" id="from_user" value="">
                                            <input type="hidden" name="shared_by" id="shared_by" value="{{Auth::user()->id}}">
                                            <div class="form-group">
                                                <label>Comments<sup>*</sup></label>
                                                <textarea class="form-control" rows="2" name="note" required="">{{ old('note') }}</textarea>
                                            </div>


                                        </div>
                                    </figure>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success button" id="share_task" >Share Lead</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


            {{-- =====Lead Trnasfer Model======== --}}

                <div class="modal fade" id="leadtransfer" role="dialog">
                    <div class="modal-dialog modal-default">

                        <form method="post" action="{{ url('lead/transfer/store') }}" class="form-horizontal" id="transfer_lead_from" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">

                                    <figure class="ps-block--form-box">
                                        <figcaption>Lead Transfer </figcaption>
                                        <div class="ps-block__content">
                                            <div class="form-group">
                                                <label>Transfer With<sup>*</sup></label>
                                                <select class="form-control" name="to_user" required="">
                                                    <option value="">--Select Person--</option>
                                                    @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <input type="hidden" name="lead_id" id="tlead_id" value="">
                                            <input type="hidden" name="from_user" id="tfrom_user" value="">
                                            <input type="hidden" name="transfer_by" id="transfer_by" value="{{Auth::user()->id}}">
                                            <input type="hidden" name="client_id" id="client_id_transfer" value="">

                                            <div class="form-group">
                                                <label>Comments<sup>*</sup></label>
                                                <textarea class="form-control" rows="2" name="note" required="">{{ old('note') }}</textarea>
                                            </div>

                                        </div>
                                    </figure>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success button" id="transfer_task" >Transfer</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            {{-- =================== --}}


            <div class="modal fade" id="tasklog" role="dialog">
                <div class="modal-dialog modal-large">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">X</button>
                        </div>
                        <div class="modal-body">
                            <table class="table ps-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Lead</th>
                                        <th>Interest</th>
                                        <th>Type</th>
                                        <th>Task</th>
                                        <th>Comments</th>
                                    </tr>
                                </thead>
                                <tbody id="taskrow">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="myModalthree" role="dialog">
                <div class="modal-dialog modal-default">
                    <form method="post" action="{{ url('task/store') }}" class="form-horizontal" id="taskform" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('post')
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">

                                <figure class="ps-block--form-box">
                                    <figcaption>Add Task</figcaption>
                                    <div class="ps-block__content">
                                        <div class="form-group" id="tasktype">
                                            <label>Select Type<sup>*</sup></label>
                                            <select name="type" id="type" class="form-control" required="">
                                                <option value="Calls">Calls</option>
                                                <option value="Meetings">Meetings</option>
                                                <option value="Sales">Sales</option>
                                                <option value="Emails">Emails</option>
                                                <option value="Acquisition">Acquisition</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="tasksubtype">
                                            <label>Sub Type<sup>*</sup></label>
                                            <select name="subtype" id="subtype" class="form-control select-picker" data-size="8" required="">
                                                <option value="Do Nothing">Do Nothing</option>
                                                <option value="SMS">SMS</option>
                                                <option value="Contacted Client">Contacted Client</option>
                                                <option value="Call Attempt">Call Attempt</option>
                                                <option value="Followed Up">Followed Up</option>
                                                <option value="Whatsapp Call">Whatsapp Call</option>
                                                <option value="Whatsapp (Message)">Whatsapp (Message)</option>
                                                <option value="Meeting Confirmed">Meeting Confirmed</option>
                                                <option value="Meeting Cancelled">Meeting Cancelled</option>
                                                <option value="Meeting Postponed">Meeting Postponed</option>
                                            </select>

                                            <span id="name-error" class="error text-danger subtype-error" for="input-name"></span>

                                        </div>
                                        <div class="subtypeopton">

                                        </div>

                                        <div class="projects-units">

                                        </div>


                                        <div class="form-group">
                                            <label>Completed Date
                                            </label>
                                            <input class="form-control completion_date" type="text" name="completion_date" value="{{ old('duedate', date('m/d/Y')) }}"/>
                                        </div>

                                        <div class="projectoption">

                                        </div>

                                        <div class="form-group task_attachment" style="display: none;">
                                            <label>Attachment<sup>*</sup></label>
                                            <div class="form-group--nest">
                                                <input class="form-control mb-1" name="file" type="file" placeholder="">
                                                <button class="ps-btn ps-btn--sm">Choose</button>
                                            </div>
                                        </div>

                                        <input type="hidden" name="lead_id" id="lead_id" value="">
                                        <input type="hidden" name="client_id" id="client_id" value="">
                                        <input type="hidden" name="item_id" id="item_id" value="">
                                        <input type="hidden" name="added_by" id="added_by" value="{{Auth::user()->id}}">
                                        <div class="form-group">
                                            <label>Note<sup>*</sup></label>
                                            <textarea class="form-control" rows="2" name="note" required="">{{ old('note') }}</textarea>
                                        </div>
                                        <h2>Next Task</h2>
                                        <hr>
                                        <div class="form-group" id="ntasktype">
                                            <label>Select Type<sup>*</sup></label>
                                            <select name="ntype" id="ntype" class="form-control select" required="" data-size="8">
                                                <option value=""> -- Next Action --</option>
                                                <option value="Do Nothing">Do Nothing</option>
                                                <option value="Contact Client" > Contact Client</option>
                                                <option value="Follow Up" > Follow Up</option>
                                                <option value="Receive Token Payment" > Receive Token Payment</option>
                                                <option value="Meet Client" > Meet Client</option>
                                                <option value="Arrange Meeting" > Arrange Meeting</option>
                                                <option value="Receive Partial Token Payment" > Receive Partial Token Payment</option>
                                                <option value="Receive Partial Down Payment" > Receive Partial Down Payment</option>
                                                <option value="Receive Complete Down Payment" > Receive Complete Down Payment</option>
                                                <option value="Closed (Won)" > Closed (Won)</option>
                                                <option value="Sign Sale Agreement" > Sign Sale Agreement</option>                                            </select>
                                        </div>
                                        <?php
                                            // date_default_timezone_set('Asia/Karachi');
                                            $mytime = Carbon\Carbon::now();
                                            $currentTime= $mytime->toTimeString();
                                        ?>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Deadline Date</label>
                                                    <input class="form-control deadline" type="text" placeholder="Date" name="deadline" required  value="{{old('deadline')}}" {{--value="{{ old('deadline', date('m/d/Y')) }}" --}} >
                                                </div>
                                                {{-- <div class="col-md-6">
                                                    <label>Deadline Time</label>
                                                    <input class="form-control" type="time" placeholder="Time" name="time"  value="{{$currentTime}}" >
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </figure>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success button" id="submit_task" disabled="">Save changes</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            @else
                No Record Found!
            @endif
        </section>

    </div>


@endsection






