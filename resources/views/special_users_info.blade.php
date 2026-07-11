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
            <h3>Reports ({{$all_tasks->total()}})</h3>
        </div>
        <div class="header__center">

        </div>

    </header>
    <section class="ps-items-listing">

        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Lead ID</th>
                            <th>Allocated To</th>
                            <th>Date</th>
                            <th>Completed</th>
                            <th>Interest</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($all_tasks as $record)

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
                                    } elseif ($day == 1) {
                                        echo $day . ' day ago<br>';
                                    } else {

                                    }
                                    ?>

                                    {{ date('M d, Y', strtotime($record->completion_date)) }}

                                </span>
                            </td>

                            <td>
                                {{ $record->subtype }}
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
            <p>Showing {{($all_tasks->currentpage()-1)*$all_tasks->perpage()+1}} to {{$all_tasks->currentpage()*$all_tasks->perpage()}}
                of  {{$all_tasks->total()}} entries
            </p>
            {{ $all_tasks->appends(request()->except('page'))->links() }}
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
			    <th>Client</th>
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
        <form method="post" action="{{ url('task/store') }}"  autocomplete="off" class="form-horizontal" id="taskform" enctype="multipart/form-data">
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
        <div class="col-md-6">
            <label>Deadline Date</label>
            <input class="form-control deadline" type="text" placeholder="Date" name="deadline" required  value="{{old('deadline')}}" {{--value="{{ old('deadline', date('m/d/Y')) }}" --}} >
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
                    <button type="submit" class="btn btn-default" id="submit_task">Save changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
