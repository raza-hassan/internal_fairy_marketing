@extends('layouts.app', ['activePage' => 'newleads', 'titlePage' => __('Products')])

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
    <section class="ps-new-item">
        <form method="post" action="{{ url('facebook-leads-store') }}" autocomplete="on" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Product Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Offices </label>
                                    <div class="bootstrap-select fm-cmp-mg">

                                        <select class="ps-select  {{ $errors->has('office_id') ? ' is-invalid' : '' }}" id="input-office_id" style="width: 100%"  title="office Name" name="office_id" required="true" aria-required="true">
                                            <option selected disabled >Select Office</option>
                                                @foreach($offices as $office)
                                                    <option value="{{$office->id}}" @if(old('office_id')==$office->id ) selected  @endif  >{{$office->name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>

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
                <a class="ps-btn ps-btn--black" href="{{url('inventory')}}">Cancel</a>
                <button class="ps-btn" type="submit">Submit</button>
            </div>
        </form>
    </section>
</div>

@endsection
