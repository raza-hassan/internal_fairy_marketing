@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])
@section('content')
@include('products.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Inventory Prices Update</h3>
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

        @if (session('error'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                        <strong>Error! </strong> {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($message))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                        <strong>Success! </strong> {{ $message }}
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($error_message))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                        <strong>Error! </strong> {{ $error_message }}
                    </div>
                </div>
            </div>
        @endif

    </header>
    <section class="ps-items-listing">
        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('inventory-update')}}" method="get">
                    <!-- @csrf
                    @method('post')-->
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Unit ID </label>
                                <input type="text" name="unit_id" class="form-control" placeholder="Enter Unit ID" value="{{app('request')->input('unit_id')}}">
                            </div>
                        </div>


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
                                <label>Category </label>
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


                        {{-- Building --}}
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group" id="building_select">
                                <label>Building</label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="building" required="required">
                                        <option value="0">All</option>
                                        @foreach($buildings as $product)
                                            <option <?php if(app('request')->input('building') == $product->building){ echo 'selected'; } ?> value="{{$product->building}}">{{$product->building}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Types --}}
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group" id="type_select">
                                <label>Type </label>
                                <div class="bootstrap-select fm-cmp-mg" id="types_load">
                                    <select class="form-control" name="type" required="required">
                                        <option value="0">All</option>
                                        @foreach($types as $product)
                                            <option <?php if(app('request')->input('type') == $product->type){ echo 'selected'; } ?> value="{{$product->type}}">{{$product->type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Sub-Types --}}
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>SubType</label>
                                <div class="bootstrap-select fm-cmp-mg" id="subtypes_load">
                                    <select class="form-control" name="subtype" required="required">
                                        <option value="0">All</option>
                                        @foreach($subtypes as $product)
                                            <option <?php if(app('request')->input('subtype') == $product->subtype){ echo 'selected'; } ?> value="{{$product->subtype}}">{{$product->subtype}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Corner OR None </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control select_corner" name="corner" >
                                        <option value=""> None  </option>
                                        <option value="corner"> Corner      </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 corner_input" style="display:none;">
                            <div class="form-group">
                                <label>Add In Percent ( % )</label>
                                <input type="text" name="corner_percent" class="form-control" placeholder="Enter Percentage" value="{{app('request')->input('corner_percent')}}">
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

        {{-- ==================================================================================== --}}


            <div class="ps-section__content">
                <div class="table-responsive">
                    <table class="table ps-table">
                        <thead>
                            <tr>
                                <th>Unit ID</th>
                                <th>Unit Name</th>
                                <th>Project</th>
                                <th>Type</th>
                                <th>Floor</th>
                                <th>Gross Area</th>
                                <th>Net Area</th>
                                <th>Price</th>
                                <th>Status</th>
                                <!--<th>Sold By</th>-->

                                @if (Auth::user()->role==1  || Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14)
                                    <th>Existence</th>
                                @endif


                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)

                            <tr class="gradeX">

                                <td>
                                    {{ $record->unitid }}
                                </td>
                                <td>
                                    <div class="client-title tooltip">
                                        <div class="tooltiptext client-info">
                                            <div class="tooltip-head">Product Information:</div>
                                            <div class="inner-side"><label>ID: </label><span>{{ $record->id }}</span></div>
                                            <div class="inner-side"><label>Name: </label><span>{{ $record->name }}</span></div>
                                            @if($record->category_id == 1)
                                            <div class="inner-side"><label>Type: </label><span>{{ $record->type }}</span></div>
                                            <div class="inner-side"><label>Sub Type: </label><span>{{ $record->subtype }}</span></div>
                                            <div class="inner-side"><label>Building: </label><span>{{ $record->building }}</span></div>
                                            @endif
                                            <div class="inner-side"><label>Project: </label><span>{{ $record->projectname->name }}</span></div>
                                            <div class="inner-side"><label>Down Payment ({{$record->dpaymentper}}): </label>
                                                <span>
                                                    @if (is_numeric($record->dpayment))
                                                    {{ number_format($record->dpayment) }}
                                                    @else
                                                    {{$record->dpayment}}
                                                    @endif
                                                </span>

                                            </div>
                                            <div class="inner-side"><label>Quarterly Installment: </label>

                                                <span>
                                                    @if (is_numeric($record->qtrinstallment))
                                                    {{ number_format($record->qtrinstallment) }}
                                                    @else
                                                    {{$record->qtrinstallment}}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="inner-side"><label>No Installment: </label><span>{{ $record->numinstallment }}</span></div>
                                            <div class="inner-side"><label>Monthly Installment: </label>
                                                <span>

                                                    @if (is_numeric($record->moninstallment))
                                                    {{ number_format($record->moninstallment) }}
                                                    @else
                                                    {{$record->moninstallment}}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="inner-side"><label>No of Monthly Installment: </label><span>{{ $record->nummoninstallment }}</span></div>

                                            <div class="inner-side"><label>Possession Amount  ({{$record->posperamount}}): </label>
                                                <span>
                                                    @if (is_numeric($record->posamount))
                                                    {{ number_format($record->posamount) }}
                                                    @else
                                                    {{$record->posamount}}
                                                    @endif
                                                </span>
                                            </div>


                                            <div class="inner-side"><label>Total Price: </label>

                                                <span>
                                                    @if (is_numeric($record->price))
                                                        {{ number_format($record->price) }}
                                                    @else
                                                        {{$record->price}}
                                                    @endif
                                                </span>
                                            </div>

                                        </div>
                                        <a href="#" class="client-title">
                                            <div class="title-flag">
                                                <strong>{{ $record->name }}</strong>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    </div>
                                </td>

                                <td><?php echo $record->projectname->name; ?></td>
                                <td>
                                    <?php
                                    if ($record->type != '') {
                                        echo $record->type;
                                    } else {
                                        if ($record->category) {
                                            echo $record->category->name;
                                        } else {
                                            echo 'No Category';
                                        }
                                    }
                                    ?>
                                </td>

                                <td>
                                    {{ $record->floor }}
                                </td>

                                <td>
                                    @if($record->category_id ==1)
                                    {{$record->carea.'sqr/ft'}}
                                    @endif
                                </td>
                                <td>
                                    @if($record->category_id !=1)
                                    {{$record->carea.'sqr/ft'}}
                                    @endif
                                </td>

                                <td>
                                    @if (is_numeric($record->price))
                                    {{ number_format($record->price) }}
                                    @else
                                    {{$record->price}}
                                    @endif
                                </td>

                                <td>
                                    @if($record->status == 'Hold')
                                    {{ $record->status }}
                                    {{ $record->hold_expiary }}
                                    @else
                                    {{ $record->status }}
                                    @endif
                                </td>

                               <!-- <td>
                                    @if($record->status == 'Sold')
                                    {{ $record->sold_by }}
                                    @elseif($record->status != '' && $record->status != 'Sold')
                                    {{ $record->sold_by }}
                                    @else
                                    Do Nothing
                                    @endif
                                </td>-->

                                @if (Auth::user()->role==1  || Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14)
                                    <td>
                                        @if($record->status != 'Sold')
                                            <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $record->id; ?>"  data-target="#existence_model" id="existence_id">
                                                <i class="fa fa-pause" aria-hidden="true"></i>
                                            </a>
                                        @else
                                        {{$record->status}}
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <div class="modal fade" id="existence_model" role="dialog">
                        <div class="modal-dialog modal-default">
                            <form method="post" action="{{ url('existence/store') }}" class="form-horizontal" id="existence_from" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <figure class="ps-block--form-box">
                                            <figcaption>Existence Selection</figcaption>
                                            <div class="ps-block__content">
                                                <div class="form-group">
                                                    <label>Select Existence Date</label>
                                                    <input class="form-control existence_date_select" onlyselect type="text" name="date" placeholder="Please Select Existence Date" value="{{old('date', date('m/d/Y')) }}"/>
                                                </div>
                                                @if(!empty($company))
                                                <div class="form-group">

                                                    <select class="form-control" name="company">
                                                        <option value="" selected disabled >Select Company</option>
                                                        @foreach ($company as  $record)
                                                            <option <?php if (app('request')->input('company') == '{{$record->name}}') {echo 'selected';} ?> value="{{$record->name}}">{{$record->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                                <div class="form-group">
                                                    <select class="form-control" name="status">
                                                        <option value="" selected disabled >Select Status</option>
                                                        <option <?php if (app('request')->input('status') == 'Available') {echo 'selected';} ?> value="Available">Available</option>
                                                        <option <?php if (app('request')->input('status') == 'Hold') {echo 'selected';} ?> value="Hold">Hold</option>
                                                        <option <?php if (app('request')->input('status') == 'Reserved') {echo 'selected';} ?> value="Reserved">Reserved</option>
                                                        <option <?php if (app('request')->input('status') == 'Sold') {echo 'selected';} ?> value="Sold">Sold</option>
                                                        <option <?php if (app('request')->input('status') == 'Token') {echo 'selected';} ?> value="Token">Token</option>
                                                        <option <?php if (app('request')->input('status') == 'Terminated') {echo 'selected';} ?> value="Terminated">Terminated</option>
                                                        <option <?php if (app('request')->input('status') == 'Not for sale') {echo 'selected';} ?> value="Not for sale">Not for sale</option>
                                                        <option <?php if (app('request')->input('status') == 'Hold For Structural Change') {echo 'selected';} ?> value="Hold For Structural Change">Hold For Structural Change</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Note</label>
                                                    <textarea class="form-control" rows="3" name="note">{{old('note')}}</textarea>
                                                </div>
                                                <input type="hidden" name="hold_by"  value="{{Auth::user()->id}}">
                                                <input type="hidden" id="product_id" name="product_id" >
                                            </div>
                                        </figure>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success button" id="share_task" >Save  </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>


            <div class="ps-section__footer">
                <p>Showing {{($records->currentpage()-1)*$records->perpage()+1}} to {{$records->currentpage()*$records->perpage()}}
                    of  {{$records->total()}} entries
                </p>
                {{ $records->appends(request()->except('page'))->links() }}
            </div>

        {{-- ==================================================================================== --}}

    </section>








</div>




<script>
    $(document).on('change', '.select_corner', function (e)
    {
        var value=$( ".select_corner option:selected" ).val();

        if(value == 'corner')
        {
            $('.corner_input').css('display', 'block');
        }
        else
        {
            $('.corner_input').css('display', 'none');
        }
    });
</script>



    {{-- Load Type and sub-type with jquery --}}
    <script type="text/javascript">

        $(document).on('change', '#building_select select', function (e){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();

            var _token = $('meta[name="csrf-token"]').attr('content');
            var building_name = $(this).val();
            // var base_url = $('#base_url').val();
            // alert(building_name);exit;
            $.ajax({
                type: 'POST',
                // url: base_url + '/admin/load/subcategory',
                url: "{{ url('load/types/') }}",

                data: {building_name: building_name, _token: _token},
                success: function (data)
                {
                    $('#types_load').html(data.html);
                }
            });
        });


        $(document).on('change', '#type_select select', function (e){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();

            var _token = $('meta[name="csrf-token"]').attr('content');
            var type_name = $(this).val();
            // var base_url = $('#base_url').val();
            // alert(type_name);exit;
            $.ajax({
                type: 'POST',
                // url: base_url + '/admin/load/subcategory',
                url: "{{ url('load/subtypes/') }}",

                data: {type_name: type_name, _token: _token},
                success: function (data)
                {
                    $('#subtypes_load').html(data.html);
                }
            });
        });

    </script>






@endsection
