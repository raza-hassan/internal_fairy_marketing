@extends('layouts.admin-app', ['activePage' => 'price-module', 'titlePage' => __('Module')])

@section('content')

<section role="main" class="content-body">
    <header class="page-header">
        <h2>Create Module</h2>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="panel-body">
                    <form method="post" action="{{ url('admin/module/store') }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('post')

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">TITILE</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('tittle') ? ' is-invalid' : '' }}" name="tittle" id="input-tittle" type="text" placeholder="{{ __('tittle') }}" value="{{ old('tittle') }}" required="true" aria-required="true"/>
                                @if ($errors->has('tittle'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('tittle') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Type</label>
                            <div class="col-md-6">
                                <select name="rule" data-plugin-selectTwo class="form-control populate placeholder" data-plugin-options='{ "placeholder": "Select Attribute", "allowClear": true }'>
                                    <option >Select Type</option>
                                    <option value="0">Percentage</option>
                                    <option value="1">Fixed</option>
                                </select>
                                @if ($errors->has('rule'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('rule') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPassword">Price</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" input  type="text" name="price" id="input-price" placeholder="{{ __('price') }}" value="" required />
                                @if ($errors->has('price'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('price') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Start Date</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('sdate') ? ' is-invalid' : '' }}" input  type="date" name="sdate" id="input-sdate" placeholder="{{ __('sdate') }}" value="" required />
                                @if ($errors->has('sdate'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('sdate') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">End Date</label>
                            <div class="col-md-6">
                                <input class="form-control{{ $errors->has('edate') ? ' is-invalid' : '' }}" input  type="date" name="edate" id="input-edate" placeholder="{{ __('edate') }}" value="" required />
                                @if ($errors->has('edate'))
                                <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('edate') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"> Category</label>
                            <div class="col-md-6">
                                <select multiple data-plugin-selectTwo class="form-control populate placeholder" name="categories[]" data-plugin-options='{ "placeholder": "Select Category", "allowClear": true }' id="category">
                                    <optgroup label="Select Category">
                                        @if($category)
                                        @foreach($category as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                        @endif
                                    </optgroup>
                                </select>
                            </div>
                        </div>
<!--                        <div class="sub-category-div">

                        </div>-->

                        <div class="form-group">
                            <label class="col-md-3 control-label">Status</label>
                            <div class="col-md-9">
                                <div class="switch switch-lg switch-success">
                                    <input type="checkbox" name="status" data-plugin-ios-switch checked="checked" value="1"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPassword">&nbsp;</label>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </section>


        </div>
    </div>

    <!-- end: page -->
</section>

@endsection
<!--<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.selectall', function (e) {
        if ($(this).is(':checked')) {
            $(this).parent().parent().find('.sub_category').prop('checked', true);
        } else {
            $(this).parent().parent().find('.sub_category').prop('checked', false);
        }
//        alert('dfgdsfg');
    });

    $(document).on('change', '#category', function (e) {
        e.preventDefault();
        var category_id = $(this).val();

//        alert(category_id); return false;
        var _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "{{ url('admin/get_subcategory') }}",
            data: {category_id: category_id, _token: _token},
            success: function (data) {
                $('.sub-category-div').html(data);
            }
        });
    });
});

</script>-->
