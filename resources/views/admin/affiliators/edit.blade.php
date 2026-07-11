@extends('layouts.app', ['activePage' => 'affiliators', 'titlePage' => __('Products')])

@section('content')

@include('admin.affiliators.sidebar')


<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Edit Affiliator</h3>
        </div>
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
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Success! </strong> {{ session('status') }}
                </div>
            </div>
        </div>
        @endif
    </header>
    <section class="ps-new-item">
        <form method="post" action="{{ route('affiliators.update', $affiliator) }}" class="form-horizontal" enctype="multipart/form-data">
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
                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $affiliator->name) }}" required="true" aria-required="true"/>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', $affiliator->email) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Cnic<sup>*</sup>
                                    </label>
                                    <input class="form-control" name="cnic" id="input-name" type="text" placeholder="{{ __('Cnic') }}" value="{{ old('cnic', $affiliator->cnic) }}" required="true" aria-required="true"/>
                                </div>

                                <div class="form-group">
                                    <label>CNIC Front Image<sup>*</sup></label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicf" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($affiliator->cnicf != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$affiliator->cnicf) }}">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>CNIC Back Image<sup>*</sup></label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicb" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($affiliator->cnicb != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$affiliator->cnicb) }}">
                                    @endif
                                </div>

                                @if($affiliator->type == 'Dealor')
                                <div class="form-group">
                                    <label>Office Inside Pic <sup>*</sup></label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1 requiredfile" name="offinspic" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($affiliator->offinspic != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$affiliator->offinspic) }}">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Office Outside Pic <sup>*</sup></label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1 requiredfile" name="offoutspic" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($affiliator->offoutspic != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$affiliator->offoutspic) }}">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Office Board Pic <sup>*</sup></label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1 requiredfile" name="offboardpic" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($affiliator->offboardpic != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$affiliator->offboardpic) }}">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Office Visiting Card Pic</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="offvisitpic" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($affiliator->offvisitpic != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$affiliator->offvisitpic) }}">
                                    @endif
                                </div>
                                @endif

                                <input type="hidden" name="oldcnicf" value="{{$affiliator->cnicf}}">
                                <input type="hidden" name="oldcnicb" value="{{$affiliator->cnicb}}">
                                <input type="hidden" name="oldoffvisitpic" value="{{$affiliator->offvisitpic}}">
                                <input type="hidden" name="oldoffboardpic" value="{{$affiliator->offboardpic}}">
                                <input type="hidden" name="oldoffoutspic" value="{{$affiliator->offoutspic}}">
                                <input type="hidden" name="oldoffinspic" value="{{$affiliator->offinspic}}">


                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Affiliator Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Gender
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="gender">
                                        <option value="Male">Select Type</option>
                                        <option <?php
                                        if ($affiliator->gender == 'Male') {
                                            echo 'selected';
                                        }
                                        ?> value="Male">Male</option>
                                        <option <?php
                                        if ($affiliator->gender == 'Female') {
                                            echo 'selected';
                                        }
                                        ?> value="Female">Female</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Address<sup>*</sup>
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Address" name="address" value="{{ old('address', $affiliator->address) }}"  <?php
                                    if ($affiliator->type == 'Dealor') {
                                        echo 'required="true"';
                                    }
                                    ?> />
                                </div>
                                <div class="form-group">

                                    <label style="width: 100%;">Mobile<sup>*</sup>
                                    </label>

                                    <input class="form-control" type="text" name="phone" value="{{ old('phone', $affiliator->phone) }}"/>


                                </div>
                                <div class="form-group">
                                    @if($affiliator->telephone != '')
                                    <label>TelePhone 1</label>

                                    <input <?php if (Auth::user()->role != 1) { ?> readonly="" <?php } ?> class="form-control" type="text" placeholder="Telephone 1" name="telephone" value="{{ $affiliator->telephone }}"/>
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

                                    <input class="form-control" type="text" placeholder="TelePhone 1" name="telephone" value="{{ old('telephone') }}" style="width: 69%;"/>
                                    @endif
                                </div>
                                <div class="form-group">
                                    @if($affiliator->telephone1 != '')
                                    <label>TelePhone 2
                                    </label>
                                    <input <?php if (Auth::user()->role != 1) { ?> readonly="" <?php } ?> class="form-control" type="text" placeholder="Telephone 2" name="telephone1" value="{{ $affiliator->telephone1 }}"/>
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
                                    <input class="form-control" type="text" placeholder="TelePhone 2" name="telephone1" value="{{ old('telephone1') }}" style="width: 69%;"/>
                                    @endif
                                </div>
                                @if(Auth::user()->role == 1)
                                <div class="form-group">
                                    <label>Status</label>

                                    <div class="switch switch-lg switch-success">
                                        Enable: <input id="status" <?php
                                        if ($affiliator->status == 1) {
                                            echo 'checked';
                                        }
                                        ?> type="radio" name="status" data-plugin-ios-switch value="1" style="height: auto !important;"/>
                                        Disable: <input id="status" <?php
                                        if ($affiliator->status == 0) {
                                            echo 'checked';
                                        }
                                        ?>  type="radio" name="status" data-plugin-ios-switch value="0" style="height: auto !important;"/>
                                    </div>

                                </div>
                                @endif
                            </div>
                        </figure>

                    </div>
                </div>
            </div>
            @if ($affiliator->status == 0 && Auth::user()->role != 1)
            @else
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('affiliators')}}">Cancel</a>
                <button class="ps-btn" type="submit">Update</button>
            </div>
            @endif
        </form>
    </section>
</div>
@endsection
