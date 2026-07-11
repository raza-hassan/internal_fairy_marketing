@extends('layouts.admin-app', ['activePage' => 'product', 'titlePage' => __('Attributes')])

@section('content')


<section role="main" class="content-body">
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
    <!-- start: page -->
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Attributes</h2>
            <div class="col-12 text-right">
                <!--<a href="{{ url('admin/attributes/create') }}" class="btn btn-sm btn-primary">{{ __('Add Attribute') }}</a>-->
                <a class="modal-with-form btn btn-sm btn-primary" href="#option">Add Attribute</a>
            </div>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Menu order</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Status</th>
                        <th>Variations</th>
                        <th class="hidden-phone">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($records as $record)

                    <tr class="gradeX">
                        <td>
                            {{ $record->name }}
                        </td>
                        <td>
                            {{ $record->menu_order }}
                        </td>
                        <td>
                            {{ ucfirst($record->type) }}
                        </td>
                        
                        <td>
                            @if($record->required == 1)
                                Yes
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            @if($record->status == 1)
                                Yes
                            @else
                                No
                            @endif
                        </td>
                        
                        <td>
                            @if(count($record->variations))
                            <?php
                            $decode_variations = json_decode($record->variations);
                            foreach ($decode_variations as $variation) {
                                ?>
                                <a href="{{url('admin/options/edit',$variation->id)}}"><?php echo $variation->title; ?></a>, 
                                <?php
                            }
                            ?>
                            @endif
                        </td>

                        
                        <td class="td-actions text-right">

                            <form action="{{ url('admin/attributes/destroy', $record) }}" method="post">
                                @csrf
                                @method('delete')

                                <a rel="tooltip" class="btn btn-success btn-link" href="{{ url('admin/options', $record) }}" data-original-title="" title="">
                                    <i class="material-icons">Options</i>
                                    <div class="ripple-container"></div>
                                </a>
                                <a rel="tooltip" class="btn btn-success btn-link" href="{{ url('admin/attributes/edit', $record) }}" data-original-title="" title="">
                                    <i class="material-icons">Edit</i>
                                    <div class="ripple-container"></div>
                                </a>
                                <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">
                                    <i class="material-icons">Delete</i>
                                    <div class="ripple-container"></div>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </section>

    <!-- end: page -->
</section>
<div id="option" class="modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <form method="post" action="{{ url('admin/attributes/store') }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('post')
            <header class="panel-heading">
                <h2 class="panel-title">Create Attribute</h2>
            </header>
            <div class="panel-body">

               <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Name</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                                @if ($errors->has('name'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Type</label>
                            <div class="col-md-6">
                                <select name="type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="select">Select</option>
                                    <option value="radio">Radio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Menu Order</label>
                            <div class="col-md-6">
                                 <input class="form-control" name="menu_order" type="text" placeholder="{{ __('Menu Order') }}" value="{{ old('menu_order') }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Required</label>
                            <div class="col-md-9">
                                <div class="switch switch-lg switch-success">
                                    <input type="checkbox" name="required" data-plugin-ios-switch checked="checked" value="1"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Status</label>
                            <div class="col-md-9">
                                <div class="switch switch-lg switch-success">
                                    <input type="checkbox" name="status" data-plugin-ios-switch checked="checked" value="1"/>
                                </div>
                            </div>
                        </div>



            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                    </div>
                </div>
            </footer>
        </form>
    </section>
</div>
@endsection