@extends('layouts.app', ['activePage' => 'clients', 'titlePage' => __('Clients')])
@section('content')
@include('clients.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Edit Client</h3>
        </div>

    </header>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
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

    <section class="ps-new-item">
        <form method="post" action="{{ url('client/update', $client) }}" class="form-horizontal" enctype="multipart/form-data" onSubmit="return check_client()">
            @csrf
            @method('put')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>General</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Name<sup>*</sup>
                                    </label>
                                    <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }} name" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $client->name) }}" required="true" aria-required="true"/>
                                    @if ($errors->has('name'))
                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', $client->email) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Cnic
                                    </label>
                                    <input class="form-control" name="cnic" id="input-name" type="text" placeholder="{{ __('Cnic') }}" value="{{ old('cnic', $client->cnic) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>CNIC Front Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicf" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($client->cnicf != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$client->cnicf) }}">
                                    @endif
                                </div>
                                <input type="hidden" name="oldcnicf" value="{{$client->cnicf}}">
                                <input type="hidden" name="oldcnicb" value="{{$client->cnicb}}">

                                <div class="form-group">
                                    <label>CNIC Back Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicb" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($client->cnicb != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$client->cnicb) }}">
                                    @endif
                                </div>
                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Client Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Gender
                                    </label>
                                    <select class="ps-select" title="Status" name="gender">
                                        <option value="Male">Select Type</option>
                                        <option <?php
                                        if ($client->gender == 'Male') {
                                            echo 'selected';
                                        }
                                        ?> value="Male">Male</option>
                                        <option <?php
                                        if ($client->gender == 'Female') {
                                            echo 'selected';
                                        }
                                        ?> value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Address
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Address" name="address" value="{{ old('address', $client->address) }}"/>
                                </div>

                                {{--===================Phone=================== --}}
                                    <div class="form-group">
                                        <label> Phone </label> <br>
                                        <input class="phonestatus" type="radio" name="phonenumber" data-plugin-ios-switch  value="1" style="height: auto !important;"  @if(old('phonenumber')=='1' || old('phonenumber')=='') checked @endif/> Mobile Phone
                                        <input class="phonestatus" type="radio" name="phonenumber" data-plugin-ios-switch  value="0" style="height: auto !important;"  @if(old('phonenumber')=='0') checked @endif/> Ptcl
                                    </div>

                                    <style>
                                        .iti.iti--allow-dropdown.iti--separate-dial-code{width: 100%;}
                                        input#leadphone{width: 100%;}
                                    </style>

                                    <div class="mobilediv form-group mb-0" style="display: @if(old('phonenumber')=='0') none @else block @endif;">
                                        <label>Mobile Phone<sup>*</sup></label>
                                        <div class="col-sm-11" >
                                            <div class="form-group mb-0" >
                                                <input <?php if (Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14 ){ ?> readonly="" <?php } ?> class="form-control phone search_number" maxlength="15"  name="phone"  client-id="{{ $client->id }}" id="leadphone" type="text" required=""  value="{{ old('phone',$client->phone) }}" />
                                                <span id="error-msg" class="hide"></span>
                                                <p id="result"></p>
                                            </div>
                                        </div>
                                        <div class="col-sm-1 form-group" >
                                            <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success" id="add-dynamic-international-phone1"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button>
                                        </div>
                                    </div>

                                    <input type="hidden" name="phone_number_id" value="{{ $phone->id }}"/>


                                    <div class="ptcldiv form-group mb-0" style="display: @if(old('phonenumber')=='1' || old('phonenumber')=='') none @else block @endif;">
                                        <label>Ptcl<sup>*</sup></label>
                                        <div class="col-sm-11 ">
                                            <div class="form-group mb-0">
                                                <input <?php if (Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14){ ?> readonly="" <?php } ?> class="form-control ptclphone search_number" name="ptclphone" client-id="{{ $client->id }}" placeholder="Ptcl Phone Number"  id="leadptcl" type="text" required=""  value="{{ old('ptclphone') }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-1 form-group" >
                                            <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success" id="add-dynamic-international-phone2"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button>
                                        </div>
                                    </div>

                                    <div id="resu"  style="margin-bottom: 20px;"></div>


                                    {{-- Multi Phone --}}
                                    <style>
                                        .white_hover:hover{
                                            color:white !important;
                                        }
                                        div#dynamicphoneFields .col-sm-1.pt-2 {
                                            padding-left: 0;
                                        }
                                        button#add-dynamic-international-phone1 {
                                            height: 40px;
                                        }
                                        button#add-dynamic-international-phone1 i {
                                            color: #fff !important;
                                        }
                                        div#dynamicphoneFields button {
                                            background: transparent;
                                            left: -10px;
                                            position: relative;
                                        }
                                        div#dynamicphoneFields button i {
                                            font-size: 20px;
                                        }
                                        div#dynamicphoneFields .mb-2 {
                                            margin-bottom: 0.4em !important;
                                        }
                                    </style>

                                    <div class="form-group">
                                        <div id="dynamicphoneFields">

                                            @foreach ($numbers as $key => $record)

                                                <div class="row">
                                                    <div class="col-sm-11 mb-2">
                                                        <input type="text" maxlength="15" required="true" name="edit_international_phone_dynamic[]" class="form-control search_edit_international_multiphone_number" placeholder="301 2345678" id="search_edit_international_number{{ $key+1 }}" counter="{{ $key+1 }}" client_id={{ $client->id }} @if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) style="width: 100%; padding-left: 83px;" @else style="width: 68%;"  @endif value="{{ $record->number }}"  <?php if (Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14){ ?>  readonly <?php } ?>>
                                                        <input type="hidden" name="edit_phone_number_ids[{{$key+1}}]" value="{{ $record->id }}"/>
                                                    </div>

                                                    @if (Auth::user()->role == 1 || Auth::user()->role == 5  || Auth::user()->role == 13 || Auth::user()->role == 14 )
                                                        <div class="col-sm-1 pt-2">
                                                            <button type="button" class="btn remove-edit-international-input-field" id="remove-edit-international-input-field-{{ $key+1 }}" client_id={{ $client->id }} number_id="{{ $record->id }}"><i class="fa fa-trash fa-2x fa-solid" aria-hidden="true"></i></button>
                                                        </div>
                                                    @endif

                                                    <div id="error-msg{{ $key+1 }}"></div>
                                                    <div id="result{{ $key+1 }}"></div>
                                                    <div id="client-result{{ $key+1 }}"></div>
                                                </div>

                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Multi Phone --}}

                                {{--===================Phone=================== --}}

                                {{-- <div class="form-group">
                                    @if($client->telephone != '')
                                    <label>TelePhone 1</label>
                                    <input @if($client->telephone != '') id="telephone" @endif <?php if (Auth::user()->role != 1) { ?> readonly="" <?php } ?> class="form-control search_number" type="text" placeholder="Telephone 1" name="telephone" value="{{ $client->telephone }}"/>
                                    @else
                                    <label style="width: 100%;">TelePhone 1 </label>
                                    <select name="countryCode1" class="form-control" style="width: 30%;float: left;margin-right: 1%;">
                                        <option value="+92" selected="">Pakistan (+92)</option>
                                        <option value="+44">UK (+44)</option>
                                        <option value="+1">USA (+1)</option>
                                        <option value="+974">Qatar (+974)</option>
                                        <option value="+966">Saudi Arabia (+966)</option>
                                        <option value="+90">Turkey (+90)</option>
                                        <option value="+971">United Arab Emirates (+971)</option>
                                    </select>
                                    <input class="form-control search_number" id="telephone" type="text" placeholder="TelePhone 1" name="telephone" value="{{ old('telephone') }}" style="width: 69%;"/>
                                    @endif
                                </div>
                                <div class="form-group">
                                    @if($client->telephone1 != '')
                                    <label>TelePhone 2
                                    </label>
                                    <input @if($client->telephone1 != '') id="telephone1" @endif  <?php if (Auth::user()->role != 1) { ?> readonly="" <?php } ?> class="form-control search_number" type="text" placeholder="Telephone 2" name="telephone1" value="{{ $client->telephone1 }}"/>
                                    @else
                                    <label style="width: 100%;">TelePhone 2 </label>
                                    <select name="countryCode2" class="form-control" style="width: 30%;float: left;margin-right: 1%;">
                                        <option value="+92" selected="">Pakistan (+92)</option>
                                        <option value="+44">UK (+44)</option>
                                        <option value="+1">USA (+1)</option>
                                        <option value="+974">Qatar (+974)</option>
                                        <option value="+966">Saudi Arabia (+966)</option>
                                        <option value="+90">Turkey (+90)</option>
                                        <option value="+971">United Arab Emirates (+971)</option>
                                    </select>
                                    <input class="form-control search_number" id="telephone1" type="text" placeholder="TelePhone 2" name="telephone1" value="{{ old('telephone1') }}" style="width: 69%;"/>
                                    @endif
                                </div> --}}


                            </div>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                @if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14 )
                <a class="ps-btn ps-btn--black" href="{{url('all/clients/'.$client->user_id)}}">Go Back >></a>
                @endif
                <a class="ps-btn ps-btn--black" href="{{url('clients')}}">Cancel</a>
                <button class="ps-btn client_update_btn" id="submit_button" type="submit">Update</button>
            </div>
        </form>
    </section>

    <div class="ps-form__content client-leads">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="ps-block__content">
                    <header class="header--dashboard">
                        <div class="header__left">
                            <h3>Leads</h3>
                        </div>
                        <div class="header__center">
                        </div>
                        <div class="header__right">
                            <a class="ps-btn success" href="{{ url('/client/lead', $client->id) }}"><i class="icon icon-plus mr-2"></i>New Lead</a>
                        </div>
                    </header>
                    <section class="ps-items-listing">
                        @if(!empty($client->leads_count))
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
                                            @foreach($client->leads_count as $lead)
                                            <tr>
                                                <td><input type="checkbox" name="checkbox[]" value="{{$lead->id}}"></td>
                                                <td>
                                                    @if(!empty($lead->client))
                                                    <a style="color: #fcb800;" href="{{url('client/edit',$lead->client_id)}}">
                                                        <div class="title-flag">
                                                            <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">
                                                            <strong>{{ $lead->client->name }}</strong> </a>
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    </div>
                                                    <i class="fa fa-th" aria-hidden="true"></i>
                                                    {{ $lead->client->leads_count()->count() }} Lead
                                                    @else
                                                    Nothing
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $lead->leadAgent->name }}<br>
                                                    <span style="color: grey;">{{ $lead->leadAgent->designation->name }} - {{ $lead->leadAgent->department->name }}</span>
                                                </td>
                                                <td>
                                                    <a style="color: #fcb800;" href="{{url('lead/edit',$lead->id)}}">
                                                        {{ $lead->id }}
                                                    </a>
                                                    <br>
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
                                                    {{$lead->taskStatus->subtype}}<br>
                                                    @if($note != '')
                                                    {{$lead->taskStatus->note}}<br>
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
                                                    {{$lead->item->name}}<br>
                                                    Buy Primar
                                                @else
                                                    <br>Unit Not Selected
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
                                                    <!--                                <a rel="tooltip" href="{{url('share-lead',$lead->id)}}" data-original-title="" title="">
                                                                                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                                                                                    </a>-->
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                        No Record Found!
                        @endif
                    </section>
                </div>
            </div>
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

