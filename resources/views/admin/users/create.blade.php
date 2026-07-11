@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])

@section('content')
@include('users.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New User</h3>
            <p>Add new Users</p>
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
    </header>
    <section class="ps-new-item">
        <form method="post" action="{{ url('user/store') }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
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
                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                                    @if ($errors->has('name'))
                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Father Name / Husband Name
                                    </label>
                                    <input class="form-control" name="fname" type="text" placeholder="{{ __('Father Name') }}" value="{{ old('fname') }}"/>
                                    
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}" required />
                                    
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="ps-select" title="Status" name="gender">
                                        <option value="Male">Select Type</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cnic ID<sup>*</sup</label>
                                    <input class="form-control" name="cnic" type="text" placeholder="{{ __('CNIC') }}" value="{{ old('cnic') }}" required=""/>
                                   
                                </div>
                                <div class="form-group">
                                    <label>DOB</label>
                                    <input class="form-control" name="dob" type="text" placeholder="{{ __('Date Of Birth') }}" value="{{ old('dob') }}" required=""/>
                                   
                                </div>
                                
                                <div class="form-group">
                                    <label>Emergency Name
                                    </label>
                                    <input class="form-control" type="text" placeholder="Emergency Name" name="emgname" value="{{ old('emgname') }}"/>
                                </div>
                                
                                <div class="form-group">
                                    <label>Emergency Number
                                    </label>
                                    <input class="form-control" type="text" placeholder="Emergency Number" name="emgrnum" value="{{ old('emgrnum') }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Relation
                                    </label>
                                    <input class="form-control" type="text" placeholder="Relation" name="emgrrelation" value="{{ old('emgrrelation') }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Password<sup>*</sup>
                                    </label>
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" input type="password" name="password" id="input-password" placeholder="{{ __('Password') }}" value="" required />
                                    @if ($errors->has('password'))
                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label>Confirm Password<sup>*</sup>
                                    </label>
                                    <input class="form-control" name="password_confirmation" id="input-password-confirmation" type="password" placeholder="{{ __('Confirm Password') }}" />
                                </div>

                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>User Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group" id="manager">
                                    <label>Designation 
                                    </label>
                                    <select class="ps-select" title="Status" name="role" required="true" aria-required="true">
                                        <option>Select Designation</option>
                                        @foreach($designations as $designation)
                                        <option value="{{$designation->id}}">{{$designation->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Assign Manager 
                                    </label>
                                    <select class="ps-select" title="Status" name="parent" required="true" aria-required="true">
                                        <option>Select Manager</option>
                                        @if(!empty($managers))
                                            @foreach($managers as $manager)
                                            <option value="{{$manager->id}}">{{$manager->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Department 
                                    </label>
                                    <select class="ps-select" title="Status" name="department_id" required="true" aria-required="true">
                                        <option>Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Address
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Address" name="address" value="{{ old('address') }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Mobile<sup>*</sup>
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Mobile" name="telephone1" value="{{ old('telephone1') }}" required=""/>
                                </div>
                                <div class="form-group">
                                    <label>Telephone
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Telephone" name="telephone2" value="{{ old('telephone2') }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Profile Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="file" type="file" placeholder="">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>CNIC Front Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicf" type="file" placeholder="">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>CNIC Back Image</label>
                                    <div class="form-group--nest">
                                        <input class="form-control mb-1" name="cnicb" type="file" placeholder="">
                                        <button class="ps-btn ps-btn--sm">Choose</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                        <div class="switch switch-lg switch-success">
                                            Enable: <input id="status" type="radio" name="status" data-plugin-ios-switch value="1"/>
                                            Disable: <input id="status" type="radio" name="status" data-plugin-ios-switch value="0"/>
                                        </div>
                                    
                                </div>
                            </div>
                        </figure>

                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('admin/users')}}">Cancel</a>
                <button class="ps-btn" type="submit">Submit</button>
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
        if(role != 1){
            $('#assignee').css('display','block');
        }else{
            $('#assignee').css('display','none');
        }
    });
});
</script>
@endsection