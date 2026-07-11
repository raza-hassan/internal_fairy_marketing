@extends('layouts.admin-app', ['activePage' => 'user', 'titlePage' => __('User Management')])

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Edit User</h2>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
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
                <div class="panel-body">
                    <form method="post" action="{{ url('outer/profile/update') }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Name</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required="true" aria-required="true"/>
                                @if ($errors->has('name'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Email</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-name" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" required="true" aria-required="true"/>
                                @if ($errors->has('email'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPassword">&nbsp;</label>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <form method="post" action="{{ url('outer/profile/password') }}" class="form-horizontal">
                        @csrf
                        @method('put')
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

                        <!--                        <div class="card-footer ml-auto mr-auto">
                                                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                                </div>-->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPassword">&nbsp;</label>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">{{ __('Change Password') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </section>


        </div>
    </div>

    <!-- end: page -->
</section>
@endsection
