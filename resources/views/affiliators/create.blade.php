@extends('layouts.app', ['activePage' => 'affiliators', 'titlePage' => __('Affiliators')])
@section('content')
@include('users.sidebar')

<div class="ps-main__wrapper">
   <header class="header--dashboard">
       <div class="header__left">
           <h3>New Affiliator </h3>
           <p>Add new Affiliator</p>
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
       <form method="post" action="{{ route('affiliators.store') }}" class="form-horizontal" enctype="multipart/form-data" onSubmit="return check_client()">
           @csrf
           @method('post')
           <div class="ps-form__content">
               <div class="row">
                   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                       <figure class="ps-block--form-box">
                           <figcaption>General</figcaption>
                           <div class="ps-block__content">
                               <div class="form-group">
                                   <label>Account</label>
                                   <div class="switch switch-lg switch-success affiliator_type">
                                        <input type="radio" name="type" data-plugin-ios-switch value="Dealer" style="height: auto !important;"  @if(old('type') == 'Dealer' || old('type') == '') checked @endif/> Dealer
                                        <input type="radio" name="type" data-plugin-ios-switch value="Freelancer" style="height: auto !important;" @if(old('type') == 'Freelancer') checked @endif/> Freelancer
                                   </div>
                               </div>
                               <div class="form-group">
                                   <label>Name<sup>*</sup>
                                   </label>
                                   <input class="form-control name" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required aria-required="true"/>
                               </div>
                               <div class="form-group">
                                   <label>Business<sup>*</sup>
                                   </label>
                                   <input class="form-control business_name" name="business" id="input-name" type="text" placeholder="{{ __('Business') }}" value="{{ old('business') }}" required aria-required="true"/>
                               </div>
                               <div class="form-group">
                                   <label>Email
                                   </label>
                                   <input class="form-control" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}"/>
                               </div>
                               <div class="form-group">
                                   <label>Cnic</label>
                                   <input class="form-control" maxlength="15" name="cnic" id="input-name" type="text" placeholder="{{ __('12345-1234567-1') }}" value="{{ old('cnic') }}"/>
                               </div>
                               <div class="form-group">
                                   <label>CNIC Front Image<sup>*</sup></label>
                                   <div class="form-group--nest">
                                       <input class="form-control mb-1 cnic_front_image" name="cnicf" type="file" value="{{ old('cnicf') }}" required>
                                       <button class="ps-btn ps-btn--sm">Choose</button>
                                   </div>
                               </div>
                               <div class="form-group">
                                   <label>CNIC Back Image<sup>*</sup></label>
                                   <div class="form-group--nest">
                                       <input class="form-control mb-1 cnic_back_image" name="cnicb" type="file" required>
                                       <button class="ps-btn ps-btn--sm">Choose</button>
                                   </div>
                               </div>
                               <div class="dealor_div" style="display:  @if(old('type') == 'Freelancer') none @else block @endif;">
                                   <div class="form-group">
                                       <label>Office Inside Pic <sup>*</sup></label>
                                       <div class="form-group--nest">
                                           <input class="form-control mb-1 requiredfile office_inside_pic" name="offinspic" required type="file" @if(old('type') == 'Dealer' || old('type') == '')  @else block @endif>
                                                  <button class="ps-btn ps-btn--sm">Choose</button>
                                       </div>
                                   </div>
                                   <div class="form-group">
                                       <label>MOU Sign Pic <sup>*</sup></label>
                                       <div class="form-group--nest">
                                           <input class="form-control mb-1 requiredfile office_outside_pic" name="offoutspic" required type="file"  @if(old('type') == 'Dealer' || old('type') == '')  @else block @endif>
                                                  <button class="ps-btn ps-btn--sm">Choose</button>
                                       </div>
                                   </div>
                                   <div class="form-group">
                                       <label>Office Board Pic <sup>*</sup></label>
                                       <div class="form-group--nest">
                                           <input class="form-control mb-1 requiredfile office_board_pic" name="offboardpic" required type="file"  @if(old('type') == 'Dealer' || old('type') == '')  @else block @endif>
                                                  <button class="ps-btn ps-btn--sm">Choose</button>
                                       </div>
                                   </div>
                                   <div class="form-group">
                                       <label>Office Visiting Card Pic</label>
                                       <div class="form-group--nest">
                                           <input class="form-control mb-1" name="offvisitpic" type="file">
                                           <button class="ps-btn ps-btn--sm">Choose</button>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </figure>
                   </div>
                   <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                   <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                       <figure class="ps-block--form-box">
                           <figcaption>Affiliator Information</figcaption>
                           <div class="ps-block__content">
                               <div class="form-group">
                                   <label>Gender<sup></sup>
                                   </label>
                                   <select class="ps-select form-control" title="Status" name="gender">
                                       <option value="Male">Select Type</option>
                                       <option value="Male" @if(old('gender') == 'Male') selected @endif>Male</option>
                                       <option value="Female" @if(old('gender') == 'Female') selected @endif>Female</option>
                                   </select>
                               </div>
                               <div class="form-group">
                                   <label>Address<sup>*</sup>
                                   </label>
                                   <input class="form-control requiredfile address" type="text" placeholder="Enter Address" name="address" value="{{ old('address') }}"  required=""/>
                               </div>
                               <!-- {{-- <div class="form-group">
                                   <label style="width: 100%;">Mobile<sup>*</sup>
                                   </label>
                                   <select name="countryCode" class="form-control" style="width: 30%;float: left;margin-right: 1%;">
                                      <option value="+92" selected="">Pakistan (+92)</option>
                                       <option value="+44">UK (+44)</option>
                                       <option value="+1">USA (+1)</option>
                                       <option value="+974">Qatar (+974)</option>
                                       <option value="+966">Saudi Arabia (+966)</option>
                                       <option value="+90">Turkey (+90)</option>
                                       <option value="+971">United Arab Emirates (+971)</option>
                                   </select>
                                   <input class="form-control phone" maxlength="10"  pattern="[3-3]{1}[0-9]{9}" title="Please start from 3 & enter exactly 10 digits only" name="phone" type="text" placeholder="3001234567"   value="{{ old('phone') }}" required="true" aria-required="true" style="width: 69%;"/>
                               </div> --}} -->


