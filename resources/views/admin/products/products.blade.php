@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

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
            <h2 class="panel-title">Products</h2>
            <div class="col-lg-6 text-right">
                <a href="{{ url('admin/products/create') }}" class="btn btn-sm btn-primary">{{ __('Create Product') }}</a>
            </div>
            <div class="col-lg-6 text-left">
                <form method="post" action="{{url('admin/product/search') }}">
                    @csrf
                    @method('post')
                    <input type="text" name="search" value="{{$search}}" class="form-control" placeholder="Search By Sku">
                    <button type="submit" class="btn btn-primary">Add</button>
                </form
            </div>
            
            <p>All Products : {{ $total }}</p>
            
        </header>
        
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Sku</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Status</th>
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
                            {{ $record->sku }}
                        </td>
                        <td>
                            {{ $record->stock }}
                        </td>
                        <td>
                            {{ number_format($record->price , 2) }}
                        </td>
                        
                        <td>
                            @if($record->status == 1)
                                Published
                            @else
                                Draft
                            @endif
                        </td>
                        <td class="td-actions text-right">

                            <form action="{{ url('admin/product/destroy', $record) }}" method="post">
                                @csrf
                                @method('delete')
                                
                                <a rel="tooltip" class="btn btn-success btn-link" href="{{ url('admin/product/edit', $record) }}" data-original-title="" title="">
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

@endsection