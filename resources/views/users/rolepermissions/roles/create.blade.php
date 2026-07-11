@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.rolepermissions.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3> Create Role</h3>
        </div>
    </header>

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

    @if (count($errors) > 0)
        <div class = "alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <section class="ps-new-item">

        <div class="ps-section__content">

            <form method="post" action="{{ url('role/store') }}">
                @csrf

                <div class="ps-form__content">

                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group">
                                <label>Role Name </label>
                                <div>
                                    <input class="form-control" type="text" name="name" type="text" required placeholder="{{ __('Role Name') }}" value="{{old('name')}}">
                                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ps-form__bottom">
                        <a class="ps-btn ps-btn--black" href="{{url('roles')}}">Cancel</a>
                        <button class="ps-btn" type="submit">Submit</button>
                    </div>

                </div>

            </form>

        </div>

    </section>
</div>
@endsection