</div>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.js"></script>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    {{-- End JQuery Phone Plugin Cdn --}}
    <script>
        var input = document.querySelector(".phone"),
        errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"],
        result = document.querySelector("#result");
        window.addEventListener("load", function ()
        {
            errorMsg = document.querySelector("#error-msg");
            function getIp(callback)
            {
                fetch('https://ipinfo.io', { headers: { 'Accept': 'application/json' }})
                .then((resp) => resp.json())
                .catch(() => {
                    return {
                    country: '',
                    };
                })
                .then((resp) => callback(resp.country));
            }
            var iti = window.intlTelInput(input,
            {
                // allowDropdown: false,
                // dropdownContainer: document.body,
                // excludeCountries: ["us"],
                hiddenInput: "full_number",
                nationalMode: true,
                // formatOnDisplay: true,
                formatOnDisplay: false, // =======here is the issue if true create issue (with this number +60189181388) if false then work ok=======
                separateDialCode: true,
                autoHideDialCode: true,
                autoPlaceholder: "aggressive" ,
                initialCountry: "pk",
                placeholderNumberType: "MOBILE",
                preferredCountries: ['il','ge'],
                geoIpLookup: getIp,
                // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js",
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
            });
            input.addEventListener('keyup', formatIntlTelInput);
            input.addEventListener('change', formatIntlTelInput);
            function formatIntlTelInput()
            {
                if (typeof intlTelInputUtils !== 'undefined')    // utils are lazy loaded, so must check
                {
                    var currentText = iti.getNumber(intlTelInputUtils.numberFormat.E164);
                    if (typeof currentText === 'string')    // sometimes the currentText is an object :)
                    {
                        iti.setNumber(currentText); // will autoformat because of formatOnDisplay=true
                    }
                }
            }
            input.addEventListener('keyup', function ()
            {
                reset();
                if (input.value.trim()) {
                    if (iti.isValidNumber())
                    {
                        $(input).addClass('form-control is-valid');
                    }
                    else
                    {
                        $(input).addClass('form-control is-invalid');
                        var errorCode = iti.getValidationError();
                        errorMsg.innerHTML = errorMap[errorCode];
                        $(errorMsg).show();
                    }
                }
            });
            input.addEventListener('change', reset);
            input.addEventListener('keyup', reset);
            var reset = function ()
            {
                $(input).removeClass('form-control is-invalid');
                errorMsg.innerHTML = "";
                $(errorMsg).hide();
            };
            ////////////// testing - start //////////////
            input.addEventListener('keyup', function(e)
            {
                e.preventDefault();
                var num = iti.getNumber(),
                valid = iti.isValidNumber();
                if(valid==true)
                {
                    result.textContent = "Number: " + num + ", Correct ";
                }
                else
                {
                    result.textContent = "Number: " + num + ", Wrong ";
                }
                // result.textContent = "Number: " + num + ", valid: " + valid;
            }, false);
            input.addEventListener("focus", function()
            {
                result.textContent = "";
            }, false);
            $(input).on("focusout", function(e, countryData)
            {
                var intlNumber = iti.getNumber();
                console.log(intlNumber);
                // document.getElementById("country_code").innerHTML = intlNumber;
                // alert(intlNumber);
            });
            ////////////// testing - end //////////////
        });
        //-----------------------only-phone-number-input code (with +)-------------------------------start-------//
        function isPhoneNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
        //-----------------------only-phone-number-input code (with +)-------------------------------end-------//
    </script>
    {{-- End Internation Telephone JQuery Plugin code --}}



