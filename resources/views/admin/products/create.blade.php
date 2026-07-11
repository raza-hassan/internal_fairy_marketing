@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])
@section('content')
@include('admin.products.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Product</h3>
            <p>Add new Product</p>
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
        <form method="post" action="{{ url('admin/inventory/store') }}" autocomplete="on" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>General</figcaption>
                            <div class="ps-block__content">

                                <div class="form-group">
                                    <label>Unit-ID<sup>*</sup></label>
                                    <input class="form-control" name="unitid" required type="text" placeholder="{{ __('Unit ID') }}" value="{{ old('unitid') }}"/>
                                </div>

                                <div class="form-group">
                                    <label>Name<sup>*</sup>
                                    </label>
                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                                </div>
                                <div class="form-group">
                                    <label>Project<sup>*</sup></label>
                                    <select class="form-control" title="Project" name="project_id" required>
                                        <option value="" selected disabled>Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Category
                                    </label>
                                    <select class="form-control" title="Category" name="categories" id="categories">
                                        <option value="0">Select Type</option>
                                        @if($categories)
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="product_types" style="display: none;">
                                    <div class="form-group">
                                        <label>Type
                                        </label>
                                        <select class="form-control" title="type" name="type">
                                            <option value="0">Select Type</option>
                                            <option>Studio</option>
                                            <option>1 Bed</option>
                                            <option>2 Bed</option>
                                            <option>3 Bed</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Sub Type
                                        </label>
                                        <select class="form-control" title="subtype" name="subtype">
                                            <option value="0">Select Sub Type</option>
                                            <option>A</option>
                                            <option>B</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Gross Area<sup>*</sup></label>
                                    <input class="form-control" name="garea" type="text" placeholder="{{ __('Gross Area') }}" value="{{ old('carea') }}" required="true" aria-required="true"/>
                                </div>
                                <div class="form-group">
                                    <label>Covered Area<sup>*</sup></label>
                                    <input class="form-control covered_area" name="carea" type="text" placeholder="{{ __('Covered Area') }}" value="{{ old('carea') }}" required="true" aria-required="true"/>
                                </div>
                                <div class="form-group">
                                    <label>Floor<sup>*</sup></label>
                                    <select class="form-control" title="Floor" name="floor" required>

                                        {{-- <?php for ($counter = 1; $counter <= 15; $counter++) {
                                            ?>
                                            <option>{{$counter}}</option>
                                        <?php } $counter++  ?> --}}

                                        <option value="">Select Floor</option>
                                        <option value="Ground Floor">Ground Floor</option>
                                        <option value="1st Floor">1st Floor</option>
                                        <option value="2nd Floor">2nd Floor</option>
                                        <option value="3rd Floor">3rd Floor</option>
                                        <option value="4th Floor">4th Floor</option>
                                        <option value="5th Floor">5th Floor</option>
                                        <option value="6th Floor">6th Floor</option>
                                        <option value="7th Floor">7th Floor</option>
                                        <option value="8th Floor">8th Floor</option>
                                        <option value="9th Floor">9th Floor</option>
                                        <option value="10th Floor">10th Floor</option>
                                        <option value="11th Floor">11th Floor</option>
                                        <option value="12th Floor">12th Floor</option>
                                        <option value="13th Floor">13th Floor</option>
                                        <option value="14th Floor">14th Floor</option>
                                        <option value="15th Floor">15th Floor</option>
                                        <option value="16th Floor">16th Floor</option>
                                        <option value="17th Floor">17th Floor</option>
                                        <option value="18th Floor">18th Floor</option>
                                        <option value="19th Floor">19th Floor</option>
                                        <option value="20th Floor">20th Floor</option>
                                        <option value="21th Floor">21th Floor</option>
                                        <option value="22th Floor">22th Floor</option>
                                        <option value="23th Floor">23th Floor</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>SKU<sup>*</sup></label>
                                    <input class="form-control" name="sku" type="text" placeholder="{{ __('Sku code') }}" value="{{ old('sku') }}" required="true" aria-required="true"/>
                                </div>
                                <div class="form-group">
                                    <label>SBM NO.</label>
                                    <input class="form-control" name="sbm" type="text" placeholder="{{ __('SBM NO.') }}" value="{{ old('sbm') }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea id="summernote" rows="6" name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Discount (sqft)</label>
                                    <input class="form-control" name="discount" type="number" placeholder="{{ __('Discount Amount') }}" value="{{ old('discount') }}"/>
                                </div>
                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Product Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Price P/Sft</label>
                                    <input class="form-control psft" name="psft" type="text" placeholder="{{ __('Price P/Sft') }}" value="{{ old('psft',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Down Payment %</label>
                                    <input class="form-control dpaymentper" name="dpaymentper" type="text" placeholder="{{ __('Down Payment %') }}" value="{{ old('dpaymentper',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Down Payment</label>
                                    <input class="form-control dpayment" name="dpayment" type="text" placeholder="{{ __('Down Payment') }}" value="{{ old('dpayment',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Possession Amount %</label>
                                    <input class="form-control posperamount" name="posperamount" type="text" placeholder="{{ __('Possession Amount %') }}" value="{{ old('posperamount',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Possession Amount</label>
                                    <input class="form-control posamount" name="posamount" type="text" placeholder="{{ __('Possession Amount') }}" value="{{ old('posamount',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Remaining Payment</label>
                                    <input class="form-control rpayment" name="rpayment" type="text" placeholder="{{ __('Remaining Payment') }}" value="{{ old('rpayment', 0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Total Amount<sup>*</sup></label>
                                    <input class="form-control total_amount" name="price" type="text" placeholder="{{ __('Total Amount') }}" value="{{ old('price',0) }}" required aria-required="true"/>
                                </div>
                                <div class="form-group">
                                    <label>Quarterly Installment</label>
                                    <input class="form-control" name="qtrinstallment" type="text" placeholder="{{ __('Quarterly Installment') }}" value="{{ old('qtrinstallment',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Number of Quarterly Installment</label>
                                    <input class="form-control" name="numinstallment" type="text" placeholder="{{ __('Number of Quarterly Installment') }}" value="{{ old('numinstallment',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Monthly Installment</label>
                                    <input class="form-control" name="moninstallment" type="text" placeholder="{{ __('Monthly Installment') }}" value="{{ old('moninstallment',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Number of Monthly Installment</label>
                                    <input class="form-control" name="nummoninstallment" type="text" placeholder="{{ __('Number of Monthly Installment') }}" value="{{ old('nummoninstallment',0) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" title="Status" name="status" required >
                                        {{-- <option value="" disabled selected>Select Status</option> --}}
                                        <option value="Available">Available</option>
                                        <option value="Sold">Sold</option>
                                        <option value="Reserved">Reserved</option>
                                        <option value="Hold">Hold</option>
                                    </select>
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
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('change', '#categories', function (e) {
        e.preventDefault();
        var categories = $(this).val();
        if (categories == 1 || categories == 6) {
            $('.product_types').css('display', 'block');
        } else {
            $(".product_types").find('.form-control').val($("select option:first").val());
            $('.product_types').css('display', 'none');
        }
    });
    $(document).on('keyup', '.psft', function (e) {
        var persqrt = $(this).val();
        var carea = $('.covered_area').val();
        var totall = 0;
        totall = (parseFloat(persqrt) * parseFloat(carea));
        $('.total_amount').val(totall);
        $('.rpayment').val(totall);
        calculatoTotal();
    });
    $(document).on('keyup', '.dpaymentper', function (e) {
        var dpaymentper = $(this).val();
        var total = $('.total_amount').val();
        //console.log(dpaymentper);
        var percentage = (parseFloat(dpaymentper) / 100) * parseFloat(total);
        var totall = 0;
        totall = parseFloat(total) - parseFloat(percentage);
//        console.log(totall);
        $('.total_amount').val();
        $('.rpayment').val(totall);
        $('.dpayment').val(percentage);
        calculatoTotal();
    });
    $(document).on('keyup', '.posperamount', function (e) {
        var posperamount = $(this).val();
        var total = $('.total_amount').val();
        var dpayment = $('.dpayment').val();
        //console.log(dpaymentper);
        var percentage = (parseFloat(posperamount) / 100) * parseFloat(total);
        var totall = 0;
        totall = (parseFloat(total) - parseFloat(percentage))- parseFloat(dpayment);
//        console.log(totall);
        $('.total_amount').val();
        $('.posamount').val(percentage);
        $('.rpayment').val(totall);
        calculatoTotal();
    });
    $(document).on('keyup', '.covered_area', function (e) {
        var carea = $(this).val();
        var psft = $('.psft').val();
        var totall = 0;
        totall = (parseFloat(psft) * parseFloat(carea));
        $('.total_amount').val(totall);
        $('.rpayment').val(totall);
        calculatoTotal();
    });
    function calculatoTotal() {
        var total_amount = $('.total_amount').val();
        var dpayment = $('.dpayment').val();
        var posamount = $('.posamount').val();
//        var rpayment = $('.rpayment').val((parseFloat(total_amount) - parseFloat(dpayment)) - parseFloat(posamount));
    }
});
</script>
@endsection
