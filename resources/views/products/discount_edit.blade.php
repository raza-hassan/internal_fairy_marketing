
@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

@section('content')

    @include('products.sidebar')

    <div class="ps-main__wrapper">
        <header class="header--dashboard">
            <div class="header__left">
                <h3>Edit Discount</h3>
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
            <form method="post" action="{{ url('discount/update', $discount) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="ps-form__content">

                    <figure class="ps-block--form-box">
                        <figcaption>Discount Info</figcaption>
                        <div class="ps-block__content">

                            <div class="form-group">

                                <div class="row">

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 ">
                                        <label>Discount Amount Psqr/ft<sup>*</sup></label>
                                        <input class="form-control" name="sqft_amount" id="input-name" type="number" placeholder="{{ __('Discount Amount') }}" value="{{ old('sqft_amount', $discount->sqft_amount) }}" required="true" aria-required="true"/>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label>Token Amount<sup>*</sup></label>
                                        <input class="form-control" name="token_amount" id="input-name" type="number" placeholder="{{ __('Token Amount') }}" value="{{ old('token_amount', $discount->token_amount) }}" required="true" aria-required="true"/>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </figure>

                </div>
                <div class="ps-form__bottom">
                    <a class="ps-btn ps-btn--black" href="{{url('discountprice')}}">Cancel</a>
                    <button class="ps-btn" type="submit">Submit</button>
                </div>
            </form>
        </section>
    </div>


@endsection
