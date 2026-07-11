@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

@section('content')

@include('admin.products.sidebar')

<div class="ps-main__wrapper">

    <header class="header--dashboard">

        <div class="header__left">

            <h3>Inventory</h3>

        </div>

        <div class="header__center">

            <a class="ps-btn important" href="{{ url('admin/inventory/import') }}"><i class="icon icon-plus mr-2"></i>Import Product</a>

        </div>

        <div class="header__right">

            <a class="ps-btn success" href="{{ url('admin/inventory/create') }}"><i class="icon icon-plus mr-2"></i>Create Product</a>

        </div>

    </header>

    @if (session('status'))

    <div class="row">

        <div class="col-sm-12">

            <div class="alert alert-success">

                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>

                <strong>Success! </strong> {{ session('status') }}

            </div>

        </div>

    </div>

    @endif

    <section class="ps-items-listing">

        <div class="ps-section__header">

            <div class="ps-section__filter">

                <form class="ps-form--filter" action="{{url('admin/inventory-search')}}" method="get">

                    @csrf
                    @method('put')

                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Unit ID </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="form-control" name="unit_id">

                                        <option value="0">All</option>

                                        @foreach($products as $product)

                                            <option value="{{$product->id}}">{{$product->name}}</option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Type </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="form-control" name="category_id">

                                        <option value="0">All</option>

                                        @foreach($categories as $category)

                                            <option value="{{$category->id}}">{{$category->name}}</option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Projects </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="form-control" name="project_id">

                                        <option value="0">All</option>

                                        @foreach($projects as $project)

                                        <option value="{{$project->id}}">{{$project->name}}</option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                            <div class="form-group">

                                <label>Status </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="form-control" name="status">

                                        <option value="">Do Nothing</option>

                                        <option value="Available">Available</option>

                                        <option value="Hold">Hold</option>

                                        <option value="Reserved">Reserved</option>

                                        <option value="Sold">Sold</option>

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

                            <div class="form-group"><label>&nbsp; </label>

                                <div class="bootstrap-select fm-cmp-mg">

                                    <input class="ps-btn success form-control" type="submit" name="submit" value="Filter">

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <div class="ps-section__content">

            <div class="table-responsive">

                <table class="table ps-table">

                    <thead>

                        <tr>

                            <th>Name</th>

                            <th>Project</th>

                            <th>Type</th>

                            <th>Floor</th>

                            <th>Covered Area</th>

                            <th>Price</th>

                            <th>Status</th>

                            <th>Sold By</th>

                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($records as $record)

                        <tr class="gradeX">

                             <td>

                                {{ $record->name }}

                            </td>

                            <td>

                                <?php echo $record->projectname->name; ?>

                            </td>

                            <td>

                                <?php

                                if ($record->type != '')

                                {

                                    echo $record->type;

                                }

                                else

                                {

                                    // echo $record->category->name;



                                    if ($record->category != '')

                                    {

                                        echo $record->category->name;

                                    }

                                }

                                ?>

                            </td>

                            <td>

                                {{ $record->floor }}

                            </td>

                            <td>

                                <?php

                                if ($record->carea != '') {

                                    echo $record->carea;

                                } else {

                                    echo $record->garea;

                                }

                                ?>

                            </td>

                            <td>

                                {{ $record->price }}

                            </td>

                            <td>

                                {{ $record->status }}

                            </td>

                            <td>

                                @if($record->status == 'Sold')

                                {{ $record->sold_by }}

                                @else

                                Nothing

                                @endif

                            </td>

                            <td class="td-actions text-right">

                                @if($record->status != 'Sold')

                                <form action="{{ url('admin/inventory/destroy', $record) }}" method="post">

                                    @csrf

                                    @method('delete')

                                    <a rel="tooltip" href="{{ url('admin/inventory/edit', $record) }}" data-original-title="" title="">

                                        <i class="fa fa-edit" aria-hidden="true"></i>

                                    </a>

                                    @if(Auth::user()->role == 0)

                                    <button type="button" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">

                                        <i class="fa fa-trash" aria-hidden="true"></i>

                                    </button>

                                    @endif

                                </form>

                                @else

                                Edit Blcok

                                 @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <div class="ps-section__footer">

            {{ $records->render() }}

            <p>Show {{ $records->count() }} in {{$records->total()}} items.</p>

            <ul class="pagination">

                <li><a href="#"><i class="icon icon-chevron-left"></i></a></li>

                <li class="active"><a href="#">1</a></li>

                <li><a href="#">2</a></li>

                <li><a href="#">3</a></li>

                <li><a href="#"><i class="icon-chevron-right"></i></a></li>

            </ul>

        </div>

    </section>

</div>

@endsection

