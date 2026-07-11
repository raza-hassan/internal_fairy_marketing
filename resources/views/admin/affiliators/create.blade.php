@extends('layouts.app', ['activePage' => 'affiliators', 'titlePage' => __('Affiliators')])



@section('content')

@include('admin.users.sidebar')

<div class="ps-main__wrapper">
   <header class="header--dashboard">
       <div class="header__left">
           <h3>New Affiliator</h3>
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
       <form method="post" action="{{ url('admin/affiliators/store') }}" class="form-horizontal" enctype="multipart/form-data">
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
                                   <input class="form-control name" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                               </div>
                               <div class="form-group">
                                   <label>Email
                                   </label>
                                   <input class="form-control" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}"/>
                               </div>
                               <div class="form-group">
                                   <label>Cnic<sup>*</sup>
                                   </label>
                                   <input class="form-control cnic" maxlength="15"  pattern="[1-9]{5}[-][0-9]{7}[-][0-9]{1}" title="Please Use Cnic Formate xxxxx-xxxxxxxx-x" name="cnic" id="input-name" type="text" placeholder="{{ __('12345-1234567-1') }}" value="{{ old('cnic') }}" required=""/>
                               </div>
                               <div class="form-group">
                                   <label>CNIC Front Image<sup>*</sup></label>
                                   <div class="form-group--nest">
                                       <input class="form-control mb-1 cnic_front_image" name="cnicf" type="file" value="{{ old('cnicf') }}" >
                                       <button class="ps-btn ps-btn--sm">Choose</button>
                                   </div>
                               </div>
                               <div class="form-group">
                                   <label>CNIC Back Image<sup>*</sup></label>
                                   <div class="form-group--nest">
                                       <input class="form-control mb-1 cnic_back_image" name="cnicb" type="file" >
                                       <button class="ps-btn ps-btn--sm">Choose</button>
                                   </div>
                               </div>
                               <div class="dealor_div" style="display:  @if(old('type') == 'Freelancer') none @else block @endif;">
                                   <div class="form-group">
                                       <label>Office Inside Pic <sup>*</sup></label>
                                       <div class="form-group--nest">
                                           <input class="form-control mb-1 requiredfile office_inside_pic" name="offinspic" type="file" @if(old('type') == 'Dealer' || old('type') == '')  @else block @endif;>
                                                  <button class="ps-btn ps-btn--sm">Choose</button>
                                       </div>
                                   </div>
                                   <div class="form-group">
                                       <label>Office Outside Pic <sup>*</sup></label>
                                       <div class="form-group--nest">
                                           <input class="form-control mb-1 requiredfile office_outside_pic" name="offoutspic" type="file"  @if(old('type') == 'Dealer' || old('type') == '')  @else block @endif;>
                                                  <button class="ps-btn ps-btn--sm">Choose</button>
                                       </div>
                                   </div>
                                   <div class="form-group">
                                       <label>Office Board Pic <sup>*</sup></label>
                                       <div class="form-group--nest">
                                           <input class="form-control mb-1 requiredfile office_board_pic" name="offboardpic" type="file"  @if(old('type') == 'Dealer' || old('type') == '')  @else block @endif;>
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
                               <div class="form-group">
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
                                   {{-- <input class="form-control" type="text" placeholder="3001234567" name="phone" value="{{ old('phone') }}" pattern="[0-9]{3}[0-9]{7}"  required="true" aria-required="true" style="width: 69%;"/> --}}
                                   <input class="form-control phone" maxlength="10"  pattern="[3-3]{1}[0-9]{9}" title="Please start from 3 & enter exactly 10 digits only" name="phone" type="text" placeholder="3001234567"   value="{{ old('phone') }}" required="true" aria-required="true" style="width: 69%;"/>
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
                               <input type="hidden" name="status" value="0">
                           </div>
                       </figure>
                   </div>
               </div>
           </div>
           <div class="ps-form__bottom">
               <a class="ps-btn ps-btn--black" href="{{url('affiliators')}}">Cancel</a>
               {{-- <button class="ps-btn" type="submit" id="">Submit</button> --}}
               <button id="lead_create" class="btn btn-success button" type="submit" >Submit</button>
           </div>
       </form>
   </section>

</div>



<script>
   let name = document.querySelector(".name");
   let cnic = document.querySelector(".cnic");
   let cnic_front_image = document.querySelector(".cnic_front_image");
   let cnic_back_image = document.querySelector(".cnic_back_image");
   let office_inside_pic = document.querySelector(".office_inside_pic");
   let office_outside_pic = document.querySelector(".office_outside_pic");
   let office_board_pic = document.querySelector(".office_board_pic");
   let address = document.querySelector(".address");
   let phone = document.querySelector(".phone");
   let button = document.querySelector(".button");
   button.disabled = true;
   name.addEventListener("change", stateHandle);
   cnic.addEventListener("change", stateHandle);
   cnic_front_image.addEventListener("change", stateHandle);
   cnic_back_image.addEventListener("change", stateHandle);
   office_inside_pic.addEventListener("change", stateHandle);
   office_outside_pic.addEventListener("change", stateHandle);
   office_board_pic.addEventListener("change", stateHandle);
   address.addEventListener("change", stateHandle);
   phone.addEventListener("change", stateHandle);
   function stateHandle() {
       if (
               document.querySelector(".name").value != ""
               && document.querySelector(".cnic").value != ""
               && document.querySelector(".cnic_front_image").value != ""
               && document.querySelector(".cnic_back_image").value != ""
               && document.querySelector(".office_inside_pic").value != ""
               && document.querySelector(".office_outside_pic").value != ""
               && document.querySelector(".office_board_pic").value != ""
               && document.querySelector(".address").value != ""
               && document.querySelector(".phone").value != ""
               )
       {
           // console.log('ifname : '+name.value+ ' Phone: '+phone.value+' source: '+source.value);
           button.disabled = false;
       } else {
           // console.log('elsename : '+name.value+ ' Phone: '+phone.value+' source: '+source.value);
           button.disabled = true;
       }
   }



</script>











@endsection

