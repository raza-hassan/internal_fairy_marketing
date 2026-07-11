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

    <header class="header--dashboard">

        <div class="header__left">
            <h3>Leads In Trash ({{$leads->total()}})</h3>
        </div>

    </header>

    <section class="ps-items-listing">



        @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 )
            <div class="ps-section__header">
                <div class="ps-section__filter">

                    <form class="ps-form--filter" action="{{url('tarsh/leads-search')}}" method="get">

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

                            @if(Auth::user()->role != 11 && Auth::user()->role != 12)

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
                                                    <option value="0" selected disabled >Select Office</option>
                                                    @foreach($offices as $office)
                                                        <option <?php if($office_id == $office->id){ echo 'selected'; } ?> value="{{$office->id}}" @if(old('office_id')==$office->id ) selected  @endif  >{{$office->name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <?php $office_id = Auth::user()->office_id; ?>
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


        {{-- ===============Change occure 24-01-2022====================== --}}
            @if(Auth::user()->can('trashed.lead.share') || Auth::user()->can('trashed.lead.transfer'))
                <div class="row" style="text-align:center;">
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
                            @if(Auth::user()->can('trashed.lead.share'))
                                <a style="margin-right: 10px;" rel="tooltip" href="#" data-original-title="" title="Share-Lead" data-toggle="modal" data-target="#multileadshare" id="multi_shareLead">
                                    <i class="fa fa-share-alt" aria-hidden="true"></i>
                                </a>
                            @endif
                            @if(Auth::user()->can('trashed.lead.transfer'))
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
                                            <label>Transfer With<sup>*</sup></label>
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


        {{-- ===============Change occure 24-01-2022====================== --}}



        <div class="ps-section__content" style="margin-top: 10px !important;">
            <div class="table-responsive">

                <table class="table ps-table count_form_checkboxes" id="tbl" >
                    <thead>
                        <tr>
                            @if(Auth::user()->can('trashed.lead.share') || Auth::user()->can('trashed.lead.transfer') )
                                <th><input type="checkbox" id="check_all_records" name="multi_records_checkbox"></th>
                            @endif
                            <th>Client</th>
                            <th> Designation </th>
                            <th>Lead-ID</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Interest</th>
                            @if(Auth::user()->can('trashed.lead.share') || Auth::user()->can('trashed.lead.transfer') || Auth::user()->can('trashed.lead.restore') || Auth::user()->can('trashed.lead.delete'))
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $lead)
                        <tr>

                            @if(Auth::user()->can('trashed.lead.share') || Auth::user()->can('trashed.lead.transfer') )
                                <td>
                                    <input id="a_chkDelete" class="chkDelete" type="checkbox" name="leadids" value="{{$lead->id}}" data-client="<?php echo $lead->client_id; ?>" data-bind="<?php echo $lead->id; ?>" from-user='<?php echo $lead->user_id; ?>'>
                                </td>
                            @endif

                            <td>

                                @if(!empty($lead->client))
                                <div class="client-title tooltip">

                                    <div class="tooltiptext client-info">
                                        <div class="tooltip-head">Client Information:</div>
                                        <div class="inner-side"><label>Client Name: </label><span>{{ $lead->client->name }}</span></div>
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
                                    <a href="{{url('client/edit',$lead->client_id)}}" class="client-title">
                                        <div class="title-flag">
                                            <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">
                                            <strong>{{ $lead->client->name }}</strong>
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        </div>
                                    </a>
                                    <i class="fa fa-th" aria-hidden="true"></i>

                                    <a href="{{url('clients/leads',$lead->client_id)}}" class="client-title">
                                        {{ $lead->client->leads_count()->count() }} Lead
                                    </a>


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
                                {{$lead->project->name}}
                                @endif
                                @if(!empty($lead->category))
                                    <br>{{$lead->category->name}}
                                @endif

                                @if(!empty($lead->item))
                                {{$lead->item->name}}<br>
                                Buy Primar
                                @else
                                <br>Unit Not Selected
                                @endif
                            </td>


                            @if(Auth::user()->can('trashed.lead.share') || Auth::user()->can('trashed.lead.transfer') || Auth::user()->can('trashed.lead.restore') || Auth::user()->can('trashed.lead.delete'))
                                <td>
                                    @if($lead->share_id == 0)
                                        @if(Auth::user()->can('trashed.lead.share'))
                                            <a rel="tooltip" href="#" data-original-title="" title="" class="mr-15" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" from-user='<?php echo $lead->user_id; ?>' data-target="#leadshare" id="shareLead">
                                                <i class="fa fa-share-alt" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @if(Auth::user()->can('trashed.lead.transfer'))
                                            <a rel="tooltip" href="#" data-original-title="" title="" class="mr-10" data-toggle="modal" data-client="<?php echo $lead->client_id; ?>" data-bind="<?php echo $lead->id; ?>" from-user='<?php echo $lead->user_id; ?>' data-target="#leadtransfer" id="transferLead">
                                                <i class="fa fa-share" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if(Auth::user()->can('trashed.lead.restore') || Auth::user()->can('trashed.lead.delete'))
                                        <div style="display: inline-flex">
                                            @if(Auth::user()->can('trashed.lead.restore'))
                                                <form action="{{ url('lead/restore', $lead->id) }}" method="post" class="mr-10">
                                                    @csrf
                                                    <button type="button" class="fa fa-lock-open mr-5" data-original-title="" title="Actice Data" onclick="confirm('{{ __("Are you sure you want to Active this Record?") }}') ? this.parentElement.submit() : ''"></button>
                                                </form>
                                            @endif
                                            @if(Auth::user()->can('trashed.lead.delete'))
                                                <form action="{{ url('lead/delete/permanently', $lead->id) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" class="fa fa-trash " data-original-title="" title="Delete Data" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''"></button>
                                                </form>
                                            @endif
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
        <div class="ps-section__footer">
            <p>Showing {{($leads->currentpage()-1)*$leads->perpage()+1}} to {{$leads->currentpage()*$leads->perpage()}}
                of  {{$leads->total()}} entries
            </p>
            {{ $leads->appends(request()->except('page'))->links() }}
        </div>

        {{-- =============Lead Share Model============= --}}
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
        {{-- ==========Lead Trnasfer Model============== --}}
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

    </section>

</div>



@endsection