<!-- {{--===================Phone=================== --}} -->
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
            <button type="button" name="add" style="padding: 10px 10px 10px 10px"  class="btn btn-success" id="add-dynamic-international-phone2"><i class="fa fa-plus fa-solid text-white white_hover"  aria-hidden="true"></i></button>
        </div>
    </div>

    <div id="resu"  style="margin-bottom: 20px;"></div>


    <!-- {{-- Multi Phone --}} -->
    <style>
        .white_hover:hover{
            color:white !important;
        }
    </style>
    {{-- <div class="form-group" id="dynamicAddRemove" ></div> --}}

    <div class="form-group row">
        <div id="dynamicphoneFields">

        </div>
    </div>


    <!-- {{-- Multi Phone --}} -->

<!-- {{--===================Phone=================== --}} -->



                               <!-- <div class="form-group">
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
                                   <input class="form-control search_number" id="telephone"  type="text" placeholder="TelePhone 1" name="telephone" value="{{ old('telephone') }}" style="width: 69%;"/>
                               </div>
                               <div class="form-group">
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
                               </div> -->


                               <input type="hidden" name="status" value="0">



{{-- @if(Auth::user()->role == 1 )
    <div>
        <label for="is_login">
            <input class="login" type="checkbox" id="is_login" name="is_login"> Is Login
        </label>
    </div>
    <div class="form-group password_div" style="display: none" >
        <label>Password</label>
        <input class="form-control password" required name="password" id="input-password" type="password" placeholder="{{ __('Password') }}" value="{{ old('password') }}"/>
    </div>

@endif --}}








                           </div>
                       </figure>
                   </div>
               </div>
           </div>
           <div class="ps-form__bottom">
               <a class="ps-btn ps-btn--black" href="{{url('affiliators')}}">Cancel</a>
               <button id="affliator_create" class="btn btn-success button" type="submit">Submit</button>
           </div>
       </form>
   </section>

