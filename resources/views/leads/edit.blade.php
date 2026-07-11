@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])
@section('content')
@include('leads.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Edit Lead</h3>
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

    <section class="ps-new-item">
        <form method="post" action="{{ url('lead/update', $lead) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data" onSubmit="return check_client()">
            @csrf
            @method('put')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Client Info</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Name<sup>*</sup>
                                    </label>
                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name',$lead->client->name) }}"  @if(Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14) readonly @endif/>
                                    @if ($errors->has('name'))
                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Email<sup>*</sup>
                                    </label>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email',$lead->client->email) }}"  @if(Auth::user()->role != 1  && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14) readonly @endif />
                                    @if ($errors->has('email'))
                                    <span id="email-error" class="error text-danger" for="input-email">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                {{-- <div class="form-group">
                                    <label>Mobile
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Mobile" name="phone" value="{{ old('phone',$lead->client->phone) }}"  @if(Auth::user()->role != 1) readonly @endif/>
                                </div> --}}


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
                            <input <?php if (Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14){ ?> readonly <?php } ?> class="form-control phone search_number" maxlength="15"  name="phone"  id="leadphone" type="text" required="" client-id="{{ $lead->client_id }}" value="{{ old('phone',$lead->client->phone) }}" />
                            <span id="error-msg" class="hide"></span>
                            <p id="result"></p>
                        </div>
                    </div>
                    <div class="col-sm-1 form-group" >
                        {{-- <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success add-dynamic-phone"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button> --}}
                        <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success" id="add-dynamic-international-phone1"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button>
                    </div>
                </div>

                <input type="hidden" name="orignal_phone_number_id" value="{{ $phone->id }}"/>


                <div class="ptcldiv form-group mb-0" style="display: @if(old('phonenumber')=='1' || old('phonenumber')=='') none @else block @endif;">
                    <label>Ptcl<sup>*</sup></label>
                    <div class="col-sm-11 ">
                        <div class="form-group mb-0">
                            <input <?php if (Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14){ ?> readonly="" <?php } ?> class="form-control ptclphone search_number" name="ptclphone" placeholder="Ptcl Phone Number"  id="leadptcl" type="text" required="" client-id="{{ $lead->client_id }}" value="{{ old('ptclphone',$lead->client->phone) }}" />
                        </div>
                    </div>
                    <div class="col-sm-1 form-group" >
                        {{-- <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success add-dynamic-phone"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button> --}}
                        <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success" id="add-dynamic-international-phone2"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button>
                    </div>
                </div>

                <div id="resu"  style="margin-bottom: 20px;"></div>


                {{-- Multi Phone --}}
                <style>
                    .white_hover:hover{
                        color:white !important;
                    }
                </style>

                <div class="form-group">
                    <div id="dynamicphoneFields">

                        @foreach ($numbers as $key => $record)

                            <div class="row">
                                <div class="col-11 mb-2">
                                    <input type="text" maxlength="15" required="true" name="edit_international_phone_dynamic[]" class="form-control search_edit_international_multiphone_number" placeholder="301 2345678" id="search_edit_international_number{{ $key+1 }}" counter="{{ $key+1 }}" client_id={{ $lead->client_id }} @if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) style="width: 100%; padding-left: 83px;" @else style="width: 68%;"  @endif value="{{ $record->number }}"  <?php if (Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14){ ?>  readonly <?php } ?> >
                                    <input type="hidden" name="edit_phone_number_ids[{{$key+1}}]" value="{{ $record->id }}"/>
                                </div>

                                @if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                                    <div class="col-sm-1 pt-2">
                                        <button type="button" class="btn remove-edit-international-input-field" id="remove-edit-international-input-field-{{ $key+1 }}" client_id={{ $lead->client_id }} number_id="{{ $record->id }}"><i class="fa fa-trash fa-2x fa-solid" aria-hidden="true"></i></button>
                                    </div>
                                @endif

                                <div id="error-msg{{ $key+1 }}"></div>
                                <div id="result{{ $key+1 }}"></div>
                                <div id="client-result{{ $key+1 }}"></div>

                            </div>

                        @endforeach
                    </div>
                </div>

            {{--===================Phone=================== --}}








                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Lead Info</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Select Project</label>
                                    <select class="form-control" title="Status" name="project_id" @if(Auth::user()->role != 1) disabled @endif>
                                        <option value="0">Select Item</option>
                                        @if(!empty($projects))
                                        @foreach($projects as $project)
                                        <option value="{{ $project->id }}" @if(old('project_id') == $project->id) selected @endif <?php if($project->id == $lead->project_id){ echo 'selected'; } ?>>{{$project->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Interested In
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="category_id" @if(Auth::user()->role != 1) disabled @endif>
                                        <option value="0">Select Item</option>
                                        @if(!empty($categories))
                                        @foreach($categories as $category)
                                        <option <?php if($category->id == $lead->category_id){ echo 'selected'; } ?> value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Assign To
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="user_id" required="true" aria-required="true" @if(Auth::user()->role != 1) disabled @endif>
                                        <option value="0">Select Agent</option>
                                        @if(!empty($agents))
                                        @foreach($agents as $user)
                                        <option <?php if($lead->user_id == $user->id){ echo 'selected'; } ?> value="{{ $user->id }}" @if(old('user_id') == $user->id) selected @endif>{{$user->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                               <div class="form-group">
                                    <label>Lead Priority
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="priority" @if(Auth::user()->role != 1) disabled @endif>
                                        <option value="0">Choose Priority</option>
                                        @if(!empty($priority))
                                        @foreach($priority as $priorityy)
                                        <option value="{{ $priorityy->id }}"  @if(old('priority') == $priorityy->id) selected @endif <?php if($lead->priority == $priorityy->id){ echo 'selected'; } ?>>{{$priorityy->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                               <div class="form-group">
                                    <label>Lead Status
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="status_id" @if(Auth::user()->role != 1) disabled @endif>
                                        <option value="0">Choose Status</option>
                                        @if(!empty($lead_status))
                                        @foreach($lead_status as $lead_status)
                                        <option value="{{ $lead_status->id }}" @if(old('status_id') == $lead_status->id) selected @endif <?php if($lead->status_id == $lead_status->id){ echo 'selected'; } ?>>{{$lead_status->type}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                               @if(Auth::user()->department_id == 2 )
                               <div class="form-group">
                                    <label>Token Status
                                    </label>
                                   <select class="ps-select form-control" title="Status" name="status_id" required="">
                                        <option value=""  {{ old('status_id') == ""  ? "selected" : "" }}>--Token Status--</option>
                                        <option value="1" {{ old('status_id') == "1" ? "selected" : "" }}>Accepted</option>
                                        <option value="0" {{ old('status_id') == "0" ? "selected" : "" }}>Rejected</option>
                                    </select>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea id="summernote" rows="6" name="note" class="form-control">{{ old( 'note' ,$lead->note)}}</textarea>
                                </div>


                                @if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                                    @if($task)
                                        <div class="form-group">
                                            <label>Inventory Booked</label>
                                            <input type="text" class="form-control"  name="old_order_unit_id" value="{{ $task->unit_id }}" readonly/>
                                        </div>

                                        <div class="form-group">
                                            <label> Want to Change Inventory</label> <br>
                                            <input class="change_inventory" type="radio" name="change_inventory" data-plugin-ios-switch  value="0" style="height: auto !important;"  @if(old('change_inventory')=='0' || old('change_inventory')=='') checked @endif/> No
                                            <input class="change_inventory" type="radio" name="change_inventory" data-plugin-ios-switch  value="1" style="height: auto !important;"  @if(old('change_inventory')=='1') checked @endif/> Yes
                                        </div>

                                        <div class="change_inventory_options" style="display: @if(old('change_inventory')=='0') none @else block @endif;">

                                            <div class="form-group select_type">
                                                <label>Select Type<sup>*</sup></label>
                                                <select name="type_id" id="change_type_id" class="form-control select-picker" data-size="8">
                                                    <option value="">--Select Type--</option>';
                                                    @foreach ($categories as $category)
                                                        <option  {{-- @if($category->id==$lead->category_id)@selected(true)@endif --}} value="{{ $category->id }}"> {{ $category->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div id="floor_items"></div>
                                            <div id="unit_items"></div>

                                        </div>
                                    @endif
                                @endif

                            </div>
                        </figure>
                        <input type="hidden" name="added_by" value="{{Auth::user()->id}}">
                        <input type="hidden" name="last_updated_by" value="{{Auth::user()->id}}">
                        <input type="hidden" id="client_id" name="client_id" value="{{$lead->client_id}}">

                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                @if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                    <a class="ps-btn ps-btn--black" href="{{url('all/leads/'.$lead->user_id)}}">Go Back >></a>
                @endif
                <a class="ps-btn ps-btn--black" href="{{url('leads')}}">Cancel</a>
                <button class="ps-btn button" id="lead_update_btn" type="submit">Update</button>
            </div>
        </form>
    </section>
</div>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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


    $(document).on('click', '#lead_update_btn', function ()
    {
        var account = $('.accountstatus:checked').val();

        var exist= $("input").hasClass( "is-invalid" );
        if(exist){
            return false;
        }
    });


</script>








{{-- =================================== --}}

{{-- Dynamically Add Multiple International Phone fields --}}
<script>

    // document.getElementById("add-dynamic-international-phone").addEventListener("click", function()
    // {
    //     var phoneFieldsContainer = document.getElementById("dynamicphoneFields");

    //     // Create Row div
    //     var rowDiv = document.createElement("div");
    //     rowDiv.classList.add("row");

    //     // Create the First col-11 div
    //     var col1Div = document.createElement("div");
    //     col1Div.classList.add("col-11" , "mb-2");

    //     // Create the second col-1 div
    //     var col2Div = document.createElement("div");
    //     col2Div.classList.add("col-sm-1", "pt-2");


    //     // Create a new phone input field element
    //     var phoneInput = document.createElement("input");

    //     var counter = phoneFieldsContainer.children.length + 1;

    //     // // Set the attributes for the input element
    //     phoneInput.setAttribute("type", "text");
    //     phoneInput.setAttribute("maxlength", "15");
    //     phoneInput.setAttribute("required", "true");
    //     phoneInput.setAttribute("name", "international_phone_dynamic[]");
    //     phoneInput.setAttribute("class", "form-control");
    //     phoneInput.setAttribute("placeholder", "3012345678");
    //     phoneInput.setAttribute("id", "search_international_number"+counter);
    //     phoneInput.setAttribute("counter", counter);


    //     // You can also Add the Class like this
    //     // phoneInput.classList.add("phone");
    //     phoneInput.classList.add("search_international_multiphone_number");

    //     // style add like this
    //     // phoneInput.style.marginBottom = "10px";
    //     phoneInput.style.width = "100%";

    //     // Create the button
    //     var button = document.createElement("button");
    //     button.type = "button";
    //     button.classList.add("btn", "remove-international-input-field"); // Add the necessary classes
    //     button.innerHTML = '<i class="fa fa-trash fa-2x fa-solid" aria-hidden="true"></i>'; // Set the button content

    //     // Create a new error message element
    //     var errorMsg = document.createElement("div");
    //     errorMsg.id = "error-msg" + counter;

    //     // Create a new result element
    //     var result = document.createElement("div");
    //     result.id = "result" + counter;

    //     // Create a new error message element
    //     var client_div = document.createElement("div");
    //     client_div.id = "client-result" + counter;


    //     // Add row into main div
    //     phoneFieldsContainer.appendChild(rowDiv);

    //     // Add both col into row div
    //     rowDiv.appendChild(col1Div);
    //     rowDiv.appendChild(col2Div);

    //     //Also add error and result div into row div
    //     rowDiv.appendChild(errorMsg);
    //     rowDiv.appendChild(result);
    //     rowDiv.appendChild(client_div);

    //     // add input field into first div
    //     col1Div.appendChild(phoneInput); // Append the Input to the First column div

    //     // add buttton into second div
    //     col2Div.appendChild(button);     // Append the button to the second column div


    //     function getIp(callback)
    //     {
    //         fetch('https://ipinfo.io', { headers: { 'Accept': 'application/json' }})
    //         .then((resp) => resp.json())
    //         .catch(() => {
    //             return {
    //             country: '',
    //             };
    //         })
    //         .then((resp) => callback(resp.country));
    //     }

    //     // Initialize the new phone input element
    //     var iti = window.intlTelInput(phoneInput,
    //     {
    //         hiddenInput: "international_full_number"+counter,
    //         nationalMode: true,
    //         formatOnDisplay: true,
    //         separateDialCode: true,
    //         autoHideDialCode: true,
    //         autoPlaceholder: "aggressive" ,
    //         initialCountry: "pk",
    //         placeholderNumberType: "MOBILE",
    //         preferredCountries: ['il','ge'],
    //         geoIpLookup: getIp,
    //         // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js",
    //         utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
    //     });
    //     // Add event listeners for the new phone input element
    //     phoneInput.addEventListener('keyup', formatIntlTelInput);
    //     phoneInput.addEventListener('change', formatIntlTelInput);

    //     function formatIntlTelInput() {
    //         if (typeof intlTelInputUtils !== 'undefined') {
    //             var currentText = iti.getNumber(intlTelInputUtils.numberFormat.E164);
    //             if (typeof currentText === 'string') {
    //                 iti.setNumber(currentText);
    //             }
    //         }
    //     }

    //     phoneInput.addEventListener('keyup', function ()
    //     {
    //         reset();
    //         if (phoneInput.value.trim()) {
    //             if (iti.isValidNumber())
    //             {
    //                 $(phoneInput).addClass('form-control is-valid');
    //             }
    //             else
    //             {
    //                 $(phoneInput).addClass('form-control is-invalid');
    //                 var errorCode = iti.getValidationError();
    //                 errorMsg.innerHTML = errorMap[errorCode];
    //                 $(errorMsg).show();
    //             }
    //         }
    //     });
    //     phoneInput.addEventListener('change', reset);
    //     phoneInput.addEventListener('keyup', reset);
    //     var reset = function ()
    //     {
    //         $(phoneInput).removeClass('form-control is-invalid');
    //         errorMsg.innerHTML = "";
    //         $(errorMsg).hide();
    //     };
    //     ////////////// testing - start //////////////
    //     phoneInput.addEventListener('keyup', function(e)
    //     {
    //         e.preventDefault();
    //         var num = iti.getNumber(),
    //         valid = iti.isValidNumber();
    //         if(valid==true)
    //         {
    //             result.textContent = "Number: " + num + ", Correct ";
    //         }
    //         else
    //         {
    //             result.textContent = "Number: " + num + ", Wrong ";
    //         }
    //         // result.textContent = "Number: " + num + ", valid: " + valid;
    //     }, false);
    //     phoneInput.addEventListener("focus", function()
    //     {
    //         result.textContent = "";
    //     }, false);
    //     $(phoneInput).on("focusout", function(e, countryData)
    //     {
    //         var intlNumber = iti.getNumber();
    //         console.log(intlNumber);
    //         // document.getElementById("country_code").innerHTML = intlNumber;
    //         // alert(intlNumber);
    //     });
    // });

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
        col1Div.classList.add("col-11" , "mb-2");

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




    // $(document).on('click', '.remove-international-input-field', function ()
    // {
    //     $(this).parent().parent('div').remove();
    // });

    // $(document).on('input', '.search_international_multiphone_number', function ()
    // {
    //     var inputId = $(this).attr('id');
    //     var id_counter = $(this).attr('counter');

    //     if(inputId !='' && id_counter != '')
    //     {
    //         var token = $('meta[name="csrf-token"]').attr('content');
    //         // var token = "{{csrf_token()}}";
    //         var search_multiphone = $('#'+inputId).val();
    //     }

    //     if(search_multiphone != '' && search_multiphone.toString().length >= 8){
    //         check_multi_international_number(search_multiphone , id_counter , token);
    //     }else{
    //         $("#client-result"+id_counter).html('');
    //     }
    // });

    // function check_multi_international_number(number, id_counter, token)
    // {
    //     var base_url = $('#base_url').val();

    //     $.ajax({
    //         type: "POST",
    //         url: base_url +'/check/multi-phone',
    //         data: {
    //             'phone': number,
    //             'id_counter': id_counter,
    //             '_token': token
    //         },
    //         success: function (response)
    //         {
    //             // console.log(response);
    //             $("#client-result"+response.id_counter).html(response.html)
    //         }
    //     });
    // }



    // ============on load edit international phone=============

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


    // $(document).on('input', '.search_edit_international_multiphone_number', function ()
    // {
    //     var inputId = $(this).attr('id');
    //     var id_counter = $(this).attr('counter');
    //     var client_id = $(this).attr('client_id');

    //     if(inputId !='' && id_counter != '' && client_id !='')
    //     {
    //         var token = $('meta[name="csrf-token"]').attr('content');
    //         // var token = "{{csrf_token()}}";
    //         var search_multiphone = $('#'+inputId).val();
    //         // alert(search_multiphone);
    //     }

    //     if(search_multiphone != '' && search_multiphone.toString().length >= 8){
    //         check_edit_multi_international_number(search_multiphone , id_counter, client_id , token);
    //     }else{
    //         $("#client-result"+id_counter).html('');
    //     }
    // });

    // function check_edit_multi_international_number(number, id_counter, client_id , token)
    // {
    //     var base_url = $('#base_url').val();

    //     $.ajax({
    //         type: "POST",
    //         url: base_url +'/check/multi-phone/edit',
    //         data: {
    //             'phone': number,
    //             'id_counter': id_counter,
    //             'client_id': client_id,
    //             '_token': token
    //         },
    //         success: function (response)
    //         {
    //             $("#client-result"+response.id_counter).html(response.html)
    //         }
    //     });
    // }


    // ============on load edit international phone=============










</script>

{{-- ============================================ --}}



@endsection
