@extends('layouts.admin-app', ['activePage' => 'option', 'titlePage' => __('Create Option')])

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Create Option</h2>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="panel-body">
                    <form method="post" action="{{ url('admin/option/store') }}" class="form-horizontal">
                    @csrf
                    @method('post')

                    <div class="card ">
                        
                        <div class="card-body ">
                            @if (session('message'))
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="material-icons">close</i>
                                        </button>
                                        <span>{{ session('message') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="form-group">
                                <label class="col-sm-2 col-form-label" for="key">{{ __('Key') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('key') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('key') ? ' is-invalid' : '' }}" name="key" id="input-price" type="text" placeholder="{{ __('Enter key in lowercas') }}"/>
                                        @if ($errors->has('key'))
                                        <span id="password-error" class="error text-danger" for="key">{{ $errors->first('key') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-2 col-form-label" for="value">{{ __('Value') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('value') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" name="value" id="input-price" type="text" placeholder="{{ __('Enter Key Value') }}"/>
                                        @if ($errors->has('key'))
                                        <span id="password-error" class="error text-danger" for="value">{{ $errors->first('value') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Create Option') }}</button>
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