<script>

    $(document).ready(function ()
    {
        $('.mobilediv').css('display', 'block');
        $('.ptcldiv').css('display', 'none');
        $(".ptclphone").val('');
        $(".phone").prop('required', true);
        $(".ptclphone").prop('required', false);

        $(document).on('click', '.phonestatus', function ()
        {
            var phonenumber = $(this).val();
            if (phonenumber == 0){
                $('.mobilediv').css('display', 'none');
                $('.ptcldiv').css('display', 'block');
                $(".phone").prop('required', false);
                $(".ptclphone").prop('required', true);
                $(".phone").val('');
                button.disabled = true;
            }else{
                $('.mobilediv').css('display', 'block');
                $('.ptcldiv').css('display', 'none');
                $(".ptclphone").val('');
                $(".phone").prop('required', true);
                $(".ptclphone").prop('required', false);
                button.disabled = true;
            }
        });

        $(document).on('change', '.phonestatus', function ()
        {
            $("#resu").html('');
        });

        $(document).on('input', '.search_number', function () {

            var token = "{{csrf_token()}}";
            var search_phone = $('#leadphone').val();
            var search_ptcl= $('#leadptcl').val();
            var client_id= $(this).attr('client-id');
            $("#resu").html('');

            if(search_phone != '' && search_phone.toString().length >= 8){
                check_number(search_phone , client_id , token);
            }if(search_ptcl != '' && search_ptcl.toString().length >= 8){
                check_number(search_ptcl , client_id , token);
            }
        });

        function check_number(number, client_id ,token)
        {
            $.ajax({
                type: "POST",
                url: "{{route('check-edit-user')}}",
                data: {
                    'phone': number,
                    'client_id': client_id,
                    '_token': token
                },
                success: function (data)
                {
                    $("#resu").html(data);
                }
            });
        }
    });

    function check_client()
    {
        var exist=$("span").hasClass( "client_exist" );
        if(exist==true){
            return false;
        }else{
            return true;
        }
    }

    $(document).on('click', '.client_update_btn', function ()
    {
        var exist= $("input").hasClass( "is-invalid" );
        if(exist){
            return false;
        }
    });

