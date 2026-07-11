@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

@section('content')
@include('products.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Inventory Prices</h3>
        </div>
        <div class="header__center">

        </div>
        <div class="header__right"></div>
        @if (session('status'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                    <strong>Success! </strong> {{ session('status') }}
                </div> 
            </div>
        </div>
        @endif
    </header>
    <section class="ps-items-listing">

        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('inventory-update')}}" method="get">
<!--                    @csrf
                    @method('post')-->
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Projects <sup>*</sup></label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="project_id" required="required">
                                        @foreach($projects as $project)
                                        <option <?php if(app('request')->input('project_id') == $project->id){ echo 'selected'; } ?> value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Floor </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="floor">
                                        <option value="0">All</option>
                                        <option <?php if(app('request')->input('floor') == 'Ground Floor'){ echo 'selected'; } ?> value="Ground Floor">Ground Floor</option>
                                        <option <?php if(app('request')->input('floor') == '1st Floor'){ echo 'selected'; } ?> value="1st Floor">1st Floor</option>
                                        <option <?php if(app('request')->input('floor') == '2nd Floor'){ echo 'selected'; } ?> value="2nd Floor">2nd Floor</option>
                                        <option <?php if(app('request')->input('floor') == '3rd Floor'){ echo 'selected'; } ?> value="3rd Floor">3rd Floor</option>
                                        <option <?php if(app('request')->input('floor') == '4th Floor'){ echo 'selected'; } ?> value="4th Floor">4th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '5th Floor'){ echo 'selected'; } ?> value="5th Floor">5th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '6th Floor'){ echo 'selected'; } ?> value="6th Floor">6th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '7th Floor'){ echo 'selected'; } ?> value="7th Floor">7th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '8th Floor'){ echo 'selected'; } ?> value="8th Floor">8th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '9th Floor'){ echo 'selected'; } ?> value="9th Floor">9th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '10th Floor'){ echo 'selected'; } ?> value="10th Floor">10th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '11th Floor'){ echo 'selected'; } ?> value="11th Floor">11th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '12th Floor'){ echo 'selected'; } ?> value="12th Floor">12th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '13th Floor'){ echo 'selected'; } ?> value="13th Floor">13th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '14th Floor'){ echo 'selected'; } ?> value="14th Floor">14th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '15th Floor'){ echo 'selected'; } ?> value="15th Floor">15th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '16th Floor'){ echo 'selected'; } ?> value="16th Floor">16th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '17th Floor'){ echo 'selected'; } ?> value="17th Floor">17th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '18th Floor'){ echo 'selected'; } ?> value="18th Floor">18th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '19th Floor'){ echo 'selected'; } ?> value="19th Floor">19th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '20th Floor'){ echo 'selected'; } ?> value="20th Floor">20th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '21th Floor'){ echo 'selected'; } ?> value="21th Floor">21th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '22th Floor'){ echo 'selected'; } ?> value="22th Floor">22th Floor</option>
                                        <option <?php if(app('request')->input('floor') == '23th Floor'){ echo 'selected'; } ?> value="23th Floor">23th Floor</option>
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
                                        <option <?php if(app('request')->input('category_id') == $category->id){ echo 'selected'; } ?> value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Price Per sqft </label>
                                <input type="text" name="price" class="form-control" placeholder="Price Per sqft" value="{{app('request')->input('price')}}">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

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
        
    </section>
</div>
@endsection