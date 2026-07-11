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
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Leads</h3>
        </div>
        <div class="header__center">
        </div>
    </header>
    <section class="ps-items-listing">
        @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 )
        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('lead/report')}}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="account" value="{{$account}}">
                    <div class="row">
                        @if(count($users) > 0)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Allocation </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="user_id">
                                        <option value="0">All</option>
                                        @foreach($users as $user)
                                        <option <?php if(app('request')->input('user_id') == $user->id || Auth::user()->id == $user->id){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(count($projects) > 0)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Projects </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="project_id">
                                        <option value="0">All</option>
                                        @foreach($projects as $project)
                                        <option <?php if(app('request')->input('project_id') == $project->id){ echo 'selected'; } ?> value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(count($sources) > 0)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Sources </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="source_id">
                                        <option value="0">All</option>
                                        @foreach($sources as $source)
                                        <option <?php if(app('request')->input('source_id') == $source->id){ echo 'selected'; } ?> value="{{$source->id}}">{{$source->type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
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
        <?php
//        $assigned_leads = 0;
//        $inassigned_leads = 0;
        ?>
        @if(!empty($leads))
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec">
            <ul class="lead_sorting">
                <li><label>Total Leads : {{count($leads)}}</label></li>
            </ul>
        </div>
        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkbox[]"></th>
                            <th>Client</th>
                            <th>Allocated To</th>
                            <th>Lead-ID</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Interest</th>
                            <th>Actions</th>
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
                                    </div>
                                    <a href="{{url('client/edit',$lead->client_id)}}" class="client-title">
                                        <div class="title-flag">
                                            <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">
                                            <strong>{{ $lead->client->name }}</strong>
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        </div>
                                    </a>
                                    <i class="fa fa-th" aria-hidden="true"></i>
                                    {{ $lead->client->leads_count()->count() }} Lead
                                    @else
                                    Nothing
                                    @endif
                                </div>
                            </td>
			{{-- ======================================================================================= --}}
			<td>
                @if($lead->user_id > 0)
    				{{ $lead->leadAgent->name }} @if($lead->share_id > 0) | {{ $lead->sharePerson->name}} @endif   </br>
    				<span style="color: grey;">{{ $lead->leadAgent->designation->name }} - {{ $lead->leadAgent->department->name }}</span><br>
    				{{--  Lead Share With [User Name] --}}
    				{{-- @if ($lead->leadshare)
        				<span style="color: grey;">{{ $lead->leadshare->name }} </span><br>
			    	@endif --}}
			    	{{-- Lead Share With How much users [User Count] --}}
    				{{-- @if ($lead->shared_leads_count()->count() > 0 )
        				<span style="color: grey;">{{ $lead->shared_leads_count()->count() }}</span>
    				@endif --}}
                @else
                    Not Assigned
                @endif
			</td>
			{{-- ======================================================================================= --}}
                            <td>
                                <a style="color: #fcb800;" href="{{url('lead/edit',$lead->id)}}">
                                    {{ $lead->id }}
                                </a>
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
                                @if(!empty($lead->item))
                                {{$lead->item->name}}</br>
                                Buy Primar
                                @else
                                </br>Unit Not Selected
                                @endif
                            </td>
                            <td style="width: 9%;">
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
<!--                                <button type="button" class="btn btn-info waves-effect create_task tooltip add_icon" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" data-client="<?php echo $lead->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#myModalthree" >
                                    <i class="fa fa-plus"></i>
                                    <span class="tooltiptext">Add Task</span>
                                </button>-->
                             	{{-- ======================================================================================= --}}
                                <!--@if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                                    <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" from-user='<?php echo $lead->user_id; ?>' data-target="#leadshare" id="shareLead">
                                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                                        <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" from-user='<?php echo $lead->user_id;  ?>' data-target="#leadtransfer" id="transferLead">
                                            <i class="fa fa-share" aria-hidden="true"></i>
                                        </a>
                                    @endif-->
				{{-- ======================================================================================= --}}
                                @if(Auth::user()->role == 1)
                                {{--<a rel="tooltip" href="{{ url('lead/edit', $lead->id) }}" data-original-title="" title="">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>--}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
	{{-- =================================Lead Share Model====================================================== --}}
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
                            <button type="submit" class="btn btn-success button" id="submit_task" >Share Lead</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	{{-- =================================Lead Trnasfer Model================================================= --}}
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
                                    <div class="form-group">
                                        <label>Comments<sup>*</sup></label>
                                            <textarea class="form-control" rows="2" name="note" required="">{{ old('note') }}</textarea>
                                        </div>
                                    </div>
                            </figure>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success button" id="submit_task" >Transfer</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	{{-- ======================================================================================= --}}
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
                                    <th>Lead</th>
                                    <th>Interest</th>
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
                <form method="post" action="{{ url('task/store') }}" class="form-horizontal" id="taskform" enctype="multipart/form-data">
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
                                        <label>Attachment</label>
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
                                            <option>Contact Client</option>
                                            <option>Follow Up</option>
                                            <option>Receive Token Payment</option>
                                            <option>Meet Client</option>
                                            <option>Arrange Meeting</option>
                                            <option>Receive Partial Token Payment</option>
                                            <option>Receive Complete Down Payment</option>
                                            <option>Closed (Won)</option>
                                            <option>Sign Sale Agreement</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Deadline
                                        </label>
                                        <input class="form-control deadline" type="text" placeholder="Deadline" name="deadline" value="{{ old('deadline', date('m/d/Y')) }}">
                                    </div>
                                </div>
                            </figure>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success button" id="submit_task">Save changes</button>
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
