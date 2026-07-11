@extends('layouts.admin-app', ['activePage' => 'product', 'titlePage' => __('Edit Attribute')])

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Edit Attribute</h2>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            <section class="panel">

                <div class="panel-body">
                    <form method="post" action="{{ url('admin/attributes/update', $attributes) }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Name</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $attributes->name) }}" required="true" aria-required="true"/>
                                @if ($errors->has('name'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Menu Order</label>
                            <div class="col-md-6">
                                 <input class="form-control" name="menu_order" type="text" placeholder="{{ __('Menu Order') }}" value="{{ old('menu_order',$attributes->menu_order) }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Type</label>
                            <div class="col-md-6">
                                <select name="type" data-plugin-selectTwo class="form-control populate placeholder" data-plugin-options='{ "placeholder": "Select Attribute", "allowClear": true }'>
                                    
                                    <option value="select" <?php if ($attributes->type == 'Select') { echo "selected"; } ?>>Select</option>
                                    <option value="radio"  <?php if ($attributes->type == 'Radio') { echo "selected"; } ?>>Radio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Required</label>
                            <div class="col-md-9">
                                <div class="switch switch-lg switch-success">
                                    <input type="checkbox" name="required" data-plugin-ios-switch <?php if( $attributes->required == 1 ){ echo "checked"; } ?> value="1"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Status</label>
                            <div class="col-md-9">
                                <div class="switch switch-lg switch-success">
                                    <input type="checkbox" name="status" data-plugin-ios-switch <?php if( $attributes->status == 1 ){ echo "checked"; } ?> value="1"/>
                                </div>
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