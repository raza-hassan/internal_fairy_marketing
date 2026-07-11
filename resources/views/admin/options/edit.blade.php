@extends('layouts.admin-app', ['activePage' => 'products', 'titlePage' => __('Options')])

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
                    <form method="post" action="{{ url('admin/options/update', $options) }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('put')
                        <input type="hidden" name="attributes_id" value="{{$options->attributes_id}}">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Name</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('title', $options->title) }}" required="true" aria-required="true"/>
                                @if ($errors->has('title'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPassword">&nbsp;</label>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
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