@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])







@section('content')



@include('leads.sidebar')







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



    <header class="header--dashboard">



        <div class="header__left">



            <h3>Todos ({{$tasks->total()}})</h3>



        </div>



        <div class="header__center">







        </div>







    </header>



    <section class="ps-items-listing">







        <div class="ps-section__header">



            <div class="row">



                <form class="ps-form--filter" action="{{url('user/task')}}" method="get">



                    <!--                    @csrf



                                        @method('post')-->



                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">



                        <div class="form-group">



                            <label>Select User </label>



                            <div class="bootstrap-select fm-cmp-mg">



                                <select class="form-control" name="user_id">



                                    <option value="0">All</option>



                                    @foreach($users as $user)



                                    <option <?php



                                    if (app('request')->input('user_id') == $user->id) {



                                        echo 'selected';



                                    }



                                    ?> value="{{$user->id}}">{{$user->name}}</option>



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



                                    <option <?php



                                    if (app('request')->input('tasktype') == 0) {



                                        echo 'selected';



                                    }



                                    ?> value="0"> All </option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Contact Client') {



                                        echo 'selected';



                                    }



                                    ?> value="Contact Client">Contact Client</option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Follow Up') {



                                        echo 'selected';



                                    }



                                    ?> value="Follow Up">Follow Up</option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Receive Token Payment') {



                                        echo 'selected';



                                    }



                                    ?> value="Receive Token Payment">Receive Token Payment</option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Meet Client') {



                                        echo 'selected';



                                    }



                                    ?> value="Meet Client">Meet Client</option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Arrange Meeting') {



                                        echo 'selected';



                                    }



                                    ?> value="Arrange Meeting">Arrange Meeting</option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Receive Partial Token Payment') {



                                        echo 'selected';



                                    }



                                    ?> value="Receive Partial Token Payment">Receive Partial Token Payment</option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Receive Complete Down Payment') {



                                        echo 'selected';



                                    }



                                    ?> value="Receive Complete Down Payment">Receive Complete Down Payment</option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Closed (Won)') {



                                        echo 'selected';



                                    }



                                    ?> value="Closed (Won)">Closed (Won)</option>



                                    <option <?php



                                    if (app('request')->input('tasktype') == 'Sign Sale Agreement') {



                                        echo 'selected';



                                    }



                                    ?> value="Sign Sale Agreement">Sign Sale Agreement</option>



                                </select>



                            </div>



                        </div>



                    </div>



                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">



                        <div class="form-group">



                            <label>Task </label>



                            <div class="bootstrap-select fm-cmp-mg">



                                <select class="form-control" name="task">



                                    <option <?php



                                    if (app('request')->input('task') == 'all') {



                                        echo 'selected';



                                    }



                                    ?> value="all">All Days</option>



                                    <option <?php



                                    if (app('request')->input('task') == 'overdue') {



                                        echo 'selected';



                                    }



                                    ?> value="overdue"> Overdue </option>



                                    <option <?php



                                    if (app('request')->input('task') == 'today') {



                                        echo 'selected';



                                    }



                                    ?> value="today">Today</option>



                                    <option <?php



                                    if (app('request')->input('task') == 'tomorrow') {



                                        echo 'selected';



                                    }



                                    ?> value="tomorrow">Tomorrow</option>



                                    <option <?php



                                    if (app('request')->input('task') == 'week') {



                                        echo 'selected';



                                    }



                                    ?> value="week">7 Days</option>



                                    <option <?php



                                    if (app('request')->input('task') == 'month') {



                                        echo 'selected';



                                    }



                                    ?> value="month">Month</option>



                                </select>



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



        <!--        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec">



                    <ul class="lead_sorting">



                        <li><label>Total {{old('tasktype')}} Tasks : {{count($tasks)}}</label></li>



                    </ul>



                </div>-->







        <div class="ps-section__content">



            <div class="table-responsive">



                <table class="table ps-table">



                    <thead>



                        <tr>



                            <th>Client</th>



                            <th>Lead ID</th>



                            <th>Allocated To</th>



                            <th>Deadline</th>



                            <th>#Task</th>



                            <th>Interest</th>



                            <th>Action</th>



                        </tr>



                    </thead>



                    <tbody>







                        @foreach($tasks as $record)







                        <tr class="gradeX">



                            <td>



                                @if(!empty($record->client))



                                <div class="client-title tooltip">







                                    <div class="tooltiptext client-info">



                                        <div class="tooltip-head">Client Information:</div>



                                        <div class="inner-side"><label>Client Name: </label><span>{{ $record->client->name }}</span></div>



                                        {{-- =======Showing Phone Numbers --}}
                                            @php
                                                $counter = 1;
                                            @endphp

                                            @if ($record->client->phone)
                                                <div class="inner-side">
                                                    <label>Client Number {{ $counter }}: </label>
                                                    <span>{{ str_replace(' ', '', $record->client->phone) }}</span>
                                                </div>
                                                @php
                                                    $counter++;
                                                @endphp
                                            @endif

                                            @if ($record->client->clientNumbers)
                                                @foreach ($record->client->clientNumbers as $phone)
                                                    @if($record->client->phone != $phone->number)
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


                                        <div class="inner-side"><label>Client Email: </label><span>{{ $record->client->email }}</span></div>



                                    </div>



                                    <a href="{{url('client/edit',$record->client_id)}}" class="client-title">



                                        <div class="title-flag">



                                            <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">



                                            <strong>{{ $record->client->name }}</strong>



                                            <i class="fa fa-info-circle" aria-hidden="true"></i>



                                        </div>



                                    </a>



                                    <i class="fa fa-th" aria-hidden="true"></i>







                                    {{ $record->client->leads_count()->count() }} Lead



                                    @else



                                    Nothing











                                </div>



                                @endif







                            </td>



                            <td>



                                @if($record->lead_id > 0)



                                <span style="color: #fcb800;">{{ $record->lead_id }}</span></br>



                                {{date('M d, Y', strtotime($record->created_at))}}



                                @endif



                            </td>



                            <td>



                                {{ $record->addedBy->name }}



                            </td>



                            <td>



                                <span style="color: #e65c65 !important"> 



                                    <?php



                                    $now = time(); // or your date as well



                                    $your_date = strtotime($record->deadline);



                                    $datediff = $now - $your_date;



                                    $day = round($datediff / (60 * 60 * 24));



                                    if ($day > 1) {



                                        echo $day . ' days ago<br>';



                                    }elseif ($day == 1 ) {



                                        echo $day . ' day ago<br>';



                                    }else{



                                        



                                    }



                                    ?>







                                    {{ date('M d, Y', strtotime($record->deadline)) }}







                                </span>



                            </td>







                            <td>



                                {{ $record->ntype }}



                            </td>







                            <td>



                                @if($record->leadsTask->project)



                                {{$record->leadsTask->project->name}}



                                @endif



                                @if(!empty($record->item))



                                <span style="color: #fcb800;">{{ $record->item->name }}</span></br> Buy Primary



                                @endif



                                @if(!empty($record->item) && $record->leadsTask->project)



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







                                <!--



                                                                <button type="button" class="btn btn-info waves-effect create_task" data-toggle="modal" data-bind="<?php echo $record->lead_id; ?>" data-client="<?php echo $record->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#myModalthree">



                                                                    <i class="fa fa-plus" aria-hidden="true"></i>



                                                                </button>-->











                            </td>







                        <!--                            <td>



                                <a style="color: #fcb800;" href="{{url('client/edit',$record->client_id)}}">



                                    <strong>{{ $record->client->name }}</strong> </a></br>



                                {{ $record->client->leads_count()->count() }} Lead











                            </td>-->











                        </tr>



                        @endforeach







                    </tbody>



                </table>



            </div>



        </div>



        <div class="ps-section__footer">



            <p>Showing {{($tasks->currentpage()-1)*$tasks->perpage()+1}} to {{$tasks->currentpage()*$tasks->perpage()}}



                of  {{$tasks->total()}} entries



            </p>



            {{ $tasks->appends(request()->except('page'))->links() }}



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