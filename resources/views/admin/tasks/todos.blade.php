@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])

@section('content')
@include('leads.sidebar')
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
            <h3>Tasks to do</h3>
        </div>
        <div class="header__center">

        </div>
        
    </header>
    <section class="ps-items-listing">

        <div class="ps-section__header">
            <div class="row">
                <form class="ps-form--filter" action="{{url('todos/search')}}" method="post">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Search by </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <select class="form-control" name="allocated">
                                    <option value="0">All</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Task Type </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <select class="form-control" name="tasktype">
                                    <option> All </option>
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
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Task Type </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <input type="text" name="daterange" class="daterange form-control">
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
                </form>
            </div>
            
        </div>
        
        <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs tab-nav-center">
                        <li class="<?php if($todo == 'overdue'){ echo 'active'; } ?>"><a href="{{url('todos/overdue')}}">Overdue (<?php echo $overdue; ?>)</a></li>
                        <li class="<?php if($todo == 'today'){ echo 'active'; } ?>"><a href="{{url('todos/today')}}">Today (<?php echo $today; ?>)</a></li>
                        <li class="<?php if($todo == 'tomorrow'){ echo 'active'; } ?>"><a href="{{url('todos/tomorrow')}}">Tomorrow (<?php echo $tomorrow; ?>)</a></li>
                        <li class="<?php if($todo == 'week'){ echo 'active'; } ?>"><a href="{{url('todos/week')}}">7 Days (<?php echo $week; ?>)</a></li>
                        <li class="<?php if($todo == 'all' || $todo == ''){ echo 'active'; } ?>"><a href="{{url('todos/all')}}">All Days (<?php echo $alltasks; ?>)</a></li>
                    </ul>
                </div>
            </div>
        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>#Task</th>
                            <th>Deadline</th>
                            <th>Client</th>
                            <th>ID</th>
                            <th>Allocated To</th>
                            <th>Interest</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($tasks as $record)

                        <tr class="gradeX">
                            <td>
                                {{ $record->ntype }}
                            </td>
                            <td>
                                <span style="color: #e65c65 !important">{{ date('M d, Y', strtotime($record->deadline)) }} </span>
                            </td>

                            <td>
                                <a style="color: #fcb800;" href="{{url('client/edit',$record->client_id)}}">
                                    <strong>{{ $record->client->name }}</strong> </a></br>
                                {{ $record->client->leads_count()->count() }} Lead


                            </td>
                            <td>
                                @if(!empty($record->item))
                                <span style="color: #fcb800;">{{ $record->item->sbm.' - '.$record->lead_id }}</span></br>
                                {{date('M d, Y', strtotime($record->item->created_at))}}
                                @else
                                Nothing
                                @endif
                            </td>
                            <td>
                                @if(!empty($record->item))
                                {{ $record->addedBy->name }}
                                @else
                                Nothing
                                @endif
                            </td>

                            <td>
                                @if(!empty($record->item))
                                <span style="color: #fcb800;">{{ $record->item->name }}</span></br> Buy Primary
                                @else
                                Nothing
                                @endif

                            </td>
                            <td>
                                <?php
                                if (!empty($record->item)) {
                                    $item_id = $record->item->id;
                                } else {
                                    $item_id = 0;
                                }
                                ?>

                                <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $record->lead_id; ?>" data-client="<?php echo $record->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#tasklog" id="getTask">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </a>


                                <button type="button" class="btn btn-info waves-effect create_task" data-toggle="modal" data-bind="<?php echo $record->lead_id; ?>" data-client="<?php echo $record->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#myModalthree">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>


                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="ps-section__footer">
            {{ $tasks->links() }}
        </div>
    </section>
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
                                <label>Select Type</label>
                                <select name="type" id="type" class="form-control" required="">
                                    <option value="Calls">Calls</option>
                                    <option value="Meetings">Meetings</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Emails">Emails</option>
                                    <option value="Acquisition">Acquisition</option>
                                </select>
                            </div>
                            <div class="form-group" id="tasksubtype">
                                <label>Sub Type</label>
                                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8">
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
                                <textarea class="form-control" rows="2" name="note">{{ old('note') }}</textarea>
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
@endsection