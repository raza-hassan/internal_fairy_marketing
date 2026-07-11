@extends('layouts.app', ['activePage' => 'affiliators', 'titlePage' => __('Affiliators')])
@section('content')
@include('affiliators.sidebar')

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


        <h3>Todos ({{$tasks->total()}})</h3>



        </div>


        <div class="header__center">





        </div>





    </header>


    <section class="ps-items-listing">





        <div class="ps-section__header">


            <div class="row">


                <form class="ps-form--filter" action="{{url('affliator-todos/search')}}" method="get">


                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">


                        <div class="form-group">


                            <label>Select User </label>


                            <div class="bootstrap-select fm-cmp-mg">


                                <select class="form-control" name="user_id">


                                    @if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)


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


                                    <option <?php if($user_id == $user->id){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>


                                    @endforeach


                                </select>


                            </div>


                        </div>


                    </div>


                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">


                        <div class="form-group">


                            <label>Task Type </label>


                            <div class="bootstrap-select fm-cmp-mg">


                                <select name="tasktype" id="ntype" class="form-control select" required="" data-size="8">


                                    <option <?php if(app('request')->input('tasktype') == 0){ echo 'selected'; } ?> value="0"> -- All --</option>


                                    <option <?php if(app('request')->input('tasktype') == 'Calls'){ echo 'selected'; } ?> value="Calls">Calls</option>


                                    <option <?php if(app('request')->input('tasktype') == 'Meetings'){ echo 'selected'; } ?> value="Meetings">Meetings</option>


                                    <option <?php if(app('request')->input('tasktype') == 'Commision Cheque'){ echo 'selected'; } ?> value="Commision Cheque">Commision Cheque</option>


                                    <option <?php if(app('request')->input('tasktype') == 'Events'){ echo 'selected'; } ?> value="Events">Events</option>


                                    <option <?php if(app('request')->input('tasktype') == 'Marketing Materials'){ echo 'selected'; } ?> value="Marketing Materials">Marketing Materials</option>


                                    <option <?php if(app('request')->input('tasktype') == 'Sales'){ echo 'selected'; } ?> value="Sales">Sales</option>


                                    <option <?php if(app('request')->input('tasktype') == 'Emails'){ echo 'selected'; } ?> value="Emails">Emails</option>


                                    <option <?php if(app('request')->input('tasktype') == 'Complaint'){ echo 'selected'; } ?> value="Complaint">Complaint</option>


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


                                    if (app('request')->input('task') == 'overdue' || app('request')->input('task') == '') {


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


        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                <ul class="nav nav-tabs tab-nav-center task">

                    <li class="<?php if($todo == 'overdue' || $todo == ''){ echo 'active'; } ?>"><a href="{{url('affliator-todos/search?user_id='.$user_id.'&tasktype='.app('request')->input('tasktype').'&task=overdue&submit=Filter')}}">Overdue (<?php echo $overdue; ?>)</a></li>
                    <li class="<?php if($todo == 'today'){ echo 'active'; } ?>"><a href="{{url('affliator-todos/search?user_id='.$user_id.'&tasktype='.app('request')->input('tasktype').'&task=today&submit=Filter')}}">Today (<?php echo $today; ?>)</a></li>
                    <li class="<?php if($todo == 'tomorrow'){ echo 'active'; } ?>"><a href="{{url('affliator-todos/search?user_id='.$user_id.'&tasktype='.app('request')->input('tasktype').'&task=tomorrow&submit=Filter')}}">Tomorrow (<?php echo $tomorrow; ?>)</a></li>
                    <li class="<?php if($todo == 'week'){ echo 'active'; } ?>"><a href="{{url('affliator-todos/search?user_id='.$user_id.'&tasktype='.app('request')->input('tasktype').'&task=week&submit=Filter')}}">7 Days (<?php echo $week; ?>)</a></li>
                    <li class="<?php if($todo == 'month' || $todo == 'all'){ echo 'active'; } ?>"><a href="{{url('affliator-todos/search?user_id='.$user_id.'&tasktype='.app('request')->input('tasktype').'&task=month&submit=Filter')}}"> Month (<?php echo $tasks; ?>)</a></li>

                 </ul>
            </div>
        </div>







        <div class="ps-section__content">


            <div class="table-responsive">


                <table class="table ps-table">


                    <thead>


                        <tr>


                            <th>Type </th>


                            <th>Name </th>


                            <th>Assign To</th>


                            <th>Added By</th>


                            <th>Status </th>


                            <th>Discard Reason</th>


                            <th>Deadline</th>


                            <th>Created At</th>


                            <th>Updated At</th>


                            <th>Actions</th>


                        </tr>


                    </thead>


                    <tbody>





                        @foreach($tasks as $record)





                        <tr class="gradeX">





                            <td>


                                {{ $record->ntype }}


                            </td>





                            <td>





                                <div class="client-title tooltip">


                                    <div class="tooltiptext client-info">


                                        <div class="tooltip-head">Affiliator Information:</div>


                                        <div class="inner-side"><label>Name: </label><span>{{ ucfirst($record->affiliator->name) }}</span></div>


                                        {{-- =======Showing Phone Numbers --}}
                                            @php
                                                $counter = 1;
                                            @endphp

                                            @if ($record->affiliator->phone)
                                                <div class="inner-side">
                                                    <label>Phone Number {{ $counter }}: </label>
                                                    <span>{{ str_replace(' ', '', $record->affiliator->phone) }}</span>
                                                </div>
                                                @php
                                                    $counter++;
                                                @endphp
                                            @endif

                                            @if ($record->affiliator->affiliatorNumbers)
                                                @foreach ($record->affiliator->affiliatorNumbers as $phone)
                                                    @if($record->affiliator->phone != $phone->number)
                                                        <div class="inner-side">
                                                            <label>Phone Number {{ $counter }}: </label>
                                                            <span>{{ str_replace(' ', '', $phone->number) }}</span>
                                                        </div>
                                                        @php
                                                            $counter++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endif

                                        {{-- =======Showing Phone Numbers --}}

                                        <div class="inner-side"><label>Email: </label><span>{{ $record->affiliator->email }}</span></div>


                                    </div>


                                    <a href="#" class="client-title">


                                        <div class="title-flag">


                                            <strong>{{ ucfirst($record->affiliator->name )}}</strong>


                                            <i class="fa fa-info-circle" aria-hidden="true"></i>


                                        </div>


                                    </a>


                                </div>


                            </td>





                            <td>


                                {{ $record->addedBy->name }}


                            </td>


                            <td>


                                {{ $record->addedBy->name }}


                            </td>


                            <td>


                                @if($record->status == 0)


                                In Progress


                                @elseif($record->status == 1)


                                Completed


                                @else


                                Discarded


                                @endif


                            </td>


                            <td></td>


                            <td>{{ date('M d, Y', strtotime($record->deadline)) }}</td>


                            <td>{{ date('M d, Y', strtotime($record->created_at)) }}</td>


                            <td>{{ date('M d, Y', strtotime($record->updated_at)) }}</td>





                            <td>


                                <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-id="<?php echo $record->affliator_id; ?>" data-target="#tasklog" id="get_affiliator_Task">


                                    <i class="fa fa-calendar" aria-hidden="true"></i>


                                </a>


                                <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-id="<?php echo $record->affliator_id; ?>" data-target="#myModalthree" id="affiliatortask">


                                    <i class="fa fa-plus" aria-hidden="true"></i>


                                </a>


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


                            <th>ID</th>


                            <th>Task</th>


                            <th>Comments</th>


                            <th>Created at</th>


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


        <form method="post" action="{{ url('affiliator/task') }}" autocomplete="off" class="form-horizontal" id="taskform" enctype="multipart/form-data">


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


                            <div class="form-group" id="affiliatortasktype">


                                <label>Select Type<sup>*</sup></label>


                                <select name="type" class="form-control" required="">


                                    <option value="Calls">Calls</option>


                                    <option value="Meetings">Meetings</option>


                                    <!--<option value="Sales">Sales</option>-->


                                    <option value="Emails">Emails</option>


                                    <option value="Complaint">Complaint</option>


                                </select>


                            </div>


                            <div class="form-group" id="callsubtype">


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








                            <div class="form-group">


                                <label>Completed Date


                                </label>


                                <input class="form-control completion_date" type="text" name="completion_date" value="{{ old('duedate', date('m/d/Y')) }}"/>


                            </div>





                            <div class="form-group task_attachment" style="display: none;">


                                <label>Attachment</label>


                                <div class="form-group--nest">


                                    <input class="form-control mb-1" name="file" type="file" placeholder="">


                                    <button class="ps-btn ps-btn--sm">Choose</button>


                                </div>


                            </div>





                            <input type="hidden" name="affliator_id" id="affliator_id" value="">


                            <input type="hidden" name="user_id" id="added_by" value="{{Auth::user()->id}}">


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


                                    <option value="Calls">Calls</option>


                                    <option value="Meetings">Meetings</option>


                                    <option value="Commision Cheque">Commision Cheque</option>


                                    <option value="Events">Events</option>


                                    <option value="Marketing Materials">Marketing Materials</option>


                                    <!--<option value="Sales">Sales</option>-->


                                    <option value="Emails">Emails</option>


                                    <option value="Complaint">Complaint</option>


                                </select>


                            </div>





                            {{-- <div class="form-group">


                                <label>Deadline


                                </label>


                                <input class="form-control deadline" type="text" placeholder="Deadline" name="deadline" value="{{ old('deadline', date('m/d/Y')) }}">


                            </div> --}}





{{-- ========================== --}}


<?php


    // date_default_timezone_set('Asia/Karachi');


    $mytime = Carbon\Carbon::now();


    $currentTime= $mytime->toTimeString();


?>


<div class="form-group">


    <div class="row">


        <div class="col-md-12">


            <label>Deadline Date</label>


            <input class="form-control deadline" type="text" placeholder="Deadline" name="deadline" required  value="{{old('deadline')}}" {{--value="{{ old('deadline', date('m/d/Y')) }}" --}} >


        </div>


        <!-- <div class="col-md-6">


            <label>Deadline Time</label>


            <input class="form-control" type="time" placeholder="Time" name="time"  value="{{$currentTime}}" >


        </div> -->


    </div>


</div>


{{-- ======================= --}}





                        </div>


                    </figure>


                </div>


                <div class="modal-footer">


                    <button type="submit" class="btn btn-success button" id="affliator_submit_task">Save changes</button>


                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>


                </div>





            </div>


        </form>


    </div>


</div>





@endsection