</div>

@if(old('type') == 'Freelancer' )

<script>
   jQuery(document).ready(function () {
       $('.dealor_div').css('display', 'none');
       $('.requiredfile').attr('required', false);
   });

</script>

@endif



    <script>

        // $('.login').click(function(event) { //on click
        //     let password = document.querySelector(".password");
        //   if (this.checked) { // check select status
        //     $('.password_div').css('display', 'block');
        //     $('.password').attr('required', true);
        //     // password.disabled = false;
        //   }else {
        //     $('.password_div').css('display', 'none');
        //     $('.password').attr('required', false);
        //     // password.disabled = true;
        //   }
        // });



        let name = document.querySelector(".name");
        let business_name = document.querySelector(".business_name");
        // let cnic = document.querySelector(".cnic");
        let cnic_front_image = document.querySelector(".cnic_front_image");
        let cnic_back_image = document.querySelector(".cnic_back_image");
        let address = document.querySelector(".address");
        let phone = document.querySelector(".phone");
        let ptclphone = document.querySelector(".ptclphone");
        let button = document.querySelector(".button");
        button.disabled = true;



        name.addEventListener("change", stateHandle);
        //    cnic.addEventListener("change", stateHandle);
        cnic_front_image.addEventListener("change", stateHandle);
        cnic_back_image.addEventListener("change", stateHandle);
        address.addEventListener("change", stateHandle);
        phone.addEventListener("change", stateHandle);
        ptclphone.addEventListener("change", stateHandle);
        business_name.addEventListener("change", stateHandle);


        name.addEventListener("keyup", stateHandle);
        business_name.addEventListener("keyup", stateHandle);
        //    cnic.addEventListener("keyup", stateHandle);
        cnic_front_image.addEventListener("keyup", stateHandle);
        cnic_back_image.addEventListener("keyup", stateHandle);
        address.addEventListener("keyup", stateHandle);
        phone.addEventListener("keyup", stateHandle);
        ptclphone.addEventListener("keyup", stateHandle);

        function stateHandle() {
            if (    document.querySelector(".name").value != ""
                    && document.querySelector(".business_name").value != ""
                    // && document.querySelector(".cnic").value != ""
                    && document.querySelector(".cnic_front_image").value != ""
                    && document.querySelector(".cnic_back_image").value != ""
                    && document.querySelector(".address").value != ""
                    && document.querySelector(".phone").value != ""
                ) {
                button.disabled = false;
            }
            else if (document.querySelector(".name").value != ""
                    && document.querySelector(".business_name").value != ""
                    && document.querySelector(".cnic_front_image").value != ""
                    && document.querySelector(".cnic_back_image").value != ""
                    && document.querySelector(".address").value != ""
                    && document.querySelector(".ptclphone").value != ""
                ) {
                button.disabled = false;
            }
            else {
                button.disabled = true;
            }
        }

    </script>




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
                //    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js",
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
            $(document).on('input', '.search_number', function () {

                var token = "{{csrf_token()}}";
                var search_phone = $('#leadphone').val();
                var search_ptcl= $('#leadptcl').val();
                // var search_telephone= $('#telephone').val();
                // var search_telephone1= $('#telephone1').val();
                $("#resu").html('');

                if(search_phone != '' && search_phone.toString().length >= 8){
                    check_number(search_phone , token);
                }if(search_ptcl != '' && search_ptcl.toString().length >= 8){
                    check_number(search_ptcl , token);
                }
                // if(search_telephone != '' && search_telephone.toString().length >= 8){
                //     check_number(search_telephone , token);
                // }if(search_telephone1 != '' && search_telephone1.toString().length >= 8){
                //     check_number(search_telephone1 , token);
                // }
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

    <script>
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



{{-- Dynamically Add Multiple International Phone fields --}}
<script>

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


@endsection

