@extends('layouts.admin-app', ['activePage' => 'products', 'titlePage' => __('Options')])

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
            <h2 class="panel-title"><strong style="color: #4e6d5d;">(<?php
                    if (count($records) > 0) {
                        echo $records[0]->attribute->name;
                    }
                    ?>)</strong> Options</h2>
            <div class="col-12 text-right">
                <a class="modal-with-form btn btn-sm btn-primary" href="#option">Create Option</a>
                <a href="{{ url('admin/attributes/') }}" class="btn btn-sm btn-primary">{{ __('Back to Attribute') }}</a>
            </div>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th class="hidden-phone">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($records as $record)

                    <tr class="gradeX">

                        <td>
                            {{ $record->title }}
                        </td>
                        <td class="td-actions text-right">

                            <form action="{{ url('admin/options/destroy', $record) }}" method="post">
                                @csrf
                                @method('delete')

                                <a rel="tooltip" class="btn btn-success btn-link" href="{{ url('admin/options/edit', $record) }}" data-original-title="" title="">
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
        <form method="post" action="{{ url('admin/options/store') }}" autocomplete="off" class="form-horizontal">
            <header class="panel-heading">
                <h2 class="panel-title">Create Variation</h2>
            </header>
            <div class="panel-body">

                @csrf
                @method('post')
                <input type="hidden" name="attributes_id" value="{{$attrid}}">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputPlaceholder">Title</label>
                    <div class="col-md-6">
                        <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="input-name" type="text" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required="true" aria-required="true"/>
                        @if ($errors->has('title'))
                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('title') }}</span>
                        @endif
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