@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Edit User</h3>
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
    @if ($selfRestricted ?? false)
        <div class="alert alert-info">{{ __('Some fields are locked on your account — contact your senior to make changes. You can still update your contact information, profile picture, and password.') }}</div>
        <style>
            .field-locked-badge {
                display: inline-block;
                margin-left: 6px;
                padding: 1px 6px;
                font-size: 11px;
                font-weight: normal;
                color: #856404;
                background-color: #fff3cd;
                border: 1px solid #ffeeba;
                border-radius: 3px;
                vertical-align: middle;
            }
        </style>
    @endif
    <section class="ps-new-item">
        <form method="post" action="{{ url('user/update',$user) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>General</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Name<sup>*</sup> @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <input class="form-control" name="name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required="true" aria-required="true" @disabled($selfRestricted ?? false)/>
                                    @if ($errors->has('name'))
                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Father Name / Husband Name @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <input class="form-control" name="fname" type="text" placeholder="{{ __('Father Name') }}" value="{{ old('fname', $user->fname) }}" @disabled($selfRestricted ?? false)/>
                                </div>
                                <div class="form-group">
                                    <label>Email<sup>*</sup> @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" autocomplete="off" required @disabled($selfRestricted ?? false)/>
                                    @if ($errors->has('email'))
                                    <span id="email-error" class="error text-danger" for="input-email">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Gender @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <select class="ps-select" title="Status" name="gender" @disabled($selfRestricted ?? false)>
                                        <option value="Male">Select Type</option>
                                        <option <?php
                                        if ($user->gender == 'Male') {
                                            echo 'selected';
                                        }
                                        ?> value="Male">Male</option>
                                        <option <?php
                                        if ($user->gender == 'Female') {
                                            echo 'selected';
                                        }
                                        ?> value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cnic ID<sup>*</sup> @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <input class="form-control" name="cnic" type="text" placeholder="{{ __('CNIC') }}" value="{{ old('cnic', $user->cnic) }}" autocomplete="off" @disabled($selfRestricted ?? false)/>
                                </div>
                                <div class="form-group">
                                    <label>DOB @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <input class="form-control datepicker" name="dob" type="text" placeholder="{{ __('Date Of Birth') }}" value="{{ old('dob', $user->dob) }}" autocomplete="off" @disabled($selfRestricted ?? false)/>
                                </div>
                                <div class="form-group">
                                    <label>Emergency Name @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <input class="form-control" type="text" placeholder="Emergency Name" name="emgname" value="{{ old('emgname', $user->emgname) }}" autocomplete="off" @disabled($selfRestricted ?? false)/>
                                </div>
                                <div class="form-group">
                                    <label>Emergency Number @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <input class="form-control" type="text" placeholder="Emergency Number" name="emgrnum" value="{{ old('emgrnum', $user->emgrnum) }}" autocomplete="off" @disabled($selfRestricted ?? false)/>
                                </div>
                                <div class="form-group">
                                    <label>Relation @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif
                                    </label>
                                    <input class="form-control" type="text" placeholder="Relation" name="emgrrelation" value="{{ old('emgrrelation', $user->emgrrelation) }}" autocomplete="off" @disabled($selfRestricted ?? false)/>
                                </div>
                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>User Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Address
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Address" name="address" value="{{ old('address', $user->address) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Mobile<sup>*</sup>
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Mobile" name="telephone1" value="{{ old('telephone1', $user->telephone1) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Telephone
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Telephone" name="telephone2" value="{{ old('telephone2', $user->telephone2) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Profile Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="file" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($user->profile != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$user->profile) }}" width="50">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>CNIC Front Image @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicf" type="file" @disabled($selfRestricted ?? false)>
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($user->cnicf != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$user->cnicf) }}" width="50">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>CNIC Back Image @if($selfRestricted ?? false)<span class="field-locked-badge">🔒 Locked</span>@endif</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicb" type="file" @disabled($selfRestricted ?? false)>
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($user->cnicf != '')
                                    <br>
                                    <img src="{{ url('storage/app/public/'.$user->cnicf) }}" width="50">
                                    @endif
                                </div>
                                <input type="hidden" name="oldfile" value="{{$user->profile}}">
                                <input type="hidden" name="oldcnicf" value="{{$user->cnicf}}">
                                <input type="hidden" name="oldcnicb" value="{{$user->cnicb}}">
                            </div>
                        </figure>
                    </div>
                    <div class="ps-form__bottom">
                        <a class="ps-btn ps-btn--black" href="{{url('/')}}">Cancel</a>
                        <button class="ps-btn" type="submit">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <hr>
    <section class="ps-new-item">
        <form method="post" action="{{ url('profile/password') }}" class="form-horizontal">
            @csrf
            @method('put')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>User Password</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="inputPassword">Password</label>
                                    <div class="col-md-6">
                                        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" input type="password" name="password" id="input-password" placeholder="{{ __('Password') }}" />
                                        @if ($errors->has('password'))
                                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="inputPassword">Confirm Password</label>
                                    <div class="col-md-6">
                                        <input class="form-control" name="password_confirmation" id="input-password-confirmation" type="password" placeholder="{{ __('Confirm Password') }}" />
                                    </div>
                                </div>
                            </div>
                        </figure>
                    </div>
                    <div class="ps-form__bottom">
                        <a class="ps-btn ps-btn--black" href="{{url('/')}}">Cancel</a>
                        <button class="ps-btn" type="submit">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
//        alert('sdfsd');
    $(document).on('change', '#manager select', function (e) {
        e.preventDefault();
        var role = $(this).val();
        if (role != 1) {
            $('#assignee').css('display', 'block');
        } else {
            $('#assignee').css('display', 'none');
        }
    });
});
</script>
@endsection
