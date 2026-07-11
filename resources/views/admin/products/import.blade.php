@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])
@section('content')
@include('admin.products.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Product</h3>
            <p>Imports</p>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </header>
    <style>
        .import_section{
            margin-bottom: 15px;
            border-bottom: 2px solid #446161;
            padding:10px;
        }
    </style>
    <section class="ps-new-item import_section">
        <form method="post" action="{{ url('file-import') }}" autocomplete="on" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Product Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Import File</label>
                                    <input class="form-control" name="file" type="file"/>
                                </div>
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('admin/inventory')}}">Cancel</a>
                <button class="ps-btn" type="submit">Submit</button>
            </div>
        </form>
    </section>

    <section class="ps-new-item import_section" >
        <form method="post" action="{{ url('inventory-sizes-update') }}" autocomplete="on" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Update Product Sizes</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Import File</label>
                                    <input class="form-control" name="file" type="file"/>
                                </div>
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('admin/inventory')}}">Cancel</a>
                <button class="ps-btn" type="submit">Submit</button>
            </div>
        </form>
    </section>

</div>
@endsection
