@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Products')])

@section('content')
@include('admin.leads.sidebar')
<div class="ps-main__wrapper">
    @if (session('status'))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
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
        <div class="header__right">
            <a class="ps-btn success" href="{{ url('admin/lead/create') }}"><i class="icon icon-plus mr-2"></i>New Lead</a>
        </div>

    </header>
    <section class="ps-items-listing">

        <div class="ps-section__header">


            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('admin/leads/search')}}" method="post">
                    @csrf
                    @method('post')
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Lead ID </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="lead_id" id="input-name" type="text" placeholder="Enter Lead ID" value="{{old('lead_id')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 select-date">
                            <div class="form-group">
                                <label>Date Added </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" name="startDate" class="datepicker form-control" placeholder="Enter Start Date" value="{{old('daterange')}}">
                                </div>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" name="endDate" class="datepicker form-control" placeholder="Enter End Date" value="{{old('daterange')}}">
                                </div>
                            </div>
                        </div>
                        @if(count($users) > 0)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Allocation </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="user_id">
                                        <option value="0">All</option>
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
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
                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="status">
                                        <option value="">Do Nothing</option>
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
        @if(!empty($leads))
        <?php $todo = ''; ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec">
                    <ul class="lead_sorting">
                        <li>
                            <span>Show</span>
                            <select name="records" class="form-control">
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                            </select>
                        </li>
                        <li>
                            <label>Leads & Sort by</label>
                            <select name="status" class="form-control">
                                <option value="20">ID</option>
                                <option value="50">Allocation</option>
                                <option value="100">Status</option>
                                <option value="200">Client Name</option>
                                <option value="200">Last Updated</option>
                            </select>
                        </li>
                        <li>
                            <label>Order</label>
                            <select name="sort" class="form-control">
                                <option value="0">Assending</option>
                                <option value="1">Decending</option>
                            </select>
                        </li>
                        <li>
                            <input class="ps-btn success form-control" type="submit" name="submit" value="Go">
                        </li>
                    </ul>

					 <ul class="lead_status">
                        <li class="<?php if($todo == 'today' || $todo == ''){ echo 'active'; } ?>"><a href="{{url('leads/today')}}">Today</a></li>
                        <li class="<?php if($todo == 'overdue'){ echo 'active'; } ?>"><a href="{{url('leads/yesterday')}}">Yesterday</a></li>
                        <li class="<?php if($todo == 'tomorrow'){ echo 'active'; } ?>"><a href="{{url('leads/thisweek')}}">This Week</a></li>
                        <li class="<?php if($todo == 'week'){ echo 'active'; } ?>"><a href="{{url('leads/lastweek')}}">Last Week</a></li>
                        <li class="<?php if($todo == 'week'){ echo 'active'; } ?>"><a href="{{url('leads/thismonth')}}">This Month</a></li>
                        <li class="<?php if($todo == 'week'){ echo 'active'; } ?>"><a href="{{url('leads/lastmonth')}}">Last Month</a></li>
                        <li class="<?php if($todo == 'all'){ echo 'active'; } ?>"><a href="{{url('leads/favorites')}}">Favorites</a></li>
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
                                <a href="{{url('client/edit',$lead->client_id)}}">
                                    <div class="title-flag">
                                        <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">

                                        <div class="tooltip">
                                            <strong>{{ $lead->client->name }}</strong>
                                            <div class="tooltiptext client-info">
                                                <div class="tooltip-head">Client Information:</div>
                                                <div class="inner-side"><label>Client Name: </label><span>{{ $lead->client->name }}</span></div>
                                                <div class="inner-side"><label>Client Number: </label><span>{{ str_replace(' ','',$lead->client->phone) }}</span></div>
                                                <div class="inner-side"><label>Client Email: </label><span>{{ $lead->client->leadSource->type }}</span></div>

                                            </div>

                                        </div>

                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    </div>
                                </a>
                                <i class="fa fa-th" aria-hidden="true"></i>

                                {{ $lead->client->leads_count()->count() }} Lead
                                @else
                                Nothing
                                @endif


                            </td>

                            <td>

                                @if(!empty($lead->leadAgent))

                                    {{ $lead->leadAgent->name }}</br>

                                    <span style="color: grey;">

                                        {{-- {{ $lead->leadAgent->designation->name }} - --}}
                                        {{-- {{ $lead->leadAgent->department->name }} --}}

                                    </span>

                                @endif

                            </td>


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
                                {{ucfirst($lead->client->leadSource->subtype)}}<br>
                                {{ucfirst($lead->client->leadAffiliator->name)}}
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
                                {{$lead->taskStatus->note}}</br>
                                @endif
                                <span style="color: grey;">{{date('M d, Y', strtotime($lead->taskStatus->created_at))}}</span>
                            </td>
                            @else
                            <td>No Action</td>
                            @endif
                            <td>

                                @if(!empty($lead->item))
                                {{$lead->item->name}}</br>
                                Buy Primary
                                @else
                                Null
                                @endif
                            </td>

                            <td>
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
                                <button type="button" class="btn btn-info waves-effect create_task" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" data-client="<?php echo $lead->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#myModalthree">
                                    <i class="fa fa-plus"></i>
                                </button>

                                @if(Auth::user()->role == 1)
                                <a rel="tooltip" href="{{ url('lead/edit', $lead->id) }}" data-original-title="" title="">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ps-section__footer">
            {{ $leads->links() }}
        </div>

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
                                            <option value="">Do Nothing</option>
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
                                        <label>Note</label>
                                        <textarea class="form-control" rows="2" name="note" required="true">{{ old('note') }}</textarea>
                                    </div>
                                    <h2>Next Task</h2>
                                    <hr>
                                    <div class="form-group" id="ntasktype">
                                        <label>Select Type</label>
                                        <select name="ntype" id="ntype" class="form-control select-picker" data-size="8">
                                            <option> Do Nothing </option>
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
                            <button type="submit" class="btn btn-default" id="submit_task">Save changes</button>
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
