@extends('layouts.admin-app', ['activePage' => 'products', 'titlePage' => __('Categories')])

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
            <h2 class="panel-title">Category</h2>
            <div class="col-12 text-right">
                <a href="{{ url('admin/option/create') }}" class="btn btn-sm btn-primary">{{ __('Add Option') }}</a>
            </div>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                        <th class="hidden-phone">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($options as $option)

                    <tr>
                        <td>
                            {{ $option->key }}
                        </td>
                        <td>
                            {{ $option->value }}
                        </td>
                        <td class="td-actions text-right">

                            <form action="{{ url('admin/option/destroy', $option) }}" method="post">
                                @csrf
                                @method('delete')

                                <a rel="tooltip" class="btn btn-success btn-link" href="{{ url('admin/option/edit', $option) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                </a>
                                <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                    <i class="material-icons">close</i>
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

@endsection