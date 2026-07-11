@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets/CREATE-ACCOUNT-B2B.css')}}" media="screen">
@section('content')
<!-- start: page -->
<section class="u-clearfix u-custom-color-4 u-section-1" id="carousel_3fca">
    <div class="u-clearfix u-sheet u-valign-middle-sm u-valign-middle-xs u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
            <div class="u-layout">
                <div class="u-layout-row">
                    <div class="u-container-style u-layout-cell u-size-20 u-layout-cell-1">
                        <div class="u-container-layout u-container-layout-1">
                            <h6 class="u-custom-font u-font-oswald u-text u-text-body-alt-color u-text-1">REGISTER FOR A TRADE ACCOUNT</h6>
                            <p class="u-text u-text-body-alt-color u-text-2"> Please complete and submit this application form to apply for trade access to our website.<br>
                                <br>Once registered, we will be in touch to verify your details and activate your account.<br>
                                <br> If you are already a registered user, click the button below to log in.&nbsp;<br>
                            </p>
                            <a href="{{url('login')}}" class="u-active-custom-color-1 u-align-left u-border-none u-btn u-btn-round u-button-style u-custom-color-1 u-hover-white u-radius-1 u-text-active-white u-text-hover-custom-color-1 u-btn-1"><span class="u-icon u-text-white u-icon-1"><svg class="u-svg-content" viewBox="0 0 443.52 443.52" x="0px" y="0px" style="width: 1em; height: 1em;"><g><g><path d="M143.492,221.863L336.226,29.129c6.663-6.664,6.663-17.468,0-24.132c-6.665-6.662-17.468-6.662-24.132,0l-204.8,204.8    c-6.662,6.664-6.662,17.468,0,24.132l204.8,204.8c6.78,6.548,17.584,6.36,24.132-0.42c6.387-6.614,6.387-17.099,0-23.712    L143.492,221.863z"></path>
                                    </g>
                                    </g></svg><img></span>&nbsp;BACK TO LOG IN
                            </a>
                        </div>
                    </div>

                    <div class="u-align-left u-container-style u-layout-cell u-right-cell u-size-40 u-layout-cell-2">
                        <div class="u-container-layout u-container-layout-2">
                            <div class="u-expanded-width u-form u-form-1">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <form action="{{ route('register') }}" method="POST" class="u-clearfix u-form-spacing-18 u-form-vertical u-inner-form" style="padding: 0px;" source="custom" name="form">
                                    @csrf
                                    <div class="u-form-group u-form-name u-form-partition-factor-2">
                                        <label for="name-daf4" class="u-label u-text-custom-color-1">Registered Business Name <span class="required">*</span></label>
                                        <input type="text" id="name-daf4" name="name" value="{{ old('name') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-1" required="">
                                    </div>
                                    <div class="u-form-address u-form-group u-form-partition-factor-2 u-form-group-2">
                                        <label for="address-7d20" class="u-label u-text-custom-color-1">Trading Name <span class="required">*</span></label>
                                        <input type="text" id="address-7d20" name="tname" value="{{ old('tname') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-2" required="">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-3">
                                        <label for="text-baf5" class="u-label u-text-custom-color-1 u-label-3">Year Established </label>
                                        <input type="text" placeholder="" id="text-baf5" name="eyestablish" value="{{ old('eyestablish') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-3">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-4">
                                        <label for="text-907e" class="u-label u-text-custom-color-1">Nature of Business </label>
                                        <input type="text" placeholder="" id="text-907e" name="bnature" value="{{ old('bnature') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-4">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-select u-form-group-5">
                                        <label for="select-59e9" class="u-label u-text-custom-color-1">Legal Entity </label>
                                        <div class="u-form-select-wrapper">
                                            <select id="select-59e9" name="lentity" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-5">
                                                <option value="Limited Company">Limited Company</option>
                                                <option value="Partnership">Partnership</option>
                                                <option value="Sole Trader">Sole Trader</option>
                                                <option value="Local Authority">Local Authority</option>
                                                <option value="Registered Charity">Registered Charity</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" class="u-caret"><path fill="currentColor" d="M4 8L0 4h8z"></path></svg>
                                        </div>
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-select u-form-group-6">
                                        <label for="select-8fd3" class="u-label u-text-custom-color-1">How are you selling?</label>
                                        <div class="u-form-select-wrapper">
                                            <select id="select-8fd3" name="selling" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-6">
                                                <option value="Brick &amp; Mortar Store/s">Brick &amp; Mortar Store/s</option>
                                                <option value="Online Store/s">Online Store/s</option>
                                                <option value="Brick &amp; Mortar &amp; Online">Brick &amp; Mortar &amp; Online</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" class="u-caret"><path fill="currentColor" d="M4 8L0 4h8z"></path></svg>
                                        </div>
                                    </div>

                                     <div class="u-form-group u-form-partition-factor-2 u-form-group-9">
                                        <label for="text-48dd" class="u-label u-text-custom-color-1">VAT No.</label>
                                        <input type="text" placeholder="" id="text-48dd" name="vatnumber" value="{{ old('vatnumber') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-9">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-10">
                                        <label for="text-e089" class="u-label u-text-custom-color-1">Company Registration No.</label>
                                        <input type="text" placeholder="" id="text-e089" name="regnumber" value="{{ old('regnumber') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-10">
                                    </div>

                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-7">
                                        <label for="text-c042" class="u-label u-text-custom-color-1">Business Owner/s Name/s </label>
                                        <input type="text" placeholder="" id="text-c042" name="bowner" value="{{ old('bowner') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-7">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-8">
                                        <label for="text-04c1" class="u-label u-text-custom-color-1">Website </label>
                                        <input type="text" placeholder="" id="text-04c1" name="website" value="{{ old('website') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-8">
                                    </div>

                                                                        <div class="u-form-group u-form-partition-factor-3 u-form-group-24">
                                        <label for="text-5725" class="u-label u-text-custom-color-1"> Contact First Name </label>
                                        <input type="text" id="text-5725" name="contact_first" value="{{ old('contact_first') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-23">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-3 u-form-group-24">
                                        <label for="text-5725" class="u-label u-text-custom-color-1"> Contact Last Name </label>
                                        <input type="text" id="text-5725" name="contact_last" value="{{ old('contact_last') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-23">
                                    </div>

                                    <div class="u-form-group u-form-partition-factor-3 u-form-group-26">
                                        <label for="text-785c" class="u-label u-text-custom-color-1">Department / Role / Title <span class="required">*</span></label>
                                        <input type="text" placeholder="" id="text-785c" name="department" value="{{ old('department') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-25" required="required">
                                    </div>

                                        <div class="u-form-group u-form-partition-factor-2 u-form-group-27">
                                        <label for="text-fa4b" class="u-label u-text-custom-color-1">(Contact) Telephone <span class="required">*</span></label>
                                        <input type="text" id="text-fa4b" name="telephone1" value="{{ old('telephone1') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-26" required="required" >
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-30">
                                        <label for="text-f8d5" class="u-label u-text-custom-color-1">Email Address for B2B Online Ordering Portal <span class="required">*</span></label>
                                        <input type="text" placeholder="" id="text-f8d5" name="email" value="{{ old('email') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-29" required="required" >
                                    </div>


                                    <div class="u-form-address u-form-group u-form-partition-factor-2 u-form-group-11">
                                        <label for="address-61cc" class="u-label u-text-custom-color-1">(Business) Address 1 <span class="required">*</span></label>
                                        <input type="text" id="address-61cc" name="address1" value="{{ old('address1') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-11" required="">
                                    </div>
                                    <div class="u-form-address u-form-group u-form-partition-factor-2 u-form-group-12">
                                        <label for="address-8c1b" class="u-label u-text-custom-color-1">(Business) Address 2 </label>
                                        <input type="text" id="address-8c1b" name="address2" value="{{ old('address2') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-12">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-phone u-form-group-13">
                                        <label for="phone-966d" class="u-label u-text-custom-color-1">(Business) Town/City<span class="required">*</span></label>
                                        <input type="text" name="bcity" value="{{ old('bcity') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-13" required>
                                    </div>
                                    <div class="u-form-email u-form-group u-form-partition-factor-2">
                                        <label for="email-daf4" class="u-label u-text-custom-color-1">(Business) Postcode / Zip <span class="required">*</span></label>
                                        <input type="text" autocomplete="off" id="email-daf4" name="bzipcode" value="{{ old('bzipcode') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-14" required="">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-15">
                                        <label for="text-09ba" class="u-label u-text-custom-color-1">(Business) County / State / Province <span class="required">*</span></label>
                                        <input type="text" placeholder="" id="text-09ba" name="bstate" value="{{ old('bstate') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-15">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-16">
                                        <label for="text-b56c" class="u-label u-text-custom-color-1">(Business) Country <span class="required">*</span></label>
                                        <input type="text" placeholder="" id="text-b56c" name="bcountry" value="{{ old('bcountry') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-16" required="required">
                                    </div>
                                    <div class="u-form-group u-form-radiobutton u-form-group-17">
                                        <div class="u-form-radio-button-wrapper">
                                            <input type="checkbox" class="deliveraddress" value="{{ old('radiobutton') }}">
                                            <input type="hidden" id="deliveraddress_hidden" name="deliveraddress" value="0">
                                            <label class="u-label u-text-custom-color-1" for="radiobutton">Delivery address is same as above </label>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="sameaddress" style="display: block; width: 100%;">
                                        <div class="u-form-address u-form-group u-form-partition-factor-2 u-form-group-18">
                                            <label for="address-124c" class="u-label u-text-custom-color-1">(Delivery) Address 1 </label>
                                            <input type="text" id="address-124c" name="saddress1" value="{{ old('saddress1') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-17">
                                        </div>
                                        <div class="u-form-address u-form-group u-form-partition-factor-2 u-form-group-19">
                                            <label for="address-2883" class="u-label u-text-custom-color-1">(Delivery) Address 2 </label>
                                            <input type="text" id="address-2883" name="saddress2" value="{{ old('saddress2') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-18">
                                        </div>
                                        <div class="u-form-address u-form-group u-form-partition-factor-2 u-form-group-20">
                                            <label for="address-021c" class="u-label u-text-custom-color-1">(Delivery) Town/City </label>
                                            <input type="text" id="address-021c" name="scity" value="{{ old('scity') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-19">
                                        </div>
                                        <div class="u-form-address u-form-group u-form-partition-factor-2 u-form-group-21">
                                            <label for="address-920e" class="u-label u-text-custom-color-1">(Delivery) Postcode / Zip </label>
                                            <input type="text" id="address-920e" name="szipcode" value="{{ old('szipcode') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-20">
                                        </div>
                                        <div class="u-form-group u-form-partition-factor-2 u-form-group-22">
                                            <label for="text-e860" class="u-label u-text-custom-color-1">(Delivery) County / State / Province </label>
                                            <input type="text" placeholder="" id="text-e860" name="sstate" value="{{ old('sstate') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-21">
                                        </div>
                                        <div class="u-form-group u-form-partition-factor-2 u-form-group-23">
                                            <label for="text-e838" class="u-label u-text-custom-color-1">(Delivery) Country </label>
                                            <input type="text" placeholder="" id="text-e838" name="scountry" value="{{ old('scountry') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-22">
                                        </div>
                                    </div>

                                    <div class="u-form-group  u-form-group-28">
                                        <label for="text-8304" class="u-label u-text-custom-color-1">(Delivery) Telephone </label>
                                        <input type="text" placeholder="" id="text-8304" name="telephone2" value="{{ old('telephone2') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-27">
                                    </div>
{{--                                  <div class="u-form-group u-form-partition-factor-2 u-form-group-29">--}}
{{--                                        <label for="text-fdd5" class="u-label u-text-custom-color-1">Email Address <span class="required">*</span></label>--}}
{{--                                        <input type="text" id="text-fdd5" name="email" value="{{ old('email') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-28" required="required">--}}
{{--                                    </div>--}}

                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-29">
                                        <label for="text-fdd5" class="u-label u-text-custom-color-1">Password <span class="required">*</span></label>
                                        <input type="password" id="text-fdd5" name="password" value="{{ old('password') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-28" required="required">
                                    </div>
                                    <div class="u-form-group u-form-partition-factor-2 u-form-group-30">
                                        <label for="text-f8d5" class="u-label u-text-custom-color-1">Confirm Password <span class="required">*</span></label>
                                        <input type="password" autocomplete="off" placeholder="" id="text-f8d5" name="password_confirmation" value="{{ old('password_confirmation') }}" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-29">
                                    </div>
                                    <div class="u-form-group u-form-message u-form-group-31">
                                        <label for="message-b122" class="u-label u-text-custom-color-1">Apply a comment</label>
                                        <textarea placeholder="Enter your message" rows="4" cols="50" id="message-b122" name="comment" class="u-border-2 u-border-white u-input u-input-rectangle u-text-custom-color-1 u-white u-input-30">{{ old('comment') }}</textarea>
                                    </div>
                                    <input type="hidden" value="0" name="status">
                                    <div class="u-form-agree u-form-group u-form-group-32">
                                        <input type="checkbox" id="agree-2c2f" name="agree" class="u-agree-checkbox" required="">
{{--                                        <label for="agree-2c2f" class="u-label u-text-custom-color-1">I accept the <a href="{{url('/')}}/public/amaroni-terms-and-conditions.pdf" download>Terms of Service <span class="required">*</span></a>--}}
                                        <label for="agree-2c2f" class="u-label u-text-custom-color-1">I accept the <a href="https://ln2.sync.com/dl/836938bc0/ky58s4at-y4j9kjh5-ua75ngft-jwfbcjp6/view/doc/9652906860006" target="_blank">Terms of Service <span class="required">*</span></a>
                                        </label>
                                    </div>
                                    <div class="u-form-checkbox u-form-group u-form-group-33">
                                        <input type="checkbox" id="checkbox-76f8" name="checkbox_newsletter" value="On">
                                        <label for="checkbox-76f8" class="u-label u-text-custom-color-1">I would like to receive emails for new products and amazing offers </label>
                                    </div>
                                    <div class="u-align-left u-form-group u-form-submit">
                                        <a href="#" class="u-active-grey-70 u-border-none u-btn u-btn-submit u-button-style u-custom-color-1 u-hover-custom-color-3 u-text-body-alt-color u-text-hover-white u-btn-2">Submit</a>
                                        <input type="submit" value="submit" class="u-form-control-hidden">
                                    </div>
                                    <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
                                    <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then try again. </div>
                                    <input type="hidden" value="" name="recaptchaResponse">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    jQuery(document).ready(function () {
        $(document).on('click', '.deliveraddress', function (e) {
            if ($(this).prop("checked") == true) {
                $('.sameaddress').css('display', 'none');
                $('#deliveraddress_hidden').val('1');
            } else {
                $('.sameaddress').css('display', 'block');
                $('#deliveraddress_hidden').val('0');
            }
        });
    });
</script>
@endsection
