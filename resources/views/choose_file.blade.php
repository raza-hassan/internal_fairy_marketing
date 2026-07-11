@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])
@section('content')

@include('admin.products.sidebar')

<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div>
            <h3>Update Product Prices With CSV File</h3>
            <p>Import File</p>
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

    <section class="ps-new-item">
        <form method="post" action="{{url('update/product/prices') }}" autocomplete="on" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('get')

            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Select File</figcaption>
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
                <a class="ps-btn ps-btn--black" href="{{url('/')}}">Cancel</a>
                <button class="ps-btn" type="submit">Submit</button>
            </div>
        </form>
    </section>
</div>

@endsection
