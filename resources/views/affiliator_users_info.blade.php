@extends('layouts.app', ['activePage' => 'affiliators', 'titlePage' => __('Products')])
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
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Report({{$all_tasks->total()}})</h3>
        </div>
    </header>
    <section class="ps-items-listing">

        @if(!empty($all_tasks))
        <div class="ps-section__content">
            <div class="table-responsive affilator_class">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="name-col">Name</th>
                            <th class="name-col">Business</th>
                            {{-- <th>Phone</th> --}}
                            <th>Type</th>
                            <th>Total Leads</th>
                            {{-- <th>Last Leads</th> --}}
                            {{-- <th>Address</th> --}}

                            <th>Completed</th>

                            <th>Added</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($all_tasks as $task)
                        <tr>
                            <td class="id-col">{{ $task->affliator_id }}</td>
                            <td class="name-col"><a href="{{route('affiliators.edit',$task->affliator_id)}}"><strong>{{ $task->affiliator->name }}</strong></a></td>

                            <td class="name-col">{{ $task->affiliator->business }}</td>
                            {{-- <td>{{ $task->affiliator->phone }}</td> --}}
                            <td>{{ $task->affiliator->type }}</td>

                            <?php $lead_count = $task->affiliator->leads_count()->count(); ?>

                            <td><a href="{{url('affiliator/leads', $task->affliator_id)}}">{{ $lead_count }}</a></td>

                            {{-- <td>
                                <?php
                                    if ($lead_count > 0) {
                                        echo date('M d, Y', strtotime($task->affiliator->lastlead->created_at));
                                    } else {echo 'No Lead';}
                                ?>
                            </td> --}}

                            {{-- <td>{{ $task->affiliator->address }}</td> --}}

                            <td class="name-col">{{ $task->subtype }}</td>

                            <td>
                                {{ $task->affiliator->assigned->name }}<br>
                                {{date('M d, Y', strtotime($task->completion_date))}}
                            </td>

                            <td>
                                @if(!empty($task->note))
                                    {{Str::limit( ucfirst($task->note) , 40)}}
                                @else
                                    No Action
                                @endif
                            </td>

                            <td>
                                @if ($task->affiliator->is_delete=='0')

                                    <form action="{{ route('affiliators.destroy', $task->affliator_id) }}" method="post">

                                        @csrf

                                        @method('DELETE')

                                        @if($task->affiliator->status == 1)

                                            <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-id="<?php echo $task->affliator_id; ?>" data-target="#tasklog" id="get_affiliator_Task">

                                                <i class="fa fa-calendar" aria-hidden="true"></i>

                                            </a>

                                                <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-id="<?php echo $task->affliator_id; ?>" data-target="#myModalthree" id="affiliatortask">

                                                <i class="fa fa-plus" aria-hidden="true"></i>

                                            </a>

                                            <button type="button" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                <div class="ripple-container"></div>
                                            </button>


                                        @elseif($task->affiliator->status == 3)

                                            {{-- Again Approvel<br> --}}

                                        @elseif($task->affiliator->status == 2)

                                            {{-- Rejected<br> --}}

                                            {{$task->$affiliator->note}}

                                        @elseif($task->affiliator->status == 0)
                                            Not Active
                                        @endif



                                    </form>

                                @else
                                    <div class="text-center">
                                        <i class="fa-solid fa-trash-arrow-up fa-lg" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
                <form method="post" action="{{ url('affiliator/task') }}" autocomplete="off"  class="form-horizontal" id="taskform" enctype="multipart/form-data">
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
                                    <!--                                    <div class="form-group meetinglocation" style="display: none;">
                                                                            <label>Location</label>
                                                                            <select name="location" class="form-control select-picker" data-size="8">
                                                                                <option value="">Select Location</option>
                                                                                <option>Company Office</option>
                                                                                <option>Out-Door</option>
                                                                                <option>Site Office</option>
                                                                                <option>Sales Office</option>
                                                                            </select>
                                                                        </div>-->
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
                                        <label>Deadline</label>
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
                    <div class="col-md-6">
                        <label>Deadline Date</label>
                        <input class="form-control deadline" type="text" placeholder="Deadline" name="deadline" required  value="{{old('deadline')}}" {{--value="{{ old('deadline', date('m/d/Y')) }}" --}} >
                    </div>
                    <div class="col-md-6">
                        <label>Deadline Time</label>
                        <input class="form-control" type="time" placeholder="Time" name="time"  value="{{$currentTime}}" >
                    </div>
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

        <div class="ps-section__footer">
            <p>Showing {{($all_tasks->currentpage()-1)*$all_tasks->perpage()+1}} to {{$all_tasks->currentpage()*$all_tasks->perpage()}}
                of  {{$all_tasks->total()}} entries
            </p>
            {{ $all_tasks->appends(request()->except('page'))->links() }}
        </div>

        @else
        No Record Found!
        @endif
    </section>
</div>
@endsection
