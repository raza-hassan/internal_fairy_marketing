@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

@section('content')
@include('products.sidebar')
<div class="ps-main__wrapper">

    <header class="header--dashboard">
        <div class="header__left">
            <h3>Inventory ({{$records->total()}})</h3>
        </div>
        <div class="header__center">

        </div>

        @if(Auth::user()->can('inventory.import'))
            <div class="header__right">
                <a class="ps-btn success" href="{{ url('file-import-inventory') }}"><i class="icon icon-plus mr-2"></i>Import</a>
            </div>
        @endif

        @if(Auth::user()->can('inventory.export'))
        <div class="header__right">
            <a class="ps-btn success" href="{{ url('export-inventory-file') }}"><i class="icon icon-download mr-2"></i>Export</a>
        </div>
        @endif

    </header>

    @if (session('status'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <strong>Success! </strong> {{ session('status') }}
                </div>
            </div>
        </div>
    @endif

    @if (count($errors) > 0)
        <div class = "alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="ps-items-listing">

        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('inventory-search')}}" method="get">
                    <!--                    @csrf
                                        @method('post')-->
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Projects <sup>*</sup></label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="project_id" required="required">
                                        @foreach($projects as $project)
                                        <option <?php
                                        if (app('request')->input('project_id') == $project->id) {
                                            echo 'selected';
                                        }
                                        ?> value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Floor </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="floor">                                        <option value="0">All</option>                                        <option <?php if (app('request')->input('floor') == 'Ground Floor') {
                                            echo 'selected';
                                        } ?> value="Ground Floor"> Ground Floor </option>                                        <option <?php if (app('request')->input('floor') == '1st Floor') {
                                            echo 'selected';
                                        } ?> value="1st Floor">    1st Floor    </option>                                        <option <?php if (app('request')->input('floor') == '2nd Floor') {
                                            echo 'selected';
                                        } ?> value="2nd Floor">    2nd Floor    </option>                                        <option <?php if (app('request')->input('floor') == '3rd Floor') {
                                            echo 'selected';
                                        } ?>  value="3rd Floor">    3rd Floor    </option>                                        <option <?php if (app('request')->input('floor') == '4th Floor') {
                                            echo 'selected';
                                        } ?>  value="4th Floor">    4th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '5th Floor') {
                                            echo 'selected';
                                        } ?>  value="5th Floor">    5th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '6th Floor') {
                                            echo 'selected';
                                        } ?>  value="6th Floor">    6th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '7th Floor') {
                                            echo 'selected';
                                        } ?>  value="7th Floor">    7th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '8th Floor') {
                                            echo 'selected';
                                        } ?>  value="8th Floor">    8th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '9th Floor') {
                                            echo 'selected';
                                        } ?>  value="9th Floor">    9th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '10th Floor') {
                                            echo 'selected';
                                        } ?>  value="10th Floor">   10th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '11th Floor') {
                                            echo 'selected';
                                        } ?>  value="11th Floor">   11th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '12th Floor') {
                                            echo 'selected';
                                        } ?>  value="12th Floor">   12th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '13th Floor') {
                                                echo 'selected';
                                            } ?>  value="13th Floor">   13th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '14th Floor') {
                                            echo 'selected';
                                        } ?>  value="14th Floor">   14th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '15th Floor') {
                                            echo 'selected';
                                        } ?>  value="15th Floor">   15th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '16th Floor') {
                                            echo 'selected';
                                        } ?>  value="16th Floor">   16th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '17th Floor') {
                                            echo 'selected';
                                        } ?>  value="17th Floor">   17th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '18th Floor') {
                                            echo 'selected';
                                        } ?>  value="18th Floor">   18th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '19th Floor') {
                                            echo 'selected';
                                        } ?>  value="19th Floor">   19th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '20th Floor') {
                                            echo 'selected';
                                        } ?>  value="20th Floor">   20th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '21st Floor') {
                                                echo 'selected';
                                            } ?>  value="21st Floor">   21st Floor   </option>                                        <option <?php if (app('request')->input('floor') == '22nd Floor') {
                                            echo 'selected';
                                        } ?>  value="22nd Floor">   22nd Floor   </option>                                        <option <?php if (app('request')->input('floor') == '23rd Floor') {
                                            echo 'selected';
                                        } ?>  value="23rd Floor">   23rd Floor   </option>                                    </select>
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
                                        <option <?php
                                            if (app('request')->input('category_id') == $category->id) {
                                                echo 'selected';
                                            }
                                        ?> value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Building </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="building">
                                        <option value="0">All</option>
                                        <option <?php
                                            if (app('request')->input('building') == 'BD - A') {
                                                echo 'selected';
                                            }
                                        ?> value="BD - A">BD - A</option>
                                        <option <?php
                                            if (app('request')->input('building') == 'BD - B') {
                                                echo 'selected';
                                            }
                                            ?> value="BD - B">BD - B</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="type">
                                        <option value="0">All</option>
                                        <option <?php
                                        if (app('request')->input('type') == '1') {
                                            echo 'selected';
                                        }
                                            ?> value="1">1</option>
                                        <option <?php
                                        if (app('request')->input('type') == '2') {
                                            echo 'selected';
                                        }
                                            ?> value="2">2</option>
                                        <option <?php
                                            if (app('request')->input('type') == '3') {
                                                echo 'selected';
                                            }
                                            ?> value="3">3</option>
                                        <option <?php
                                        if (app('request')->input('type') == 'STUDIO 1') {
                                            echo 'selected';
                                        }
                                        ?> value="STUDIO 1">STUDIO 1</option>
                                        <option <?php
                                        if (app('request')->input('type') == 'DUPLEX 2') {
                                            echo 'selected';
                                        }
                                        ?> value="DUPLEX 2">DUPLEX 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Sub Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="subtype">
                                        <option value="0">All</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type A') {
                                                echo 'selected';
                                            }
                                        ?> value="Type A">Type A</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type B') {
                                                echo 'selected';
                                            }
                                        ?> value="Type B">Type B</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type C') {
                                                echo 'selected';
                                            }
                                        ?> value="Type C">Type C</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type D') {
                                                echo 'selected';
                                            }
                                        ?> value="Type D">Type D</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type E') {
                                                echo 'selected';
                                            }
                                        ?> value="Type E">Type E</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type F') {
                                                echo 'selected';
                                            }
                                        ?> value="Type F">Type F</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type G') {
                                                echo 'selected';
                                            }
                                        ?> value="Type G">Type G</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'JAQUZI') {
                                                echo 'selected';
                                            }
                                        ?> value="JAQUZI">JAQUZI</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->role !=11 && Auth::user()->role !=12)
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Status </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <select class="form-control" name="status">
                                            <option value="0">All</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Available') {
                                                    echo 'selected';
                                                }
                                            ?> value="Available">Available</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Hold') {
                                                    echo 'selected';
                                                }
                                            ?> value="Hold">Hold</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Reserved') {
                                                    echo 'selected';
                                                }
                                            ?> value="Reserved">Reserved</option>
                                            <option <?php
                                            if (app('request')->input('status') == 'Sold Aprroval') {
                                                echo 'selected';
                                            }
                                            ?> value="Sold Aprroval">Sold Aprroval</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Sold') {
                                                    echo 'selected';
                                                }
                                            ?> value="Sold">Sold</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Token') {
                                                    echo 'selected';
                                                }
                                            ?> value="Token">Token</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Terminated') {
                                                    echo 'selected';
                                                }
                                            ?> value="Terminated">Terminated</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Not for sale') {
                                                    echo 'selected';
                                                }
                                            ?> value="Not for sale">Not for sale</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Hold For Structural Change') {
                                                    echo 'selected';
                                                }
                                            ?> value="Hold For Structural Change">Hold For Structural Change</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="corner">

                                        <option value="all" @selected(true) <?php
                                            if (app('request')->input('corner') == 'all') {
                                                echo 'selected';
                                            }
                                        ?>>All</option>

                                        <option value="none_corner"<?php
                                            if (app('request')->input('corner') == 'none_corner') {
                                                echo 'selected';
                                            }
                                        ?>>Non Corner</option>
                                        <option value="corner"<?php
                                            if (app('request')->input('corner') == 'corner') {
                                                echo 'selected';
                                            }
                                        ?>>Corner</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Property Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="property_type">

                                        <option value="all" @selected(true) <?php
                                            if (app('request')->input('property_type') == 'all') { echo 'selected';}
                                        ?>>All</option>

                                        <option value="commercial"<?php
                                            if (app('request')->input('property_type') == 'commercial') {echo 'selected';}
                                        ?>>Commercial</option>
                                        <option value="residential"<?php
                                            if (app('request')->input('property_type') == 'residential') {echo 'selected';}
                                        ?>>Residential</option>
                                    </select>
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Unit ID </label>
                                <input type="text" name="unit_id" class="form-control" placeholder="Enter Unit ID" value="{{app('request')->input('unit_id')}}">
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

                            @if(Auth::user()->role==1 || Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14)
                                <th>Approved</th>
                            @endif

                            @if(Auth::user()->can('inventory.existence.change'))
                                <th>Existence</th>
                            @endif

                            @if(Auth::user()->can('inventory.history') || Auth::user()->can('inventory.edit'))
                                <th>Action</th>
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

                                @php
                                    // if ($record->category_id == 0) {
                                    //     $condition = preg_match("/\b(apartment)\b/i", $record->name);
                                    // } else {
                                    //     $condition = preg_match("/\b(apartment)\b/i", $record->name) || $record->category->slug == 'apartment';
                                    // }

                                    if ($record->category_id == 0) {
                                        $condition = preg_match("/\b(appartment|apartment)\b/i", $record->name);
                                    } else {
                                        $condition = preg_match("/\b(appartment|apartment)\b/i", $record->name) || $record->category->slug == 'apartment' || $record->category->slug == 'appartment';
                                    }
                                @endphp

                                @if ($condition)

                                    <div class="client-title tooltip">
                                        <div class="tooltiptext client-info">
                                            <div class="tooltip-head">Product Information: appart</div>
                                            <div class="inner-side"><label>Project: </label><span>{{ $record->projectname->name }}</span></div>
                                            {{-- <div class="inner-side"><label>ID: </label><span>{{ $record->id }}</span></div> --}}
                                            <div class="inner-side"><label>Name: </label><span>{{ $record->name }}</span></div>

                                            @if($record->category_id == 1)
                                                <div class="inner-side"><label>Type: </label><span> @if ($record->type != '') <strong>Executive</strong> @endif {{ $record->type }}</span></div>
                                                <div class="inner-side"><label>Sub Type: </label><span>{{ $record->subtype }}</span></div>
                                                <div class="inner-side"><label>Building: </label><span>{{ $record->building }}</span></div>
                                            @endif

                                            {{-- Corner & non-corner --}}
                                            {{-- <div class="inner-side"><label>Size (psft): </label><span>{{ $record->psft }}</span></div> --}}
                                            <div class="inner-side"><label>Size (sqrft): </label><span>{{ $record->carea }}</span></div>
                                            <div class="inner-side"><label>Rate per sqrft: </label><span>{{ number_format((int) $record->psft) }}</span></div>

                                            @if($record->corner == 1)
                                                <div class="inner-side"><label>Corner (10%): </label><span>{{ number_format($record->corner_amt) }}</span></div>
                                            @endif

                                            {{-- @if($record->corner == 0)
                                                <div class="inner-side"><label>Price: </label><span>{{ number_format((int) $record->price) }}</span></div>
                                                <div class="inner-side"><label>Rate per sqrft: </label><span>{{ number_format((int) $record->psft) }}</span></div>
                                            @else
                                                <div class="inner-side"><label>Price: </label><span>{{ number_format($record->price - $record->corner_amt) }}</span></div>
                                                <div class="inner-side"><label>Rate per sqrft: </label><span>{{ number_format($record->psft) }}</span></div>
                                                <div class="inner-side"><label>Corner (10%): </label><span>{{ number_format($record->corner_amt) }}</span></div>
                                                <div class="inner-side"><label>Total Amount: </label><span>{{ number_format($record->price) }}</span></div>
                                            @endif --}}

                                            {{-- Corner & non-corner --}}


                                            <div class="inner-side"><label>Total Price: </label>

                                                <span>
                                                    @if (is_numeric($record->price))
                                                        {{ number_format($record->price) }}
                                                    @else
                                                        {{$record->price}}
                                                    @endif
                                                </span>
                                            </div>


                                            <?php
                                                if($record->price == 0 || $record->price == '' || $record->price == null){
                                                    $product_price = 0;
                                                    $down_amount = 0;
                                                    $sixty_five_per_installment = 0;
                                                    $quater_installment = 0;
                                                    $monthly_installment = 0;
                                                }else {

                                                    $product_price = $record->price;
                                                    $down_amount = $product_price * (25/100);               // 25% downpayment
                                                    $sixty_five_per_installment = $product_price * (65/100);   // 65% on installments

                                                    // Below both options are ok to find out monthly_installment & quater_installment

                                                    // $monthly_installment = $sixty_five_per_installment / $record->nummoninstallment; // 60       // its mean => (65% on installments / 60 months (i.e in 5 years))
                                                    // $quater_installment = $sixty_five_per_installment / $record->numinstallment;     // 20       // its mean => (monthly_installment * 3) OR (65% on installments / 20 )
                                                    $monthly_installment = $sixty_five_per_installment / $record->nummoninstallment; // 60      // its mean => (65% on installments / 60 months (i.e in 5 years))
                                                    $quater_installment = $monthly_installment * 3;         // 20                               // its mean => (monthly_installment * 3) OR (65% on installments / 20 )
                                                }
                                            ?>

                                            {{-- <div class="inner-side"><label>Down Payment ({{$record->dpaymentper}}%): </label>
                                                <span>
                                                    @if (is_numeric($record->dpayment))
                                                        {{ number_format($record->dpayment) }}
                                                    @else
                                                        {{$record->dpayment}}
                                                    @endif
                                                </span>
                                            </div> --}}

                                            <div class="inner-side"><label>Down Payment (25%): </label>
                                                <span>
                                                    @if (is_numeric($down_amount))
                                                        {{ number_format($down_amount) }}
                                                    @else
                                                        {{$down_amount}}
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="inner-side"><label>Installments (65%): </label>
                                                <span>
                                                    @if (is_numeric($sixty_five_per_installment))
                                                        {{ number_format($sixty_five_per_installment) }}
                                                    @else
                                                        {{$sixty_five_per_installment}}
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="inner-side"><label>Monthly Installment: </label>
                                                <span>
                                                    @if (is_numeric($monthly_installment))
                                                        {{ number_format($monthly_installment) }}
                                                    @else
                                                        {{$monthly_installment}}
                                                    @endif
                                                </span>
                                            </div>

                                            {{-- <div class="inner-side"><label>Monthly Installment: </label>
                                                <span>
                                                    @if (is_numeric($record->moninstallment))
                                                        {{ number_format($record->moninstallment) }}
                                                    @else
                                                        {{$record->moninstallment}}
                                                    @endif
                                                </span>
                                            </div> --}}

                                            <div class="inner-side"><label>Quarterly Installment: </label>
                                                <span>
                                                    @if (is_numeric($quater_installment))
                                                        {{ number_format($quater_installment) }}
                                                    @else
                                                        {{$quater_installment}}
                                                    @endif
                                                </span>
                                            </div>

                                            {{-- <div class="inner-side"><label>Quarterly Installment: </label>
                                                <span>
                                                    @if (is_numeric($record->qtrinstallment))
                                                        {{ number_format($record->qtrinstallment) }}
                                                    @else
                                                        {{$record->qtrinstallment}}
                                                    @endif
                                                </span>
                                            </div> --}}

                                            <div class="inner-side"><label>No of Monthly Installments: </label><span>{{ $record->nummoninstallment }}</span></div>
                                            <div><span>OR</span></div>
                                            <div class="inner-side"><label>No of Quarterly Installments: </label><span>{{ $record->numinstallment }}</span></div>


                                            <div class="inner-side"><label>Possession Amount  ({{$record->posperamount}}%): </label>
                                                <span>
                                                    @if (is_numeric($record->posamount))
                                                        {{ number_format($record->posamount) }}
                                                    @else
                                                        {{$record->posamount}}
                                                    @endif
                                                </span>
                                            </div>

                                        </div>
                                        <?php
                                        $corner_name = '';
                                        if ($record->corner == 1) {
                                            $corner_name = " (Corner)";
                                        }
                                        ?>
                                        <a href="#" class="client-title">
                                            <div class="title-flag">
                                                <strong>{{ $record->name.$corner_name }}</strong>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    </div>

                                @else

                                    <div class="client-title tooltip">
                                        <div class="tooltiptext client-info">
                                            <div class="tooltip-head">Product Information:</div>
                                            <div class="inner-side"><label>Project: </label><span>{{ $record->projectname->name }}</span></div>
                                            {{-- <div class="inner-side"><label>ID: </label><span>{{ $record->id }}</span></div> --}}
                                            <div class="inner-side"><label>Name: </label><span>{{ $record->name }}</span></div>
                                            {{-- <div class="inner-side"><label>Size (psft): </label><span>{{ $record->psft }}</span></div> --}}
                                            <div class="inner-side"><label>Size (sqrft): </label><span>{{ $record->carea }}</span></div>
                                            <div class="inner-side"><label>Rate per sqrft: </label><span>{{ number_format((int) $record->psft) }}</span></div>

                                            {{-- @if($record->corner == 0)
                                                <div class="inner-side"><label>Rate per sqrft: </label><span>{{ number_format((int) $record->psft) }}</span></div>
                                            @else
                                                <div class="inner-side"><label>Price: </label><span>{{ number_format($record->price - $record->corner_amt) }}</span></div>
                                                <div class="inner-side"><label>Corner (10%): </label><span>{{ number_format($record->corner_amt) }}</span></div>
                                                <div class="inner-side"><label>Total Amount: </label><span>{{ number_format($record->price) }}</span></div>
                                            @endif --}}

                                            @if($record->corner == 1)
                                                <div class="inner-side"><label>Corner (10%): </label><span>{{ number_format($record->corner_amt) }}</span></div>
                                            @endif

                                            <div class="inner-side"><label>Total Price: </label>
                                                <span>
                                                    @if (is_numeric($record->price))
                                                        {{ number_format($record->price) }}
                                                    @else
                                                        {{$record->price}}
                                                    @endif
                                                </span>
                                            </div>

                                            <?php
                                                if($record->price == 0 || $record->price == '' || $record->price == null){
                                                    $product_price = 0;
                                                    $down_amount = 0;
                                                    $seventy_per_installment = 0;
                                                    $remain_amount = 0;
                                                    $quater_installment = 0;
                                                    $monthly_installment = 0;
                                                }else {

                                                    $product_price = $record->price;
                                                    $down_amount = $product_price * (30/100);               // 30% downpayment
                                                    $seventy_per_installment = $product_price * (70/100);   // 70% on installments
                                                    $remain_amount = $product_price - $down_amount;
                                                    $quater_installment = $remain_amount / $record->numinstallment;         // 20
                                                    $monthly_installment = $remain_amount / $record->nummoninstallment;     // 60
                                                }
                                            ?>

                                            <div class="inner-side"><label>Down Payment (30%): </label>
                                                <span>
                                                    @if (is_numeric($down_amount))
                                                        {{ number_format($down_amount) }}
                                                    @else
                                                        {{$down_amount}}
                                                    @endif
                                                </span>

                                            </div>

                                            <div class="inner-side"><label>Installments (70%): </label>
                                                <span>

                                                    @if (is_numeric($seventy_per_installment))
                                                        {{ number_format($seventy_per_installment) }}
                                                    @else
                                                        {{$seventy_per_installment}}
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="inner-side"><label>Monthly Installment: </label>
                                                <span>

                                                    @if (is_numeric($monthly_installment))
                                                        {{ number_format($monthly_installment) }}
                                                    @else
                                                        {{$monthly_installment}}
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="inner-side"><label>Quarterly Installment: </label>

                                                <span>
                                                    @if (is_numeric($quater_installment))
                                                        {{ number_format($quater_installment) }}
                                                    @else
                                                        {{$quater_installment}}
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="inner-side"><label>No of Monthly Installments: </label><span>{{ $record->nummoninstallment }}</span></div>
                                            <div><span>OR</span></div>
                                            <div class="inner-side"><label>No of Quarterly Installments: </label><span>{{ $record->numinstallment }}</span></div>

                                        </div>
                                        <?php
                                        $corner_name = '';
                                        if ($record->corner == 1) {
                                            $corner_name = " (Corner)";
                                        }
                                        ?>
                                        <a href="#" class="client-title">
                                            <div class="title-flag">
                                                <strong>{{ $record->name.$corner_name }}</strong>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    </div>

                                @endif

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

                                    @if($record->hold_status == 1 && $record->hold_by != 0 && $record->is_approved == 0 && $record->approved_by == 0)
                                        {{ 'By '.$record->holded_by->name  }}
                                    @endif

                                @else
                                    {{ $record->status }}

                                    @if($record->hold_status == 1 && $record->hold_by != 0 && $record->is_approved == 0 && $record->approved_by == 0)
                                        {{ 'By '.$record->holded_by->name  }}
                                    @endif

                                    @if($record->hold_status == 1 && $record->hold_by != 0 && $record->is_approved == 1 && $record->approved_by != 0)
                                        {{ 'By '.$record->holded_by->name  }}
                                    @endif

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


                            @if (Auth::user()->role==1 || Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14)
                                <td>
                                    @if(Auth::user()->can('inventory.approve.option'))
                                        @if ($record->status == 'Sold' && $record->hold_status == 1 && $record->hold_by != 0 && $record->is_approved == 1 && $record->approved_by > 0)
                                            {{ 'Approved By '.$record->varify->name }}
                                        @elseif($record->status == 'Sold Aprroval' && $record->hold_status == 1 && $record->hold_by != 0 && $record->is_approved == 0 && $record->approved_by == 0)
                                            <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $record->id; ?>"  data-target="#inventory_approve_model" id="inventoy_approve_id">
                                                {{ 'Approvel Need'  }}
                                            </a>
                                        @else
                                            {{$record->status }}
                                        @endif
                                    @else
                                        {{$record->status }}
                                    @endif
                                </td>
                            @endif


                            @if(Auth::user()->can('inventory.existence.change'))
                                <td>
                                    @if($record->status != 'Sold')
                                        <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $record->id; ?>"  data-target="#existence_model" id="existence_id">
                                            <i class="fa fa-pause" aria-hidden="true"></i>
                                        </a>
                                    @else
                                        <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $record->id; ?>"  data-target="#existence_model" id="existence_id">
                                            {{$record->status}}
                                        </a>
                                    @endif
                                </td>
                            @endif



                            @if(Auth::user()->can('inventory.history') || Auth::user()->can('inventory.edit'))
                                <td style="width: 5px; text-align: center;">

                                    @if(Auth::user()->can('inventory.history'))
                                        <a class="mr-5" rel="tooltip" href="#" data-original-title="" title="View-History" data-toggle="modal" unitid="{{ $record->unitid }}" data-target="#indentory_model" id="indentory_model_id">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </a>
                                    @endif

                                    @if(Auth::user()->can('inventory.edit'))
                                        <a rel="tooltip" href="{{ url('inventory/edit', $record) }}" data-original-title="" title="Edit">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    @endif

                                </td>
                            @endif

                        </tr>
                        @endforeach
                    </tbody>
                </table>


                <div class="modal fade" id="indentory_model" role="dialog">
                    <div class="modal-dialog modal-large">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">X</button>
                            </div>
                            <div class="modal-body">
                                <table class="table ps-table">
                                    <thead>
                                        <tr>
                                            <th>Unit ID</th>
                                            <th>Name</th>
                                            <th>Lead ID</th>
                                            <th>Status</th>
                                            <th>Discount</th>
                                            <th>Price</th>
                                            <th>Token</th>
                                            <th>Token Received</th>
                                            <th>Client</th>
                                            <th>Approved By</th>

                                        </tr>
                                    </thead>
                                    <tbody id="order_row">

                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="inventory_approve_model" role="dialog">
                    <div class="modal-dialog modal-default">
                        <form method="post" action="{{ url('inventory/approve/store') }}" class="form-horizontal" id="existence_from" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <figure class="ps-block--form-box">
                                        <figcaption>Inventory Approval</figcaption>
                                        <div class="ps-block__content">

                                            <div class="form-group">
                                                <label>Select Approval Status </label>
                                                <select class="form-control" name="is_approved" required>
                                                    <option value="" selected disabled >Select Status</option>
                                                    <option <?php if (app('request')->input('is_approved') == '1') { echo 'selected'; } ?> value="1">Approved</option>
                                                    <option <?php if (app('request')->input('is_approved') == '0') { echo 'selected'; } ?> value="0">Rejected</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Note</label>
                                                <textarea class="form-control" rows="3" name="note">{{old('note')}}</textarea>
                                            </div>

                                            <input type="hidden" name="approved_by"  value="{{Auth::user()->id}}">
                                            <input type="hidden" id="prdct_id" name="product_id" >
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
                                                    <option <?php if (app('request')->input('company') == '{{$record->name}}') {
    echo 'selected';
} ?> value="{{$record->name}}">{{$record->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            <div class="form-group">
                                                <select class="form-control" name="status">
                                                    <option value="" selected disabled >Select Status</option>
                                                    <option <?php if (app('request')->input('status') == 'Available') {
    echo 'selected';
} ?> value="Available">Available</option>
                                                    <option <?php if (app('request')->input('status') == 'Hold') {
    echo 'selected';
} ?> value="Hold">Hold</option>
                                                    <option <?php if (app('request')->input('status') == 'Reserved') {
    echo 'selected';
} ?> value="Reserved">Reserved</option>
                                                    <option <?php if (app('request')->input('status') == 'Sold') {
    echo 'selected';
} ?> value="Sold">Sold</option>
                                                    <option <?php if (app('request')->input('status') == 'Token') {
    echo 'selected';
} ?> value="Token">Token</option>
                                                    <option <?php if (app('request')->input('status') == 'Terminated') {
    echo 'selected';
} ?> value="Terminated">Terminated</option>
                                                    <option <?php if (app('request')->input('status') == 'Not for sale') {
    echo 'selected';
} ?> value="Not for sale">Not for sale</option>
                                                    <option <?php if (app('request')->input('status') == 'Hold For Structural Change') {
    echo 'selected';
} ?> value="Hold For Structural Change">Hold For Structural Change</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Note</label>
                                                <textarea required class="form-control" rows="3" name="note">{{old('note')}}</textarea>
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
        <!--        <div class="ps-section__footer">
                    {{ $records->render() }}
                    <p>Show {{ $records->count() }} in {{$records->total()}} items.</p>

                </div>-->
    </section>
</div>
@endsection
