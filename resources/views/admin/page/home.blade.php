@extends('layouts.admin-app', ['activePage' => 'home', 'titlePage' => __('Home')])

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Create Home</h2>
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

                <form method="post" action="{{ url('admin/page/home') }}" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    @method('post')
                    <?php
                    $decodefeature = json_decode($feature->meta_value);
                    ?>
                    <!--Feature Image-->
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Name</label>
                            <div class="col-md-6">
                                <input class="form-control" name="featurename" type="text" placeholder="{{ __('Name') }}" value="{{ old('featurename', $decodefeature->featurename) }}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Main Feature Image</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="featureimage"/>
                                <input type="hidden" class="form-control" name="oldfeatureimage" value="{{$decodefeature->featureimage}}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Description</label>
                            <div class="col-md-9">
                                <textarea name="featurecontent" placeholder="Description" data-plugin-markdown-editor rows="5">{{ old('featurecontent', $decodefeature->featurecontent) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="panel-body">
                        <div class="secondslidersection">
                            <?php
                            $decodesecondslider = json_decode($secondslider->meta_value);
                            foreach ($decodesecondslider as $decodesecondslider) {
                                ?>
                                <div class="second-slider" style="margin-top: 20px;">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="inputPlaceholder">Second Slider</label>
                                        <div class="col-md-6">
                                            <input class="form-control" name="secslihead[]" type="text" placeholder="{{ __('Heading') }}" value="{{ old('secslihead', $decodesecondslider->secslihead) }}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Slider Text</label>
                                        <div class="col-md-9">
                                            <textarea name="secslidertext[]" class="form-control" placeholder="Slider Text" rows="5">{{ old('secslidertext', $decodesecondslider->secslidertext) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Slider Link</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="sliderlink[]" type="text" placeholder="{{ __('Link') }}" value="{{ old('sliderlink', $decodesecondslider->sliderlink) }}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Images</label>
                                        <div class="col-md-9">
                                            <input type="file" name="secslideimage[]" class="form-control">
                                            <input type="hidden" name="oldsecslideimage[]" value="{{$decodesecondslider->secslideimage}}">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <br>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <a href="javascript:void(0);" class="new-second-section">Add New</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="panel-body">
                        <div class="thirdslidersection">
                            <?php
                            $decodethirdslider = array();
                            $decodethirdslider = json_decode($thirdslider->meta_value);
                            foreach ($decodethirdslider as $decodethirdslider) {
                                ?>
                                <div class="third-slider" style="margin-top: 20px;">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="inputPlaceholder">Third Slider</label>
                                        <div class="col-md-6">
                                            <input class="form-control" name="thislihead[]" type="text" placeholder="{{ __('Heading') }}" value="{{ old('thislihead',$decodethirdslider->thislihead) }}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Slider Text</label>
                                        <div class="col-md-9">
                                            <textarea name="thislidertext[]" class="form-control" placeholder="Slider Text" rows="5">{{ old('thislidertext',$decodethirdslider->thislidertext) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Slider Link</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="thisliderlink[]" type="text" placeholder="{{ __('Link') }}" value="{{ old('thisliderlink',$decodethirdslider->thisliderlink) }}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Images</label>
                                        <div class="col-md-9">
                                            <input type="file" name="thislideimage[]" class="form-control">
                                            <input type="hidden" name="oldthislideimage[]" value="{{$decodethirdslider->thirdslideimage}}">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <br>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <a href="javascript:void(0);" class="new-third-section">Add New</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--Consciously Cool-->
                    <?php
                    $decodeconsciosly = json_decode($consciosly->meta_value);
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Consciously Cool</label>
                            <div class="col-md-6">
                                <input class="form-control" name="coolhead" type="text" placeholder="{{ __('Heading') }}" value="{{ old('coolhead',$decodeconsciosly->coolhead) }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Slider Text</label>
                            <div class="col-md-9">
                                <textarea name="cooldescription" class="form-control" placeholder="Slider Text" rows="5">{{ old('cooldescription',$decodeconsciosly->cooldescription) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Slider Image</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="coolfile"/>
                                <input type="hidden" class="form-control" name="oldcoolfile" value="{{$decodeconsciosly->coolfile}}"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--The Power of Play-->
                    <?php
                    $decodepowerplay = json_decode($powerplay->meta_value);
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">The Power of Play</label>
                            <div class="col-md-6">
                                <input class="form-control" name="pphead" type="text" placeholder="{{ __('Heading') }}" value="{{ old('pphead', $decodepowerplay->pphead) }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Slider Text</label>
                            <div class="col-md-9">
                                <textarea name="ppdescription" class="form-control" placeholder="Slider Text" rows="5">{{ old('ppdescription', $decodepowerplay->ppdescription) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Slider Image</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="ppimage"/>
                                <input type="hidden" class="form-control" name="oldppimage" value="{{$decodepowerplay->ppimage}}"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Nature Exploration -->
                    <?php
                    $decodenatureexplore = json_decode($natureexplore->meta_value);
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder"> Nature Exploration </label>
                            <div class="col-md-6">
                                <input class="form-control" name="nehead" type="text" placeholder="{{ __('Heading') }}" value="{{ old('nehead', $decodenatureexplore->nehead) }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Slider Text</label>
                            <div class="col-md-9">
                                <textarea name="nedescription" class="form-control" placeholder="Slider Text" rows="5">{{ old('nedescription', $decodenatureexplore->nedescription) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Slider Image</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="neimage"/>
                                <input type="hidden" class="form-control" name="oldneimage" value="{{$decodenatureexplore->neimage}}"/>
                            </div>
                        </div>


                    </div>
                    <hr>
                    <!--What's Trending-->
                    <?php
                    $decodetrendingproducts = json_decode($trendingproducts->meta_value);
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">What's Trending</label>
                            <div class="col-md-9">
                                <select multiple data-plugin-selectTwo class="form-control populate placeholder" name="trendingproducts[]" data-plugin-options='{ "placeholder": "Select Product", "allowClear": true }'>
                                    <optgroup label="Select Product">
                                        @foreach($products as $product)
                                        <option <?php
                                        if (in_array($product->id, json_decode($decodetrendingproducts))) {
                                            echo "selected";
                                        }
                                        ?> value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--Popular Categories-->
                    <?php
                    $decodepopularcate = json_decode($popularcate->meta_value);
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Popular Categories</label>
                            <div class="col-md-9">
                                <select multiple data-plugin-selectTwo class="form-control populate placeholder" name="popularcate[]" data-plugin-options='{ "placeholder": "Select Categories", "allowClear": true }'>
                                    <optgroup label="Select Categories">
                                        @foreach($category as $category)
                                        <option <?php
                                        if (in_array($category->id, json_decode($decodepopularcate))) {
                                            echo "selected";
                                        }
                                        ?> value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!--Featured Brands-->
                    <?php
                    $decodefeaturebrands = json_decode($featurebrands->meta_value);
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Featured Brands</label>
                            <div class="col-md-9">
                                <select multiple data-plugin-selectTwo class="form-control populate placeholder" name="featurebrands[]" data-plugin-options='{ "placeholder": "Select Brands", "allowClear": true }'>
                                    <optgroup label="Select Brands">
                                        @foreach($brands as $category)
                                        <option <?php
                                        if (in_array($category->id, json_decode($decodefeaturebrands))) {
                                            echo "selected";
                                        }
                                        ?>  value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!--Shop by Age-->
                    <?php
                    $decodeshopbyage = json_decode($shopbyage->meta_value);
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Shop by Age</label>
                            <div class="col-md-9">
                                <select multiple data-plugin-selectTwo class="form-control populate placeholder" name="shopbyage[]" data-plugin-options='{ "placeholder": "Select Ages", "allowClear": true }'>
                                    <optgroup label="Select Ages">
                                        @foreach($ages as $category)
                                        <option <?php
                                        if (in_array($category->id, json_decode($decodeshopbyage))) {
                                            echo "selected";
                                        }
                                        ?> value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!--Theme Worlds-->
                    <?php
                    $decodethemeworlds = json_decode($themeworlds->meta_value);
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPlaceholder">Theme Worlds</label>
                            <div class="col-md-9">
                                <select multiple data-plugin-selectTwo class="form-control populate placeholder" name="themeworlds[]" data-plugin-options='{ "placeholder": "Select Theme", "allowClear": true }'>
                                    <optgroup label="Select Theme">
                                        @foreach($themes as $category)
                                        <option <?php
                                        if (in_array($category->id, json_decode($decodethemeworlds))) {
                                            echo "selected";
                                        }
                                        ?> value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputPassword">&nbsp;</label>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                        </div>
                    </div>
                </form>
            </section>


        </div>
    </div>

    <!-- end: page -->
</section>
<div class="second-slider-clone" style="display: none;">
    <div class="second-slider" style="margin-top: 20px;">
        <div class="form-group">
            <label class="col-md-3 control-label" for="inputPlaceholder">Second Slider</label>
            <div class="col-md-6">
                <input class="form-control" name="secslihead[]" type="text" placeholder="{{ __('Heading') }}" value="{{ old('secslihead') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Slider Text</label>
            <div class="col-md-9">
                <textarea name="secslidertext[]" class="form-control" placeholder="Slider Text" rows="5">{{ old('slidertext') }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Slider Link</label>
            <div class="col-md-9">
                <input class="form-control" name="sliderlink[]" type="text" placeholder="{{ __('Link') }}" value="{{ old('sliderlink') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Images</label>
            <div class="col-md-9">
                <input type="file" name="secslideimage[]" class="form-control">
            </div>
        </div>
    </div>
</div>
<div class="third-slider-clone" style="display: none;">
    <div class="third-slider" style="margin-top: 20px;">
        <div class="form-group">
            <label class="col-md-3 control-label" for="inputPlaceholder">Third Slider</label>
            <div class="col-md-6">
                <input class="form-control" name="thislihead[]" type="text" placeholder="{{ __('Heading') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Slider Text</label>
            <div class="col-md-9">
                <textarea name="thislidertext[]" class="form-control" placeholder="Slider Text" rows="5"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Slider Link</label>
            <div class="col-md-9">
                <input class="form-control" name="thisliderlink[]" type="text" placeholder="{{ __('Link') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Images</label>
            <div class="col-md-9">
                <input type="file" name="thislideimage[]" class="form-control">
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
jQuery(document).ready(function () {
    jQuery('.new-second-section').click(function (e) {
        e.preventDefault();
        var html = $('.second-slider-clone').html();
        $('.secondslidersection').append(html);
    });
    jQuery('.new-third-section').click(function (e) {
        e.preventDefault();
        var html = $('.third-slider-clone').html();
        $('.thirdslidersection').append(html);
    });
});

</script>