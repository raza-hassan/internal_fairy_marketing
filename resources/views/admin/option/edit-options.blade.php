@extends('layouts.admin-app', ['activePage' => 'option', 'titlePage' => __('Edit Option')])

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Edit Option</h2>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">

                <div class="panel-body">
                   <form method="post" action="{{ url('admin/option/update', $option) }}" autocomplete="off" class="form-horizontal">
                    @csrf
                    @method('put')

                    <div class="card ">
                        
                        <div class="card-body ">
                            
                            <div class="form-group">
                                <label class="col-sm-2 col-form-label" for="key">{{ __('Key') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('key') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('key') ? ' is-invalid' : '' }}" name="key" id="input-price" type="text" value="{{$option->key}}"/>
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
                                        <input class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" name="value" id="input-price" type="text"  value="{{$option->value}}"/>
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