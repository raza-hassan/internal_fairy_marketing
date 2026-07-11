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

            <h3>Affiliators ({{$affiliators->total()}})</h3>

        </div>

        <div class="header__center">
        </div>

        @if(Auth::user()->can('affiliator.create'))
            <div class="header__right">
                <a class="ps-btn success" href="{{ route('affiliators.create') }}"><i class="icon icon-plus mr-2"></i>New Affiliator</a>
            </div>
        @endif

    </header>

    <section class="ps-items-listing">



        <div class="ps-section__header">

            <div class="ps-section__filter">

                <form class="ps-form--filter" action="{{ url('affiliator-search') }}" method="get">

                    <!-- @csrf

                    @method('post')-->

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Name </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Enter Name') }}" value="{{app('request')->input('name')}}">

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Email</label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <input class="form-control" name="email" id="input-name" type="email" placeholder="{{ __('Enter Email') }}" value="{{app('request')->input('email')}}">

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Cell </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <input class="form-control" name="phone" id="input-name" type="text" placeholder="{{ __('Enter Cell') }}" value="{{app('request')->input('phone')}}">

                                </div>

                            </div>

                        </div>



                        @if(count($users) > 0)

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Allocation </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="form-control" name="user_id">

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

                                        @foreach($users as $user)

                                        <option <?php if($user_id == $user->id ){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                        @endif





                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Affiliator Type </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="form-control" name="type">

                                        <option value="0">Select Type</option>

                                        <option <?php if(app('request')->input('type') == 'Dealer'){ echo 'selected'; } ?> value="Dealer">Dealer</option>

                                        <option <?php if(app('request')->input('type') == 'Freelancer'){ echo 'selected'; } ?> value="Freelancer">Freelancer</option>



                                    </select>

                                </div>

                            </div>

                        </div>



                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Status </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="form-control" name="status">

                                        <option <?php if(app('request')->input('status') == ''){ echo 'selected'; } ?> value="">Select Status </option>

                                        <option <?php if(app('request')->input('status') == 1){ echo 'selected'; } ?> value="1">Active</option>

                                        <option <?php if(app('request')->input('status') == 0 && app('request')->input('status') != ''){ echo 'selected'; } ?> value="0">InActive</option>

                                        <option <?php if(app('request')->input('status') == 2){ echo 'selected'; } ?> value="2">Rejected</option>

                                        <option <?php if(app('request')->input('status') == 3){ echo 'selected'; } ?> value="3">Again Approval Request</option>

                                    </select>

                                </div>

                            </div>

                        </div>


                        @if(count($locations) > 0)
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <div class="form-group">
                                    <label>Location</label>
                                    <select class="form-control locations_select2" name="location_id[]" multiple>
                                        <option value="">All Locations</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}"
                                                {{ in_array($location->id, request()->input('location_id', [])) ? 'selected' : '' }}>
                                                {{ $location->full_path }}
                                            </option>
                                        @endforeach
                                    </select>
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

        @if(!empty($affiliators))

        <div class="ps-section__content">



            <div class="table-responsive affilator_class">

                <table class="table ps-table">

                    <thead>

                        <tr>

                            <th class="id-col">ID</th>

                            <th class="name-col">Name</th>

                            <th class="name-col">Business</th>

                            <th>Phone</th>

                            <th>Type</th>

                            <th>Total Leads</th>

                            <th>Last Leads</th>

                            <th>Address</th>

                            <th>Added</th>

                            <th>Status</th>

                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($affiliators as $affiliator)

                        <tr>

                            <td class="id-col">{{ $affiliator->id }}</td>

                            <td class="name-col">
                                @if(Auth::user()->can('affiliator.edit'))
                                    <a href="{{route('affiliators.edit',$affiliator->id)}}"><strong>{{ $affiliator->name }}</strong></a>
                                @else
                                    <strong>{{ $affiliator->name }}</strong>
                                @endif
                            </td>

                            <td class="name-col">

                                {{ $affiliator->business }}

                            </td>

                            <td>{{ $affiliator->phone }}</td>

                            <td>{{ $affiliator->type }}</td>

                            <?php

                            $lead_count = $affiliator->leads_count()->count();

                            ?>

                            <td>
                                @if(Auth::user()->can('lead.view'))
                                    <a href="{{url('affiliator/leads', $affiliator->id)}}">{{ $lead_count }}</a></td>
                                @else
                                    {{ $lead_count }}
                                @endif
                            <td>

                                <?php

                                if ($lead_count > 0) {

                                    echo date('M d, Y', strtotime($affiliator->lastlead->created_at));

                                } else {

                                    echo 'No Lead';

                                }

                                ?>

                            </td>

                            <td>{{ $affiliator->address }}</td>





                            <td>

                                {{ $affiliator->assigned->name }}<br>

                                {{date('M d, Y', strtotime($affiliator->created_at))}}

                            </td>



                            <td>

                                @if(!empty($affiliator->taskStatus))

                                <!-- {{$affiliator->taskStatus->note}} -->
                                {{Str::limit( ucfirst($affiliator->taskStatus->note) , 40)}}


                                @else

                                    No Action

                                @endif

                            </td>

                            <td>
                                @if ($affiliator->is_delete=='0')

                                    <form action="{{ route('affiliators.destroy', $affiliator->id) }}" method="post">

                                        @csrf

                                        @method('DELETE')

                                        @if($affiliator->status == 1)

                                            <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-id="<?php echo $affiliator->id; ?>" data-target="#tasklog" id="get_affiliator_Task">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </a>

                                            <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-id="<?php echo $affiliator->id; ?>" data-target="#myModalthree" id="affiliatortask">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </a>

                                            @if(Auth::user()->can('affiliator.trash'))
                                                <button type="button" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                    <div class="ripple-container"></div>
                                                </button>
                                            @endif


                                        @elseif($affiliator->status == 3)

                                            {{-- Again Approvel<br> --}}

                                        @elseif($affiliator->status == 2)

                                            {{-- Rejected<br> --}}

                                            {{$affiliator->note}}

                                        @elseif($affiliator->status == 0)

                                            Not Active

                                        <!-- <a rel="tooltip" href="{{ route('affiliators.edit', $affiliator->id) }}" data-original-title="" title="">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>-->
                                        <!-- <a rel="tooltip" href="{{ url('affiliator/lead', $affiliator->id) }}" data-original-title="" title="">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </a>-->

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

                                            {{-- <option value="Sales">Sales</option> --}}

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


                                    <div class="form-group affmeetinglocation" id="affmeetinglocation" style="display: none;">
                                        <label>Location<sup>*</sup></label>
                                        <select name="location" id="aff_location" class="form-control select-picker" data-size="8">
                                            <option value="">Select Location</option>
                                            <option>Company Office</option>
                                            <option>Out-Door</option>
                                            <option>Site Office</option>
                                            <option>Sales Office</option>
                                        </select>
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

        <div class="col-md-12">

            <label>Deadline Date</label>

            <input class="form-control deadline" type="text" placeholder="Deadline" name="deadline" required  value="{{old('deadline')}}" {{--value="{{ old('deadline', date('m/d/Y')) }}" --}} >

        </div>

        {{-- <div class="col-md-6">

            <label>Deadline Time</label>

            <input class="form-control" type="time" placeholder="Time" name="time"  value="{{$currentTime}}" >

        </div> --}}

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



<!--        <div class="ps-section__footer">

            {{ $affiliators->links() }}

        </div>-->

        <div class="ps-section__footer">

            <p>Showing {{($affiliators->currentpage()-1)*$affiliators->perpage()+1}} to {{$affiliators->currentpage()*$affiliators->perpage()}}

                of  {{$affiliators->total()}} entries

            </p>

            {{ $affiliators->appends(request()->except('page'))->links() }}

        </div>

        @else

        No Record Found!

        @endif

    </section>

</div>



@endsection

