@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])
@section('content')
@include('leads.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Lead</h3>
            <p>Add new Lead</p>
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
    <section class="ps-new-item">
        <form method="post" action="{{ url('lead/store') }}"  class="form-horizontal" enctype="multipart/form-data" id="leadForm" onSubmit="return check_client()">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Client Info</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Account</label> <br>
                                    <input class="accountstatus" type="radio" name="account" data-plugin-ios-switch value="1" style="height: auto !important;"  @if( old('account') == '1' || old('account') == '') checked @endif/> New Client
                                    <input class="accountstatus" type="radio" name="account" data-plugin-ios-switch value="0" style="height: auto !important;"  @if( old('account') == '0') checked @endif/> Existing Client
                                </div>
                                <div class="accountdiv" style="display: @if(old('account') == '0') none @else block @endif;">
                                    <div class="form-group">
                                        <label>Name<sup>*</sup></label>
                                        <input class="form-control name" id="leadname" name="name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name')}}" required=""/>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input class="form-control" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}"/>
                                    </div>
                                    <div class="form-group" id="sourceid">
                                        <label>Source<sup>*</sup></label>
                                        <select class="form-control source" title="Status" name="source_id" required="">
                                            <option value="">--Choose Source--</option>
                                            @foreach($sources as $source)
                                            <option value="{{$source->id}}" @if(old('source_id') == $source->id) selected @endif>{{$source->type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group" style="display: none;" id="afflilate_id">
                                    </div>
                                    {{-- <div class="form-group telephone">
                                        <label>Mobile<sup>*</sup> </label>
                                        <select name="countryCode" class="form-control" >
                                            <option value="+92" selected="">Pakistan (+92)</option>
                                            <option value="+44">UK (+44)</option>
                                            <option value="+1">USA (+1)</option>
                                            <option value="+974">Qatar (+974)</option>
                                            <option value="+966">Saudi Arabia (+966)</option>
                                            <option value="+90">Turkey (+90)</option>
                                            <option value="+971">United Arab Emirates (+971)</option>
                                        </select>
                                        <input class="form-control phone" maxlength="10"  pattern="[3-3]{1}[0-9]{9}" title="Please start from 3 & enter exactly 10 digits only" name="phone"  id="leadphone" type="text" placeholder="3001234567" required=""  value="{{ old('phone') }}"/>
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
                                    <input class="form-control phone search_number" maxlength="15"  name="phone"  id="leadphone" type="text" placeholder="3001234567" required=""  value="{{ old('phone') }}"/>
                                    <span id="error-msg" class="hide"></span>
                                    <p id="result"></p>
                                </div>
                            </div>
                            <div class="col-sm-1 form-group" >
                                {{-- <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success add-dynamic-phone"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button> --}}
                                <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success" id="add-dynamic-international-phone1"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button>
                            </div>
                        </div>

                        <div class="ptcldiv form-group mb-0" style="display: @if(old('phonenumber')=='1' || old('phonenumber')=='') none @else block @endif;">
                            <label>Ptcl<sup>*</sup></label>
                            <div class="col-sm-11 ">
                                <div class="form-group mb-0">
                                    <input class="form-control ptclphone search_number" type="text" id="leadptcl" placeholder="Ptcl Phone Number" name="ptclphone" required="" value="{{ old('ptclphone') }}" />
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

                        {{-- <div class="form-group" id="dynamicAddRemove" >
                        </div> --}}


                        <div class="form-group row">
                            <div id="dynamicphoneFields">

                            </div>
                        </div>

                        {{-- Multi Phone --}}

                    {{--===================Phone=================== --}}




                                    {{-- <div class="form-group telephone">
                                        <label>TelePhone 1 </label>
                                        <select name="countryCode1" class="form-control">
                                            <option value="+92" selected="">Pakistan (+92)</option>
                                            <option value="+44">UK (+44)</option>
                                            <option value="+1">USA (+1)</option>
                                            <option value="+974">Qatar (+974)</option>
                                            <option value="+966">Saudi Arabia (+966)</option>
                                            <option value="+90">Turkey (+90)</option>
                                            <option value="+971">United Arab Emirates (+971)</option>
                                        </select>
                                        <input class="form-control search_number" id="telephone" type="text" placeholder="TelePhone 1" name="telephone" value="{{ old('telephone') }}" />
                                    </div>
                                    <div class="form-group telephone">
                                        <label style="width: 100%;">TelePhone 2 </label>
                                        <select name="countryCode2" class="form-control">
                                            <option value="+92" selected="">Pakistan (+92)</option>
                                            <option value="+44">UK (+44)</option>
                                            <option value="+1">USA (+1)</option>
                                            <option value="+974">Qatar (+974)</option>
                                            <option value="+966">Saudi Arabia (+966)</option>
                                            <option value="+90">Turkey (+90)</option>
                                            <option value="+971">United Arab Emirates (+971)</option>
                                        </select>
                                        <input class="form-control search_number" id="telephone1" type="text" placeholder="TelePhone 2" name="telephone1" value="{{ old('telephone1') }}" />
                                    </div> --}}




                                </div>
                                <div class="existing_client" style="display: @if(old('account') == '1' || old('account') == '') none @else block @endif;">
                                    <div class="form-group  account-class">
                                        <label>Search
                                        </label>
                                        <input type="text" name="client" placeholder="Cell, Email or Name" class="form-control client"><i class="fa fa-search" id="search_clients" aria-hidden="true"></i>
                                    </div>
                                    <div class="form-group" id="show_clients">
                                    </div>
                                </div>
                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Lead Info</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Select Project
                                    </label>
                                    <select class="form-control" title="Status" name="project_id">
                                        <option value="0">Select Item</option>
                                        @if(!empty($projects))
                                        @foreach($projects as $project)
                                        <option value="{{$project->id}}" @if(old('project_id') == $project->id) selected @endif>{{$project->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Interested In
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="category_id">
                                        <option value="">Select Item</option>
                                        @if(!empty($categories))
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}" @if(old('category_id') == $category->id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <div class="form-group">
                                    <label>Lead Priority
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="priority">
                                        <option value="0">Choose Priority</option>
                                        @if(!empty($priority))
                                        @foreach($priority as $priorityy)
                                        <option value="{{$priorityy->id}}" @if(old('priority') == $priorityy->id) selected @endif>{{$priorityy->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea id="summernote" class="form-control" rows="6" name="note">{{old('note')}}</textarea>
                                </div>
                            </div>
                        </figure>
                        <input type="hidden" name="added_by" value="{{Auth::user()->id}}">
                        <input type="hidden" name="last_updated_by" value="{{Auth::user()->id}}">
                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('leads')}}">Cancel</a>
                {{-- <button class="ps-btn" id="lead_create" type="submit">Submit</button> --}}
                <button id="lead_create" class="btn btn-success button" type="submit" disabled="" >Submit</button>
            </div>
        </form>
        {{-- <div class="alert alert-info" ></div> --}}
    </section>



</div>
<script>
    let name = document.querySelector(".name");
    let phone = document.querySelector(".phone");
    let source = document.querySelector(".source");
    let button = document.querySelector(".button");
    let ptclphone = document.querySelector(".ptclphone");
    ptclphone.addEventListener("keyup", stateHandle);
    name.addEventListener("keyup", stateHandle);
    phone.addEventListener("keyup", stateHandle);
    source.addEventListener("keyup", stateHandle);
    ptclphone.addEventListener("change", stateHandle);
    name.addEventListener("change", stateHandle);
    phone.addEventListener("change", stateHandle);
    source.addEventListener("change", stateHandle);
    function stateHandle(){
        if (document.querySelector(".name").value != "" && document.querySelector(".source").value != "" && document.querySelector(".phone").value != ""){
            button.disabled = false;
        }
        else if(document.querySelector(".name").value != "" && document.querySelector(".source").value != "" && document.querySelector(".ptclphone").value != ""){
            button.disabled = false;
        }else{
            button.disabled = true;
        }
    }
    $('.mobilediv').css('display', 'block');
    $('.ptcldiv').css('display', 'none');
    $(".ptclphone").val('');
    $(".phone").prop('required', true);
    $(".ptclphone").prop('required', false);
    $(document).on('click', '.phonestatus', function (){
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
</script>
{{--Start JQuery Phone Plugin Cdn --}}
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

</script>

{{-- ============================================ --}}






<script>

    $(document).ready(function ()
    {
        $(document).on('change', '.phonestatus', function ()
        {
            $("#resu").html('');
        });

        $(document).on('input', '.search_number', function () {

            var token = "{{csrf_token()}}";
            var search_phone = $(this).val()
            // var search_phone = $('#leadphone').val();
            var search_ptcl= $('#leadptcl').val();

            $("#resu").html('');

            if(search_phone != '' && search_phone.toString().length >= 8){
                check_number(search_phone , token);
            }if(search_ptcl != '' && search_ptcl.toString().length >= 8){
                check_number(search_ptcl , token);
            }
        });

        function check_number(number, token)
        {
            $.ajax({
                type: "POST",
                url: "{{route('get.user')}}",
                data: {
                    'phone': number,
                    '_token': token
                },
                success: function (data) {
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
</script>



{{-- <script type="text/javascript">
    var i = 0;
    $(".add-dynamic-phone").click(function ()
    {
        ++i;
        $("#dynamicAddRemove").append(

            `<div class="row mb-3">
                <div class="col-sm-4" style="width: 30%;">
                    <select name="countryCode[${i}]" class="form-control" >
                        <option value="+92" selected="">Pakistan (+92)</option>
                        <option value="+44">UK (+44)</option>
                        <option value="+1">USA (+1)</option>
                        <option value="+974">Qatar (+974)</option>
                        <option value="+966">Saudi Arabia (+966)</option>
                        <option value="+90">Turkey (+90)</option>
                        <option value="+971">United Arab Emirates (+971)</option>
                    </select>
                </div>

                <div class="col-sm-7 p-0" style="width: 60%;" >
                    <input type="number" name="multi_phone[${i}][number-${i}]" id="multiphone-${i}" counter="${i}" required placeholder="Enter Phone Number" class="form-control search_multiphone_number" value="" />
                    <div id="result-${i}"></div>
                </div>

                <div class="col-sm-1 pt-2">
                    <button type="button" class="btn remove-input-field"><i class="fa fa-trash fa-2x fa-solid" aria-hidden="true"></i></button>
                </div>
            </div>`

        );
    });

    $(document).on('click', '.remove-input-field', function () {
        // $(this).parents('div').remove();
        // this.parentNode.parentNode.remove();
        $(this).parent().parent('div').remove();
    });


    $(document).on('input', '.search_multiphone_number', function () {

        var inputId = $(this).attr('id');
        var id_counter = $(this).attr('counter');
        // alert(id_counter);
        // alert(inputId);

        if(inputId !='' && id_counter != '')
        {
            var token = "{{csrf_token()}}";
            var search_multiphone = $('#'+inputId).val();
        }

        if(search_multiphone != '' && search_multiphone.toString().length >= 8){
            check_multi_number(search_multiphone , id_counter , token);
        }else{
            $("#result-"+id_counter).html('');
        }
    });

    function check_multi_number(number, id_counter, token)
    {
        $.ajax({
            type: "POST",
            url: "{{route('check.multi-phone')}}",
            data: {
                'phone': number,
                'id_counter': id_counter,
                '_token': token
            },
            success: function (response)
            {
                // console.log(response);
                $("#result-"+response.id_counter).html(response.html)
            }
        });
    }

</script> --}}



@endsection
