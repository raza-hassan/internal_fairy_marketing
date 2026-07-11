@extends('freelancer.layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])

@section('content')

@include('freelancer.leads.sidebar')

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
    <section class="ps-new-item">
        <form method="post" action="{{ url('affiliator/profile/update') }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
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
                                    <input class="form-control" name="name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required="true" aria-required="true"/>
                                    @if ($errors->has('name'))
                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Email<sup>*</sup>
                                    </label>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" autocomplete="off" required/>
                                    @if ($errors->has('email'))
                                    <span id="email-error" class="error text-danger" for="input-email">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Gender
                                    </label>
                                    <select class="ps-select" title="Status" name="gender">
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
                                    <label>Cnic ID<sup>*</sup>
                                    </label>
                                    <input class="form-control" name="cnic" type="text" placeholder="{{ __('CNIC') }}" value="{{ old('cnic', $user->cnic) }}" autocomplete="off"/>
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
                                    <label>CNIC Front Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicf" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($user->cnicf != '')
                                    <br>
                                        <img src="{{ url('storage/app/public/'.$user->cnicf) }}" width="50">
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label>CNIC Back Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicb" type="file">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                    @if($user->cnicb != '')
                                    <br>
                                        <img src="{{ url('storage/app/public/'.$user->cnicb) }}" width="50">
                                    @endif
                                </div>

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
        <form method="post" action="{{ url('affiliator/profile/password') }}" class="form-horizontal">
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
