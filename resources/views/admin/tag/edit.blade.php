@extends('layouts.admin-app', ['activePage' => 'product', 'titlePage' => __('User Management')])

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Edit Tag</h2>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">

                <div class="panel-body">
                    <form method="post" action="{{ url('admin/tag/update', $tag) }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Name</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $tag->name) }}" required="true" aria-required="true"/>
                                @if ($errors->has('name'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Description</label>
                            <div class="col-md-6">
                                <textarea class="form-control" rows="3" id="textareaDefault" name="description">{{ old('description', $tag->description) }}</textarea>
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