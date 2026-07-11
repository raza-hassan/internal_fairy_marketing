@extends('freelancer.layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

@section('content')
@include('freelancer.leads.sidebar')

<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Inventory ({{$records->total()}})</h3>
        </div>
        <div class="header__center">

        </div>

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
                <form class="ps-form--filter" action="{{url('affiliator/inventory-search')}}" method="get">
                    <!--                    @csrf
                                        @method('post')-->
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Projects <sup>*</sup></label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="project_id" required="required">
                                        @foreach($projects as $project)
                                        <option <?php if (app('request')->input('project_id') == $project->id) {
    echo 'selected';
} ?> value="{{$project->id}}">{{$project->name}}</option>
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
                                        <option <?php if (app('request')->input('floor') == 'Ground Floor') {
    echo 'selected';
} ?> value="Ground Floor">Ground Floor</option>
                                        <option <?php if (app('request')->input('floor') == '1st Floor') {
    echo 'selected';
} ?> value="1st Floor">1st Floor</option>
                                        <option <?php if (app('request')->input('floor') == '2nd Floor') {
    echo 'selected';
} ?> value="2nd Floor">2nd Floor</option>
                                        <option <?php if (app('request')->input('floor') == '3rd Floor') {
    echo 'selected';
} ?> value="3rd Floor">3rd Floor</option>
                                        <option <?php if (app('request')->input('floor') == '4th Floor') {
    echo 'selected';
} ?> value="4th Floor">4th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '5th Floor') {
    echo 'selected';
} ?> value="5th Floor">5th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '6th Floor') {
    echo 'selected';
} ?> value="6th Floor">6th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '7th Floor') {
    echo 'selected';
} ?> value="7th Floor">7th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '8th Floor') {
    echo 'selected';
} ?> value="8th Floor">8th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '9th Floor') {
    echo 'selected';
} ?> value="9th Floor">9th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '10th Floor') {
    echo 'selected';
} ?> value="10th Floor">10th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '11th Floor') {
    echo 'selected';
} ?> value="11th Floor">11th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '12th Floor') {
    echo 'selected';
} ?> value="12th Floor">12th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '13th Floor') {
    echo 'selected';
} ?> value="13th Floor">13th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '14th Floor') {
    echo 'selected';
} ?> value="14th Floor">14th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '15th Floor') {
    echo 'selected';
} ?> value="15th Floor">15th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '16th Floor') {
    echo 'selected';
} ?> value="16th Floor">16th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '17th Floor') {
    echo 'selected';
} ?> value="17th Floor">17th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '18th Floor') {
    echo 'selected';
} ?> value="18th Floor">18th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '19th Floor') {
    echo 'selected';
} ?> value="19th Floor">19th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '20th Floor') {
    echo 'selected';
} ?> value="20th Floor">20th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '21th Floor') {
    echo 'selected';
} ?> value="21th Floor">21th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '22th Floor') {
    echo 'selected';
} ?> value="22th Floor">22th Floor</option>
                                        <option <?php if (app('request')->input('floor') == '23th Floor') {
    echo 'selected';
} ?> value="23th Floor">23th Floor</option>
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
                                        <option <?php if (app('request')->input('category_id') == $category->id) {
    echo 'selected';
} ?> value="{{$category->id}}">{{$category->name}}</option>
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
                                        <option <?php if (app('request')->input('building') == 'BD - A') {
    echo 'selected';
} ?> value="BD - A">BD - A</option>
                                        <option <?php if (app('request')->input('building') == 'BD - B') {
    echo 'selected';
} ?> value="BD - B">BD - B</option>
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
                                        <option <?php if (app('request')->input('type') == '1') {
    echo 'selected';
} ?> value="1">1</option>
                                        <option <?php if (app('request')->input('type') == '2') {
    echo 'selected';
} ?> value="2">2</option>
                                        <option <?php if (app('request')->input('type') == '3') {
    echo 'selected';
} ?> value="3">3</option>
                                        <option <?php if (app('request')->input('type') == 'STUDIO 1') {
    echo 'selected';
} ?> value="STUDIO 1">STUDIO 1</option>
                                        <option <?php if (app('request')->input('type') == 'DUPLEX 2') {
    echo 'selected';
} ?> value="DUPLEX 2">DUPLEX 2</option>
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
                                        <option <?php if (app('request')->input('subtype') == 'Type A') {
    echo 'selected';
} ?> value="Type A">Type A</option>
                                        <option <?php if (app('request')->input('subtype') == 'Type B') {
    echo 'selected';
} ?> value="Type B">Type B</option>
                                        <option <?php if (app('request')->input('subtype') == 'Type C') {
    echo 'selected';
} ?> value="Type C">Type C</option>
                                        <option <?php if (app('request')->input('subtype') == 'Type D') {
    echo 'selected';
} ?> value="Type D">Type D</option>
                                        <option <?php if (app('request')->input('subtype') == 'Type E') {
    echo 'selected';
} ?> value="Type E">Type E</option>
                                        <option <?php if (app('request')->input('subtype') == 'Type F') {
    echo 'selected';
} ?> value="Type F">Type F</option>
                                        <option <?php if (app('request')->input('subtype') == 'Type G') {
    echo 'selected';
} ?> value="Type G">Type G</option>
                                        <option <?php if (app('request')->input('subtype') == 'JAQUZI') {
    echo 'selected';
} ?> value="JAQUZI">JAQUZI</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="status">
                                        <option value="0">All</option>
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
                            </div>
                        </div>

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

                        </tr>
                        @endforeach
                    </tbody>
                </table>


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
