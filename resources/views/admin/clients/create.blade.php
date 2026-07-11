@extends('layouts.app', ['activePage' => 'clients', 'titlePage' => __('Clients')])

@section('content')


@include('admin.users.sidebar')


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
        <form method="post" action="{{ url('admin/client/store') }}" class="form-horizontal" enctype="multipart/form-data">
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
                                        <option value="{{$source->id}}">{{$source->type}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group" style="display: none;" id="afflilate_id">

                                </div>
                                <div class="form-group">
                                    <label>Cnic
                                    </label>

                                    {{-- <input class="form-control" name="cnic" id="input-name" type="text" placeholder="{{ __('Cnic') }}" value="{{ old('cnic') }}"/> --}}
                                    <input class="form-control cnic" maxlength="15"  pattern="[1-9]{5}[-][0-9]{7}[-][0-9]{1}" title="Please Use Cnic Formate xxxxx-xxxxxxxx-x" name="cnic" id="input-name" type="text" placeholder="{{ __('12345-1234567-1') }}" value="{{ old('cnic') }}" required=""/>


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
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
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

                                    <input class="form-control phone" maxlength="10"  pattern="[3-3]{1}[0-9]{9}" title="Please start from 3 & enter exactly 10 digits only" name="phone" type="text" placeholder="3001234567"   value="{{ old('phone') }}" style="width: 69%;"/>



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
                <a class="ps-btn ps-btn--black" href="{{url('leads')}}">Cancel</a>
                {{-- <button class="ps-btn " type="submit" id="">Submit</button> --}}
                {{-- <button  class="btn btn-success button" type="submit" >Submit</button> --}}
                <button id="lead_create" class="btn btn-success button" type="submit" >Submit</button>


            </div>
        </form>
    </section>
</div>








<script>
    let name = document.querySelector(".name");
    let phone = document.querySelector(".phone");
    let source = document.querySelector(".source");

    let button = document.querySelector(".button");
    button.disabled = true;

    name.addEventListener("change", stateHandle);
    phone.addEventListener("change", stateHandle);
    source.addEventListener("change", stateHandle);

    function stateHandle() {

        if(document.querySelector(".name").value != "" && document.querySelector(".phone").value != "" && document.querySelector(".source").value != "")
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


