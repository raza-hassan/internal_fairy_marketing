@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

@section('content')
@include('products.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Inventory</h3>
        </div>
        <div class="header__center">

        </div>
        <div class="header__right"></div>
        @if (session('status'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Success! </strong> {{ session('status') }}
                </div> 
            </div>
        </div>
        @endif
    </header>
    <section class="ps-items-listing">

        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('inventory/search')}}" method="post">
                    @csrf
                    @method('post')
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Projects <sup>*</sup></label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="project_id" required="required">
                                        @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->name}}</option>
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
                                        <option value="">All</option>
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
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="category_id">
                                        <option value="">All</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="status">
                                        <option value="0">Do Nothing</option>
                                        <option value="Available">Available</option>
                                        <option value="Hold">Hold</option>
                                        <option value="Reserved">Reserved</option>
                                        <option value="Sold">Sold</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Unit ID </label>
                                <input type="text" name="unit_id" class="form-control" placeholder="Enter Unit ID">
                            </div>
                        </div>
<!--                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Unit Name </label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Unit Name">
                            </div>
                        </div>-->

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
                            <th>Covered Area</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Sold By</th>
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
                                                <div class="inner-side"><label>Name: </label><span>{{ $record->name }}</span></div>
                                                <div class="inner-side"><label>Project: </label><span>{{ $record->projectname->name }}</span></div>
                                                <div class="inner-side"><label>Down Payment ({{$record->dpaymentper}}): </label><span>{{ $record->qtrinstallment }}</span></div>
                                                <div class="inner-side"><label>Quarterly Installment: </label><span>{{ $record->qtrinstallment }}</span></div>
                                                <div class="inner-side"><label>No Installment: </label><span>{{ $record->numinstallment }}</span></div>
                                                <div class="inner-side"><label>Monthly Installment: </label><span>{{ $record->moninstallment }}</span></div>
                                                <div class="inner-side"><label>No of Monthly Installment: </label><span>{{ $record->nummoninstallment }}</span></div>
                                                <div class="inner-side"><label>Possession Amount  ({{$record->posperamount}}): </label><span>{{ $record->posamount }}</span></div>
                                                
                                            </div>
                                    <a href="#" class="client-title">
                                        <div class="title-flag">
                                            <strong>{{ $record->name }}</strong>     
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        </div>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <?php echo $record->projectname->name; ?>
                            </td>
                            <td>
                                <?php
                                if ($record->type != '') {
                                    echo $record->type;
                                } else {
                                    if ($record->category) {
                                        echo $record->category->name;
                                    } else {
                                        echo 'No Type';
                                    }
                                }
                                ?>
                            </td>

                            <td>
                                {{ $record->floor }}
                            </td>

                            <td>
                                <?php
                                if ($record->carea != '') {
                                    echo $record->carea;
                                } else {
                                    echo $record->garea;
                                }
                                ?>
                            </td>

                            <td>
                                {{ number_format($record->price) }}
                            </td>

                            <td>
                                @if($record->status == 'Hold')
                                {{ $record->status }}<br>
                                {{ $record->hold_expiary }}
                                @else 
                                {{ $record->status }}
                                @endif
                            </td>

                            <td>
                                @if($record->status == 'Sold')
                                {{ $record->sold_by }}
                                @else 
                                Nothing
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>
@endsection