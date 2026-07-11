@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])
@section('content')
@include('admin.products.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Edit Product</h3>
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
        <form method="post" action="{{ url('admin/inventory/update', $product) }}" autocomplete="on" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>General</figcaption>
                            <div class="ps-block__content">

                                <div class="form-group">
                                    <label>Unit-ID<sup>*</sup></label>
                                    <input class="form-control" name="unitid" required type="text" placeholder="{{ __('Unit ID') }}" value="{{ old('unitid' , $product->unitid) }}"/>
                                </div>

                                <div class="form-group">
                                    <label>Name<sup>*</sup>
                                    </label>
                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $product->name) }}" required="true" aria-required="true"/>
                                </div>
                                <div class="form-group">
                                    <label>Project<sup>*</sup>
                                    </label>
                                    <select class="form-control" title="Project" name="project_id" required="true" aria-required="true">
                                        <option value="0">Select Project</option>
                                        @if($projects)
                                        @foreach($projects as $project)
                                        <option  <?php if ($project->id == $product->project_id) {
                                            echo "selected";
                                        } ?> value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Category<sup>*</sup>  </label>
                                    <?php
                                    $cats = array();
                                    foreach ($product->categories as $category) {
                                        $cats[] = $category->id;
                                    }
                                    ?>
                                    <select class="form-control" title="Category" name="categories" id="categories" required="true" aria-required="true">
                                        <option value="0">Select Type</option>
                                        @if($categories)
                                        @foreach($categories as $category)
                                        <option  <?php if (in_array($category->id, $cats)) {echo "selected"; } ?> value="{{$category->id}}">  {{$category->name}}</option>
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
                                    <input class="form-control" name="garea" type="text" placeholder="{{ __('Gross Area') }}" value="{{ old('garea', $product->garea) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Covered Area</label>
                                    <input class="form-control covered_area" name="carea" type="text" placeholder="{{ __('Covered Area') }}" value="{{ old('carea', $product->carea) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Floor<sup>*</sup></label>
                                    <select class="form-control" title="Floor" name="floor" required>
                                        <option value="">Select Floor</option>
                                        <option value="Ground Floor"<?php if ($product->floor == 'Ground Floor') {echo 'selected';}?>>Ground Floor</option>
                                        <option value="1st Floor"   <?php if ($product->floor == '1st Floor' )   {echo 'selected';}?>>1st Floor</option>
                                        <option value="2nd Floor"   <?php if ($product->floor == '2nd Floor' )   {echo 'selected';}?>>2nd Floor</option>
                                        <option value="3rd Floor"   <?php if ($product->floor == '3rd Floor' )   {echo 'selected';}?>>3rd Floor</option>
                                        <option value="4th Floor"   <?php if ($product->floor == '4th Floor' )   {echo 'selected';}?>>4th Floor</option>
                                        <option value="5th Floor"   <?php if ($product->floor == '5th Floor' )   {echo 'selected';}?>>5th Floor</option>
                                        <option value="6th Floor"   <?php if ($product->floor == '6th Floor' )   {echo 'selected';}?>>6th Floor</option>
                                        <option value="7th Floor"   <?php if ($product->floor == '7th Floor' )   {echo 'selected';}?>>7th Floor</option>
                                        <option value="8th Floor"   <?php if ($product->floor == '8th Floor' )   {echo 'selected';}?>>8th Floor</option>
                                        <option value="9th Floor"   <?php if ($product->floor == '9th Floor' )   {echo 'selected';}?>>9th Floor</option>
                                        <option value="10th Floor"  <?php if ($product->floor ==  '10th Floor' ) {echo 'selected';}?>>10th Floor</option>
                                        <option value="11th Floor"  <?php if ($product->floor ==  '11th Floor' ) {echo 'selected';}?>>11th Floor</option>
                                        <option value="12th Floor"  <?php if ($product->floor ==  '12th Floor' ) {echo 'selected';}?>>12th Floor</option>
                                        <option value="13th Floor"  <?php if ($product->floor ==  '13th Floor' ) {echo 'selected';}?>>13th Floor</option>
                                        <option value="14th Floor"  <?php if ($product->floor ==  '14th Floor' ) {echo 'selected';}?>>14th Floor</option>
                                        <option value="15th Floor"  <?php if ($product->floor ==  '15th Floor' ) {echo 'selected';}?>>15th Floor</option>
                                        <option value="16th Floor"  <?php if ($product->floor ==  '16th Floor' ) {echo 'selected';}?>>16th Floor</option>
                                        <option value="17th Floor"  <?php if ($product->floor ==  '17th Floor' ) {echo 'selected';}?>>17th Floor</option>
                                        <option value="18th Floor"  <?php if ($product->floor ==  '18th Floor' ) {echo 'selected';}?>>18th Floor</option>
                                        <option value="19th Floor"  <?php if ($product->floor ==  '19th Floor' ) {echo 'selected';}?>>19th Floor</option>
                                        <option value="20th Floor"  <?php if ($product->floor ==  '20th Floor' ) {echo 'selected';}?>>20th Floor</option>
                                        <option value="21th Floor"  <?php if ($product->floor ==  '21th Floor' ) {echo 'selected';}?>>21th Floor</option>
                                        <option value="22th Floor"  <?php if ($product->floor ==  '22th Floor' ) {echo 'selected';}?>>22th Floor</option>
                                        <option value="23th Floor"  <?php if ($product->floor ==  '23th Floor' ) {echo 'selected';}?>>23th Floor</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>SKU</label>
                                    <input class="form-control" name="sku" type="text" placeholder="{{ __('Sku code') }}" value="{{ old('sku', $product->sku) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>SBM NO.</label>
                                    <input class="form-control" name="sbm" type="text" placeholder="{{ __('SBM NO.') }}" value="{{ old('sbm', $product->sbm) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea id="summernote" rows="6" name="description">{{ old('description', $product->description) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Discount (sqft)</label>
                                    <input class="form-control" name="discount" type="number" placeholder="{{ __('Discount Amount') }}" value="{{ old('discount', $product->discount) }}"/>
                                </div>
                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Product Information</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Price Pr/Sft</label>
                                    <input class="form-control psft" name="psft" type="text" placeholder="{{ __('Price P/Sft') }}" value="{{ old('psft', $product->psft) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Down Payment %</label>
                                    <input class="form-control dpaymentper" name="dpaymentper" type="text" placeholder="{{ __('Down Payment %') }}" value="{{ old('dpaymentper', $product->dpaymentper) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Down Payment</label>
                                    <input class="form-control dpayment" name="dpayment" type="text" placeholder="{{ __('Down Payment') }}" value="{{ old('dpayment', $product->dpayment) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Possession Amount %</label>
                                    <input class="form-control posperamount" name="posperamount" type="text" placeholder="{{ __('Possession Amount %') }}" value="{{ old('posperamount', $product->posperamount) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Possession Amount</label>
                                    <input class="form-control posamount" name="posamount" type="text" placeholder="{{ __('Possession Amount') }}" value="{{ old('posamount',$product->posamount) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Remaining Payment</label>
                                    <input class="form-control rpayment" name="rpayment" type="text" placeholder="{{ __('Remaining Payment') }}" value="{{ old('rpayment', $product->rpayment) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Total Amount<sup>*</sup></label>
                                    <input class="form-control total_amount" name="price" type="text" placeholder="{{ __('Total Amount') }}" value="{{ old('price', $product->price) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Quarterly Installment</label>
                                    <input class="form-control" name="qtrinstallment" type="text" placeholder="{{ __('Quarterly Installment') }}" value="{{ old('qtrinstallment', $product->qtrinstallment) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Number of Quarterly Installment</label>
                                    <input class="form-control" name="numinstallment" type="text" placeholder="{{ __('Number of Quarterly Installment') }}" value="{{ old('numinstallment', $product->numinstallment) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Monthly Installment</label>
                                    <input class="form-control" name="moninstallment" type="text" placeholder="{{ __('Monthly Installment') }}" value="{{ old('moninstallment', $product->moninstallment) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Number of Monthly Installment</label>
                                    <input class="form-control" name="nummoninstallment" type="text" placeholder="{{ __('Number of Monthly Installment') }}" value="{{ old('nummoninstallment', $product->nummoninstallment) }}"/>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" title="Status" name="status">
                                        <option>Select Status</option>
                                        <option <?php
                                            if ($product->status == 'Available') {
                                                echo 'selected';
                                            }
                                        ?>>Available</option>
                                        <option <?php
                                            if ($product->status == 'Sold') {
                                                echo 'selected';
                                            }
                                        ?>>Sold</option>
                                        <option <?php
                                            if ($product->status == 'Reserved') {
                                                echo 'selected';
                                            }
                                        ?>>Reserved</option>
                                        <option <?php
                                            if ($product->status == 'Hold') {
                                                echo 'selected';
                                            }
                                        ?>>Hold</option>
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