</script>


{{-- =================================== --}}

{{-- Dynamically Add Multiple International Phone fields --}}
<script>

    // Define a function to be reused
    function handleClick()
    {
        // alert('ok');

        var phoneFieldsContainer = document.getElementById("dynamicphoneFields");

        // Create Row div
        var rowDiv = document.createElement("div");
        rowDiv.classList.add("row");

        // Create the First col-11 div
        var col1Div = document.createElement("div");
        col1Div.classList.add("col-sm-11" , "mb-2");

        // Create the second col-1 div
        var col2Div = document.createElement("div");
        col2Div.classList.add("col-sm-1", "pt-2");


        // Create a new phone input field element
        var phoneInput = document.createElement("input");

        var counter = phoneFieldsContainer.children.length + 1;

        // // Set the attributes for the input element
        phoneInput.setAttribute("type", "text");
        phoneInput.setAttribute("maxlength", "15");
        phoneInput.setAttribute("required", "true");
        phoneInput.setAttribute("name", "international_phone_dynamic[]");
        phoneInput.setAttribute("class", "form-control");
        phoneInput.setAttribute("placeholder", "3012345678");
        phoneInput.setAttribute("id", "search_international_number"+counter);
        phoneInput.setAttribute("counter", counter);


        // You can also Add the Class like this
        // phoneInput.classList.add("phone");
        phoneInput.classList.add("search_international_multiphone_number");

        // style add like this
        // phoneInput.style.marginBottom = "10px";
        phoneInput.style.width = "100%";

        // Create the button
        var button = document.createElement("button");
        button.type = "button";
        button.classList.add("btn", "remove-international-input-field"); // Add the necessary classes
        button.innerHTML = '<i class="fa fa-trash fa-2x fa-solid" aria-hidden="true"></i>'; // Set the button content

        // Create a new error message element
        var errorMsg = document.createElement("div");
        errorMsg.id = "error-msg" + counter;

        // Create a new result element
        var result = document.createElement("div");
        result.id = "result" + counter;

        // Create a new error message element
        var client_div = document.createElement("div");
        client_div.id = "client-result" + counter;


        // Add row into main div
        phoneFieldsContainer.appendChild(rowDiv);

        // Add both col into row div
        rowDiv.appendChild(col1Div);
        rowDiv.appendChild(col2Div);

        //Also add error and result div into row div
        rowDiv.appendChild(errorMsg);
        rowDiv.appendChild(result);
        rowDiv.appendChild(client_div);

        // add input field into first div
        col1Div.appendChild(phoneInput); // Append the Input to the First column div

        // add buttton into second div
        col2Div.appendChild(button);     // Append the button to the second column div


        function getIp(callback)
        {
            fetch('https://ipinfo.io', { headers: { 'Accept': 'application/json' }})
            .then((resp) => resp.json())
            .catch(() => {
                return {
                country: '',
                };
            })
            .then((resp) => callback(resp.country));
        }

        // Initialize the new phone input element
        var iti = window.intlTelInput(phoneInput,
        {
            hiddenInput: "international_full_number"+counter,
            nationalMode: true,
            formatOnDisplay: true,
            separateDialCode: true,
            autoHideDialCode: true,
            autoPlaceholder: "aggressive" ,
            initialCountry: "pk",
            placeholderNumberType: "MOBILE",
            preferredCountries: ['il','ge'],
            geoIpLookup: getIp,
            // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js",
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
        });
        // Add event listeners for the new phone input element
        phoneInput.addEventListener('keyup', formatIntlTelInput);
        phoneInput.addEventListener('change', formatIntlTelInput);

        function formatIntlTelInput() {
            if (typeof intlTelInputUtils !== 'undefined') {
                var currentText = iti.getNumber(intlTelInputUtils.numberFormat.E164);
                if (typeof currentText === 'string') {
                    iti.setNumber(currentText);
                }
            }
        }

        phoneInput.addEventListener('keyup', function ()
        {
            reset();
            if (phoneInput.value.trim()) {
                if (iti.isValidNumber())
                {
                    $(phoneInput).addClass('form-control is-valid');
                }
                else
                {
                    $(phoneInput).addClass('form-control is-invalid');
                    var errorCode = iti.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    $(errorMsg).show();
                }
            }
        });
        phoneInput.addEventListener('change', reset);
        phoneInput.addEventListener('keyup', reset);
        var reset = function ()
        {
            $(phoneInput).removeClass('form-control is-invalid');
            errorMsg.innerHTML = "";
            $(errorMsg).hide();
        };
        ////////////// testing - start //////////////
        phoneInput.addEventListener('keyup', function(e)
        {
            e.preventDefault();
            var num = iti.getNumber(),
            valid = iti.isValidNumber();
            if(valid==true)
            {
                result.textContent = "Number: " + num + ", Correct ";
            }
            else
            {
                result.textContent = "Number: " + num + ", Wrong ";
            }
            // result.textContent = "Number: " + num + ", valid: " + valid;
        }, false);
        phoneInput.addEventListener("focus", function()
        {
            result.textContent = "";
        }, false);
        $(phoneInput).on("focusout", function(e, countryData)
        {
            var intlNumber = iti.getNumber();
            console.log(intlNumber);
            // document.getElementById("country_code").innerHTML = intlNumber;
            // alert(intlNumber);
        });

    }

    // Add the click event listener to multiple elements with different IDs
    document.getElementById("add-dynamic-international-phone1").addEventListener("click", handleClick);
    document.getElementById("add-dynamic-international-phone2").addEventListener("click", handleClick);
    // Add more elements as needed


    $(document).ready(function()
    {
        function initializeInputField(phoneInput ,counter , result , errorMsg)
        {
            function getIp(callback)
            {
                fetch('https://ipinfo.io', { headers: { 'Accept': 'application/json' }})
                .then((resp) => resp.json())
                .catch(() => {
                    return {
                    country: '',
                    };
                })
                .then((resp) => callback(resp.country));
            }

            // Initialize the new phone input element
            var iti = window.intlTelInput(phoneInput,
            {
                hiddenInput: "edit_international_full_number"+counter,
                nationalMode: true,
                formatOnDisplay: true,
                separateDialCode: true,
                autoHideDialCode: true,
                autoPlaceholder: "aggressive" ,
                initialCountry: "pk",
                placeholderNumberType: "MOBILE",
                preferredCountries: ['il','ge'],
                geoIpLookup: getIp,
                // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js",
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
            });
            // Add event listeners for the new phone input element
            phoneInput.addEventListener('keyup', formatIntlTelInput);
            phoneInput.addEventListener('change', formatIntlTelInput);

            function formatIntlTelInput() {
                if (typeof intlTelInputUtils !== 'undefined') {
                    var currentText = iti.getNumber(intlTelInputUtils.numberFormat.E164);
                    if (typeof currentText === 'string') {
                        iti.setNumber(currentText);
                    }
                }
            }

            phoneInput.addEventListener('keyup', function ()
            {
                reset();
                if (phoneInput.value.trim()) {
                    if (iti.isValidNumber())
                    {
                        $(phoneInput).addClass('form-control is-valid');
                    }
                    else
                    {
                        $(phoneInput).addClass('form-control is-invalid');
                        var errorCode = iti.getValidationError();
                        errorMsg.innerHTML = errorMap[errorCode];
                        $(errorMsg).show();
                    }
                }
            });
            phoneInput.addEventListener('change', reset);
            phoneInput.addEventListener('keyup', reset);
            var reset = function ()
            {
                $(phoneInput).removeClass('form-control is-invalid');
                errorMsg.innerHTML = "";
                $(errorMsg).hide();
            };
            ////////////// testing - start //////////////
            phoneInput.addEventListener('keyup', function(e)
            {
                e.preventDefault();
                var num = iti.getNumber(),
                valid = iti.isValidNumber();
                if(valid==true)
                {
                    result.textContent = "Number: " + num + ", Correct ";
                }
                else
                {
                    result.textContent = "Number: " + num + ", Wrong ";
                }
                // result.textContent = "Number: " + num + ", valid: " + valid;
            }, false);
            phoneInput.addEventListener("focus", function()
            {
                result.textContent = "";
            }, false);
            $(phoneInput).on("focusout", function(e, countryData)
            {
                var intlNumber = iti.getNumber();
                console.log(intlNumber);
                // document.getElementById("country_code").innerHTML = intlNumber;
                // alert(intlNumber);
            });

        }

        var phoneInputs = document.querySelectorAll('.search_edit_international_multiphone_number');
        var counter = document.querySelectorAll('.counter');

        phoneInputs.forEach(function(input, counter)
        {
            var counter=counter+1;
            var result = document.querySelector("#result"+counter);
            var errorMsg = document.querySelector("#error-msg"+counter);

            initializeInputField(input ,counter , result , errorMsg);
        });
    });

</script>

{{-- =================================== --}}


@endsection
