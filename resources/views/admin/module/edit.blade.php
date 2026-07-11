@extends('layouts.admin-app', ['activePage' => 'product', 'titlePage' => __('Edit Module')])

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Edit Module</h2>
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
                    <form method="post" action="{{ url('admin/module/update', $module) }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Tittle</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('tittle') ? ' is-invalid' : '' }}" name="tittle" id="input-tittle" type="text" placeholder="{{ __('tittle') }}" value="{{ old('tittle', $module->tittle) }}" required="true" aria-required="true"/>
                                @if ($errors->has('tittle'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('tittle') }}</span>
                                @endif
                            </div>
                        </div>
 
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Rule</label>
                            <div class="col-md-6">
                                <select name="rule" data-plugin-selectTwo class="form-control populate placeholder" data-plugin-options='{ "placeholder": "Select Rule", "allowClear": true }'>
                                    
                                    <option value="0" <?php if ($module->rule == '0') { echo "selected"; } ?>>Percentage</option>
                                    <option value="1"  <?php if ($module->rule == '1') { echo "selected"; } ?>>Fixed</option>
                                </select>
                            </div>
                        </div>
                       
                      

                       <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Price</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" id="input-price" type="text" placeholder="{{ __('price') }}" value="{{ old('price', $module->price) }}" required="true" aria-required="true"/>
                                @if ($errors->has('price'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('price') }}</span>
                                @endif
                            </div>
                        </div>

                             <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Start Date</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('sdate') ? ' is-invalid' : '' }}" name="sdate" id="input-sdate" type="date" placeholder="{{ __('sdate') }}" value="{{ old('sdate', $module->sdate) }}" required="true" aria-required="true"/>
                                @if ($errors->has('sdate'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('sdate') }}</span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">End Date</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('edate') ? ' is-invalid' : '' }}" name="edate" id="input-edate" type="date" placeholder="{{ __('edate') }}" value="{{ old('edate', $module->edate) }}" required="true" aria-required="true"/>
                                @if ($errors->has('edate'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('edate') }}</span>
                                @endif
                            </div>
                        </div>
                        <?php
                                $cats = array();
                                
                                foreach ($module->categories as $category) {
                                    $cats[] = $category->id;
                                }
                                ?>

                        <div class="form-group">
                                    <label class="col-md-3 control-label"> Category</label>
                                    <div class="col-md-6">
                                        <select multiple data-plugin-selectTwo class="form-control populate placeholder" name="categories[]" data-plugin-options='{ "placeholder": "Select Category", "allowClear": true }'>
                                            <optgroup label="Select Category">
                                                @if($categories)
                                                @foreach($categories as $category)
                                                <option value="{{$category->id}}"<?php
                                                if (in_array($category->id, $cats)) {
                                                    echo "selected";
                                                }
                                                ?>>{{$category->name}}</option>
                                                @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                        
                    
                        <div class="form-group">
                            <label class="col-md-3 control-label">Status</label>
                            <div class="col-md-9">
                                <div class="switch switch-lg switch-success">
                                    <input type="checkbox" name="status" data-plugin-ios-switch <?php if( $module->status == 1 ){ echo "checked"; } ?> value="1" />
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