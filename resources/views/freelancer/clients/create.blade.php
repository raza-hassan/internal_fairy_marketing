@extends('freelancer.layouts.app', ['activePage' => 'clients', 'titlePage' => __('Products')])
@section('content')
@include('freelancer.leads.sidebar')

<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Client</h3>
            <p>Add new Client</p>
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
        <form method="post" action="{{ url('affiliator/client/store') }}" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>General</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Name<sup>*</sup>
                                    </label>
                                    <input class="form-control name" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                                </div>
                                <div class="form-group">
                                    <label>Email
                                    </label>
                                    <input class="form-control" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}"/>
                                </div>
                                <div class="form-group" id="sourceid">
                                    <label>Source<sup>*</sup></label>
                                    <select class="form-control source" title="Status" name="source_id">
                                        <option value="">Choose Source</option>
                                        @foreach($sources as $source)
                                        <option value="{{$source->id}}" @if(old('source_id') == $source->id) selected @endif>{{$source->type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="display: none;" id="afflilate_id">
                                </div>
                                <div class="form-group">
                                    <label>Cnic
                                    </label>
                                    <input class="form-control" name="cnic" id="input-name" type="text" placeholder="{{ __('Cnic') }}" value="{{ old('cnic') }}"/>
                                </div>
                                <div class="form-group">
                                    <label>CNIC Front Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicf" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>CNIC Back Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicb" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                </div>
                            </div>
                        </figure>
                    </div>
                    <input type="hidden" name="user_id" value="{{Auth::guard('affiliator')->user()->id}}">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Client Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Gender<sup></sup>
                                    </label>
                                    <select class="ps-select gender form-control " title="Status" name="gender">
                                        <option value="Male">Select Type</option>
                                        <option value="Male" @if(old('gender') == 'Male') selected @endif>Male</option>
                                        <option value="Female" @if(old('gender') == 'Female') selected @endif>Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Address
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Address" name="address" value="{{ old('address') }}"/>
                                </div>
                                {{-- <div class="form-group">
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
                                     <input class="form-control" type="text" placeholder="3001234567" name="phone" value="{{ old('phone') }}" pattern="[0-9]{3}[0-9]{7}"  required="true" aria-required="true" style="width: 69%;"/>
                                    <input class="form-control phone" maxlength="10"  pattern="[3-3]{1}[0-9]{9}" title="Please start from 3 & enter exactly 10 digits only" name="phone" type="text" placeholder="3001234567"   value="{{ old('phone') }}" style="width: 69%;"/>
                                </div> --}}
<div class="form-group">
    <label> Phone </label> </br>
    <input class="phonestatus" type="radio" name="phonenumber" data-plugin-ios-switch  value="1" style="height: auto !important;"  @if(old('phonenumber')=='1' || old('phonenumber')=='') checked @endif/> Mobile Phone
    <input class="phonestatus" type="radio" name="phonenumber" data-plugin-ios-switch  value="0" style="height: auto !important;"  @if(old('phonenumber')=='0') checked @endif/> Ptcl
</div>
<div class="mobilediv" style="display: @if(old('phonenumber')=='0') none @else block @endif;">
    <style>
        .iti.iti--allow-dropdown.iti--separate-dial-code{width: 100%;}
        input#leadphone{width: 100%;}
    </style>
    <div class="form-group" id="code">
        <label>Mobile Phone<sup>*</sup></label>
        <input class="form-control phone" maxlength="15"  name="phone"  id="leadphone" type="text" placeholder="3001234567" required=""  value="{{ old('phone') }}"/>
        <span id="error-msg" class="hide"></span>
        <p id="result"></p>
    </div>
</div>
<div class="ptcldiv" style="display: @if(old('phonenumber')=='1' || old('phonenumber')=='') none @else block @endif;">
    <div class="form-group ">
        <label>Ptcl<sup>*</sup></label>
        <input class="form-control ptclphone" type="text" id="leadptcl" placeholder="Ptcl Phone Number" name="ptclphone" required="" value="{{ old('ptclphone') }}" />
    </div>
</div>
                                <div class="form-group">
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
                                    <input class="form-control" type="text" placeholder="TelePhone 1" name="telephone" value="{{ old('telephone') }}" style="width: 69%;"/>
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
                                    <input class="form-control" type="text" placeholder="TelePhone 2" name="telephone1" value="{{ old('telephone1') }}" style="width: 69%;"/>
                                </div>
                                <input type="hidden" name="status" value="1">
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('clients')}}">Cancel</a>
                <button id="client_create" class="btn btn-success button" disabled="" type="submit" >Submit</button>
            </div>
        </form>
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
            formatOnDisplay: true,
            separateDialCode: true,
            autoHideDialCode: true,
            autoPlaceholder: "aggressive" ,
            initialCountry: "pk",
            placeholderNumberType: "MOBILE",
            preferredCountries: ['il','ge'],
            geoIpLookup: getIp,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js",
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
@endsection
