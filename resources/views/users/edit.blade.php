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

                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>

                    <strong>Success! </strong> {{ session('status') }}

                </div>

            </div>

        </div>

        @endif

    </header>

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

                                    <label>Name<sup>*</sup>

                                    </label>

                                    <input class="form-control" name="name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required="true" aria-required="true"/>

                                    @if ($errors->has('name'))

                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>

                                    @endif

                                </div>

                                <div class="form-group">

                                    <label>Father Name / Husband Name

                                    </label>

                                    <input class="form-control" name="fname" type="text" placeholder="{{ __('Father Name') }}" value="{{ old('fname', $user->fname) }}"/>

                                </div>

                                <div class="form-group">

                                    <label>Email<sup>*</sup>

                                    </label>

                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" autocomplete="off" required />

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

                                    <input class="form-control" name="cnic" type="text" placeholder="{{ __('CNIC') }}" value="{{ old('cnic', $user->cnic) }}" required=""/>

                                </div>

                                <div class="form-group">

                                    <label>DOB

                                    </label>

                                    <input class="form-control datepicker" name="dob" type="text" placeholder="{{ __('Date Of Birth') }}" value="{{ old('dob', $user->dob) }}"/>

                                </div>

                                <div class="form-group">

                                    <label>Emergency Name<sup>*</sup></label>

                                    <input class="form-control" type="text" placeholder="Emergency Name" name="emgname" value="{{ old('emgname', $user->emgname) }}" required=""/>

                                </div>

                                <div class="form-group">

                                    <label>Emergency Number<sup>*</sup>

                                    </label>

                                    <input class="form-control" type="text" placeholder="Emergency Number" name="emgrnum" value="{{ old('emgrnum', $user->emgrnum) }}" required=""/>

                                </div>

                                <div class="form-group">

                                    <label>Relation<sup>*</sup>

                                    </label>

                                    <input class="form-control" type="text" placeholder="Relation" name="emgrrelation" value="{{ old('emgrrelation', $user->emgrrelation) }}" required=""/>

                                </div>

                            </div>

                        </figure>

                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

                        <figure class="ps-block--form-box">

                            <figcaption>User Information</figcaption>

                            <div class="ps-block__content">

                                @if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)

                                    <div class="form-group">

                                        <label>Assign Manager<sup>*</sup></label>

                                        <select class="ps-select  {{ $errors->has('parent') ? ' is-invalid' : '' }}" id="input-parent"  title="Status" name="parent" required="" aria-required="true" @if (Auth::user()->id == $user->id) @disabled(true) @endif>
                                            <option selected disabled>Select Manager</option>
                                            @if(!empty($managers))
                                                @foreach($managers as $manager)
                                                    <option <?php if($user->parent == $manager->id ){ echo "selected"; } ?> value="{{$manager->id}}">{{$manager->name}}</option>
                                                @endforeach
                                            @endif

                                            @if (Auth::user()->id == $user->id)
                                                @if ($user->parentname)
                                                    <option selected value="{{$user->role}}" >{{$user->parentname->name}}</option>
                                                @endif
                                            @endif
                                        </select>

                                        @if ($errors->has('parent'))

                                        <span id="parent-error" class="error text-danger" for="input-parent">{{ $errors->first('name') }}</span>

                                        @endif

                                    </div>

                                    <div class="form-group" id="manager">
                                        <label> Designation Name <sup>*</sup></label>
                                        <input class="form-control{{ $errors->has('designation_name') ? ' is-invalid' : '' }}" name="designation_name" id="input-designation_name" type="text" placeholder="{{ __('Designation Name ') }}" value="{{$user->designation_name}}"  aria-required="true"/>
                                        @if ($errors->has('designation_name'))
                                            <span id="designation_name-error" class="error text-danger" for="input-designation_name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="manager">
                                        <label>Account Tpye<sup>*</sup></label>
                                        <?php $auth_user = Auth::user(); ?>
                                        <select class="ps-select  {{ $errors->has('role') ? ' is-invalid' : '' }}" id="input-role"  title="Status" name="role" required="" aria-required="true">
                                            <option selected disabled>Select Designation</option>
                                                @foreach($designations as $designation)
                                                    @if ($designation->sequence_menu  >= $auth_user->designation->sequence_menu)
                                                        <option <?php if($user->role == $designation->id ){ echo "selected"; } ?> value="{{$designation->id}}" >{{$designation->name}}</option>
                                                    @endif
                                                @endforeach
                                        </select>
                                        @if ($errors->has('role'))
                                            <span id="role-error" class="error text-danger" for="input-role">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">

                                        <label>Department<sup>*</sup>

                                        </label>

                                        <select class="ps-select {{ $errors->has('department_id') ? ' is-invalid' : '' }}" id="input-department_id"  title="Status" name="department_id" required="" aria-required="true">

                                            <option selected disabled>Select Department</option>

                                            @foreach($departments as $department)
                                            <option <?php if($user->department_id == $department->id ){ echo "selected"; } ?> value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach

                                        </select>

                                        @if ($errors->has('department_id'))

                                        <span id="department_id-error" class="error text-danger" for="input-department_id">{{ $errors->first('name') }}</span>

                                        @endif

                                    </div>

                                    <div class="form-group" id="office_id">

                                        <label> Assign Office <sup>*</sup></label>

                                        <select class="ps-select  {{ $errors->has('office_id') ? ' is-invalid' : '' }}" id="input-office_id"  title="office Name" name="office_id" required="" aria-required="true">

                                            <option selected disabled>Select Office</option>

                                            @if(!empty($offices))

                                                @foreach($offices as $office)

                                                    <option <?php if($user->office_id == $office->id ){ echo "selected"; } ?> value="{{$office->id}}">{{$office->name}}</option>

                                                @endforeach

                                            @endif

                                        </select>

                                        @if ($errors->has('office_id'))

                                        <span id="office_id-error" class="error text-danger" for="input-office_id">{{ $errors->first('name') }}</span>

                                        @endif

                                    </div>

                                @endif


                                <div class="form-group">
                                    <label>Address</label>
                                    <input class="form-control" type="text" placeholder="Enter Address" name="address" value="{{ old('address', $user->address) }}"/>
                                </div>

                                <div class="form-group">
                                    <label>Mobile<sup>*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter Mobile" name="telephone1" value="{{ old('telephone1', $user->telephone1) }}" required/>
                                </div>

                                <div class="form-group">
                                    <label>Telephone</label>
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

                                    @if($user->cnicf != '')

                                    <br>

                                    <img src="{{ url('storage/app/public/'.$user->cnicf) }}" width="50">

                                    @endif

                                </div>

                                @if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)

                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="switch switch-lg switch-success">
                                            Enable: <input <?php if($user->status == 1){ echo 'checked'; } ?> type="radio" name="status"  value="1" style="height: auto !important;"/>
                                            Disable: <input <?php if($user->status == 0){ echo 'checked'; } ?> type="radio" name="status" value="0" style="height: auto !important;"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label> Assign Role <sup>*</sup></label>
                                        <select class="ps-select  {{ $errors->has('assign_role') ? ' is-invalid' : '' }}" id="input-assign_role"  title="Role Name" name="assign_role" required aria-required="true">
                                            <option value="" selected disabled>Assign Role</option>
                                            @if(!empty($roles))
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ (old("assign_role" , $user->hasRole($role->id)) == $role->id ? "selected" : "" )}}>{{ $role->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('assign_role'))
                                            <span id="assign_role-error" class="error text-danger" for="input-assign_role">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                @endif

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

        <form method="post" action="{{ url('user/password',$user) }}" class="form-horizontal">

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

