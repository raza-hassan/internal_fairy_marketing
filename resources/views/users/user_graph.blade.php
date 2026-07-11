@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Performance')])
@section('content')

    @include('leads.sidebar')

    <div class="container">

        <div class="page-content">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec" >
                <form class="ps-form--filter" action="{{url('check/user/performance')}}" method="post" >
                    @csrf
                    @method('post')
                    <div class="col-xs-12 custom_date_option_div">
                        <div class="form-group">
                            <label>Select Time Period </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <select class="form-control custom_date_option" id="change_option" name="time_period"  required >
                                    {{-- <option value="" selected disabled> Select </option> --}}
                                    <option <?php
                                    if (app('request')->input('time_period') == 'today') {
                                        echo 'selected';
                                    }
                                    ?> value="today">Today</option>
                                    <option <?php
                                    if (app('request')->input('time_period') == 'this_month') {
                                        echo 'selected';
                                    }
                                    ?> value="this_month">This Month</option>
                                    <option <?php
                                    if (app('request')->input('time_period') == 'last_month') {
                                        echo 'selected';
                                    }
                                    ?> value="last_month">Last Month</option>
                                    <option <?php
                                    if (app('request')->input('time_period') == 'three_month') {
                                        echo 'selected';
                                    }
                                    ?> value="three_month">3 Months</option>
                                    <option <?php
                                    if (app('request')->input('time_period') == 'six_month') {
                                        echo 'selected';
                                    }
                                    ?> value="six_month"> 6 Months</option>
                                    <option <?php
                                    if (app('request')->input('time_period') == 'this_year') {
                                        echo 'selected';
                                    }
                                    ?> value="this_year">This Year</option>
                                    <option
                                        <?php
                                            if (app('request')->input('time_period') == 'custom_date') {echo 'selected';}
                                        ?>
                                        value="custom_date" >Custome Date</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="custom_date_div pl-15" style="display: none;">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label> Start Date </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <input type="text" readonly name="startDate" value="{{app('request')->input('startDate')}}" class="{{-- datepicker --}} form-control start_date_input" placeholder="Enter Start Date" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label> End Date </label>
                                    <div class="bootstrap-select fm-cmp-mg" style="margin-left: 5px;">
                                        <input type="text" readonly name="endDate"
                                        value="{{app('request')->input('endDate')}}"  class="{{-- datepicker --}} form-control end_date_input" placeholder="Enter End Date" >
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="graph_undo" style="font-size:16px"> Go Back <i class="fa fa-undo"></i></span></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Select User </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <?php
                                    if(app('request')->input('users') !="" ){
                                        $user_id = app('request')->input('users');
                                    }else {
                                        $user_id =Auth::user()->id;
                                    }
                                ?>
                                <select name="users" class="form-control multi_user" {{-- multiple --}}>
                                    @foreach ($graph_users as $user)
                                        <option <?php if($user_id ==$user->id ) {echo 'selected';}  ?>  value="{{ $user->id }}"  {{-- @if(Auth::user()->id==$user->id)selected@endif --}} > {{ $user->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><label>&nbsp; </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <input class="ps-btn success form-control" type="submit" name="submit" value="Filter">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <style>
                .graph_padding{
                    padding: 5px;
                }
            </style>

            <div class="row target_charts">
                <div class="col-sm-4 col-xs-4" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                        <h4 class="pt-10 mb-0 text-center text-uppercase">Verified Calls</h4>
                        <hr/>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="bar-chart1" class="graph_padding" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-4" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                        <h4 class="pt-10 mb-0 text-center text-uppercase">Client Meetings</h4>
                        <hr/>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="bar-chart2" class="graph_padding" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-4" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                        <h4 class="pt-10 mb-0 text-center text-uppercase">Dealer Meetings</h4>
                        <hr/>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="bar-chart3" class="graph_padding"  height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row target_charts">
                <div class="col-sm-4 col-xs-4" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                        <h4 class="pt-10 mb-0 text-center text-uppercase">Freelancer Meetings</h4>
                        <hr/>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="bar-chart4" class="graph_padding"  height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-4" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                        <h4 class="pt-10 mb-0 text-center text-uppercase"> Site Visit </h4>
                        <hr/>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="bar-chart5" class="graph_padding"  height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-4" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                        <h4 class="pt-10 mb-0 text-center text-uppercase">  Unit </h4>
                        <hr/>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="bar-chart6" class="graph_padding"  height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row target_charts">
                <div class="col-sm-3 col-xs-3" > </div>
                <div class="col-sm-6 col-xs-6" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">
                        <h4 class="pt-10 mb-0 text-center text-uppercase">  Sales </h4>
                        <hr/>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="bar-chart7" class="graph_padding"  height="150"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Vendor JS -->
    <script src="{{ asset('public/progress_chart/js/vendors.min.js') }}"></script>
    <script src="{{ asset('public/progress_chart/assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('public/progress_chart/assets/vendor_components/chart.js-master/Chart.min.js') }}"></script>
    <script>
        var all_targets_data = {!! json_encode($user_target) !!};
        // console.log(all_targets_data);
        // Verified Calls Persentage
        if (all_targets_data['set_verified_calls_target' ] > 0){
            achive_verified_calls_target = ((all_targets_data['achive_verified_calls_target' ] * 100)/all_targets_data['set_verified_calls_target' ]).toFixed(0) + "%";
        }else {
            achive_verified_calls_target = all_targets_data['set_verified_calls_target'];
        }
        // Client Meetings Persentage
        if (all_targets_data['set_client_meetings_target' ] > 0){
            achive_client_meetings_target = ((all_targets_data['achive_client_meetings_target' ] * 100)/all_targets_data['set_client_meetings_target' ]).toFixed(0) + "%";
        }else {
            achive_client_meetings_target = all_targets_data['set_client_meetings_target'];
        }
        // Dealer Meetings Persentage
        if (all_targets_data['set_dealer_meetings_target' ] > 0){
            achive_dealer_meetings_target = ((all_targets_data['achive_dealer_meetings_target' ] * 100)/all_targets_data['set_dealer_meetings_target' ]).toFixed(0) + "%";
        }else {
            achive_dealer_meetings_target = all_targets_data['set_dealer_meetings_target'];
        }
        // Freelancer Meetings Persentage
        if (all_targets_data['set_freelancer_meetings_target' ] > 0){
            achive_freelancer_meetings_target = ((all_targets_data['achive_freelancer_meetings_target' ] * 100)/all_targets_data['set_freelancer_meetings_target' ]).toFixed(0) + "%";
        }else {
            achive_freelancer_meetings_target = all_targets_data['set_freelancer_meetings_target'];
        }
        // Site Visit Persentage
        if (all_targets_data['set_site_visit_target' ] > 0){
            achive_site_visit_target = ((all_targets_data['achive_site_visit_target' ] * 100)/all_targets_data['set_site_visit_target' ]).toFixed(0) + "%";
        }else {
            achive_site_visit_target = all_targets_data['set_site_visit_target'];
        }
        // Unit Persentage
        if (all_targets_data['set_unit_target' ] > 0){
            achive_unit_target = ((all_targets_data['achive_unit_target' ] * 100)/all_targets_data['set_unit_target' ]).toFixed(0) + "%";
        }else {
            achive_unit_target = all_targets_data['set_unit_target'];
        }
        // Sales Persentage
        if (all_targets_data['set_sales_target' ] > 0){
            achive_sales_target = ((all_targets_data['achive_sales_target' ] * 100)/all_targets_data['set_sales_target' ]).toFixed(0) + "%";
        }else {
            achive_sales_target = all_targets_data['set_sales_target'];
        }
        $( document ).ready(function() {
            "use strict";
            // Bar chart 1
            new Chart(document.getElementById("bar-chart1"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_verified_calls_target' ] + ")" , "Achive ("+all_targets_data['achive_verified_calls_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_verified_calls_target' ] + ")" , "Achive ("+((all_targets_data['achive_verified_calls_target' ] * 100)/all_targets_data['set_verified_calls_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_verified_calls_target' ]).toFixed(0) + ")" , "Achive ("+achive_verified_calls_target +")"],
                datasets: [
                        {
                            label: "Calls",
                            backgroundColor: ["#689f38", "#38649f"],
                            data: [(all_targets_data['set_verified_calls_target' ]).toFixed(2) , all_targets_data['achive_verified_calls_target' ] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Verified Calls'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 2
            new Chart(document.getElementById("bar-chart2"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_client_meetings_target' ] + ")" , "Achive ("+all_targets_data['achive_client_meetings_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_client_meetings_target' ] + ")" , "Achive ("+((all_targets_data['achive_client_meetings_target' ] * 100)/all_targets_data['set_client_meetings_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_client_meetings_target' ]).toFixed(0) + ")" , "Achive ("+achive_client_meetings_target +")"],
                datasets: [
                        {
                            label: "Client Meetings",
                            backgroundColor: ["#689f38", "#389f99"],
                            data: [(all_targets_data['set_client_meetings_target' ]).toFixed(2), all_targets_data['achive_client_meetings_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Client Meetings'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 3
            new Chart(document.getElementById("bar-chart3"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_dealer_meetings_target' ] + ")" , "Achive ("+all_targets_data['achive_dealer_meetings_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_dealer_meetings_target' ] + ")" , "Achive ("+((all_targets_data['achive_dealer_meetings_target' ] * 100)/all_targets_data['set_dealer_meetings_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_dealer_meetings_target' ]).toFixed(0) + ")" , "Achive ("+achive_dealer_meetings_target +")"],
                datasets: [
                        {
                            label: "Dealer Meetings",
                            backgroundColor: ["#689f38", "#ee1044"],
                            data: [(all_targets_data['set_dealer_meetings_target' ]).toFixed(2) , all_targets_data['achive_dealer_meetings_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Dealer Meetings'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 4
            new Chart(document.getElementById("bar-chart4"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_freelancer_meetings_target' ] + ")" , "Achive ("+all_targets_data['achive_freelancer_meetings_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_freelancer_meetings_target' ] + ")" , "Achive ("+((all_targets_data['achive_freelancer_meetings_target' ] * 100)/all_targets_data['set_freelancer_meetings_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_freelancer_meetings_target' ]).toFixed(0) + ")" , "Achive ("+achive_freelancer_meetings_target +")"],
                datasets: [
                        {
                            label: "Freelancer Meetings",
                            backgroundColor: ["#689f38", "#ff8f00"],
                            data: [(all_targets_data['set_freelancer_meetings_target' ]).toFixed(2) , all_targets_data['achive_freelancer_meetings_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Freelancer Meetings'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 5
            new Chart(document.getElementById("bar-chart5"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_site_visit_target' ] + ")" , "Achive ("+all_targets_data['achive_site_visit_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_site_visit_target' ] + ")" , "Achive ("+((all_targets_data['achive_site_visit_target' ] * 100)/all_targets_data['set_site_visit_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_site_visit_target' ].toFixed(0)) + ")" , "Achive ("+achive_site_visit_target +")"],
                datasets: [
                        {
                            label: "Site Vist",
                            backgroundColor: ["#689f38", "#FF007F"],
                            data: [(all_targets_data['set_site_visit_target' ]).toFixed(2) , all_targets_data['achive_site_visit_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Site Vist'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            // Bar chart 6
            new Chart(document.getElementById("bar-chart6"), {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_unit_target' ] + ")" , "Achive ("+all_targets_data['achive_unit_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_unit_target' ] + ")" , "Achive ("+((all_targets_data['achive_unit_target' ] * 100)/all_targets_data['set_unit_target' ]).toFixed(0)  + "%)"],
                labels: ["Target ("+(all_targets_data['set_unit_target' ]).toFixed(0) + ")" , "Achive ("+achive_unit_target +")"],
                datasets: [
                        {
                            label:'Unit',
                            backgroundColor: ["#689f38", "#800000"],
                            data: [(all_targets_data['set_unit_target' ]).toFixed(2) , all_targets_data['achive_unit_target'] ]
                        }
                    ]
                },
                options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Unit'
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            var set_number = all_targets_data['set_sales_target' ];
            // var set_number = 5570989.9;
            // Format the number with thousands separators and decimal places
            var set_formattedPrice = set_number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            // withoutr Decimal
            // var set_formattedPrice = Math.floor(set_number).toLocaleString('en-PK');
            // Add the PKR currency symbol
            set_formattedPrice += ' Rs';
            // Bar chart 7
            new Chart(document.getElementById("bar-chart7"),
            {
                type: 'bar',
                data: {
                // // count
                // labels: ["Target ("+all_targets_data['set_sales_target' ] + ")" , "Achive ("+all_targets_data['achive_sales_target' ]+")"],
                // // Percentage
                // labels: ["Target ("+all_targets_data['set_sales_target' ] + ")" , "Achive ("+((all_targets_data['achive_sales_target' ] * 100)/all_targets_data['set_sales_target' ]).toFixed(0)  + "%)"],
                // labels: ["Target ("+ all_targets_data['set_sales_target' ] + ")" , "Achive ("+achive_sales_target +")"],
                labels: ["Target ("+ set_formattedPrice + ")" , "Achive ("+achive_sales_target +")"],
                datasets: [
                        {
                            label: "Sales",
                            backgroundColor: ["#689f38", "#AB47BC"],
                            // data: [ (all_targets_data['set_sales_target' ]).toFixed(2)  , all_targets_data['achive_sales_target'] ]
                            data: [ (all_targets_data['set_sales_target' ]).toFixed(2)  , all_targets_data['achive_sales_target'] ]
                        }
                    ]
                },
                options: {
                    legend: {display: false},
                    title: {
                        display: true,
                        text: 'Sales',
                    },
                    // start from 0
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }); // End of use strict
    </script>

    <script>
        $('.custom_date_option').on('click', function()
        {
            var value=$( ".custom_date_option option:selected" ).val();
            if(value=='custom_date')
            {
                $('.custom_date_div').css('display', 'block');
                $('.custom_date_option_div').css('display', 'none');


                $(".start_date_input").datepicker({
                    // minDate: '-1D',
                    dateFormat:'dd-mm-yy',// Y-m-d
                    format:'dd-mm-yy',
                    maxDate: '+0D',
                    // // currentText: 'Today',
                    // numberOfMonths: 1,
                    gotoCurrent: true,
                    // changeMonth: false,
                    // changeYear: false,
                    // stepMonths: 0,
                    // //showButtonPanel: true
                }).datepicker("setDate",'now');


                $(".end_date_input").datepicker({
                    // minDate: '-1D',
                    dateFormat:'dd-mm-yy',// Y-m-d
                    format:'dd-mm-yy',
                    maxDate: '+0D',
                    // // currentText: 'Today',
                    // numberOfMonths: 1,
                    gotoCurrent: true,
                    // changeMonth: false,
                    // changeYear: false,
                    // stepMonths: 0,
                    // //showButtonPanel: true
                }).datepicker("setDate",'now');
            }
            else{
                $('.custom_date_div').css('display', 'none');
                $('.custom_date_option_div').css('display', 'block');
            }
        });
        $(document).ready(function ()
        {
            var value=$( ".custom_date_option option:selected" ).val();
            if (value=='custom_date')
            {
                $('.custom_date_div').css('display', 'block');
                $('.custom_date_option_div').css('display', 'none');
                var value1=$(".start_date_input" ).val();
                if(value1 !='' || value1 =='')
                {
                    $(".start_date_input").datepicker({
                        dateFormat:'dd-mm-yy',
                        maxDate: '+0D',
                        // // currentText: 'Today',
                        // numberOfMonths: 1,
                        gotoCurrent: true,
                        // changeMonth: true,
                        // changeYear: false,
                        // stepMonths: 0,
                        // //showButtonPanel: true
                    })
                    $('.custom_date_option').val('custom_date');
                }
                var value2=$(".end_date_input" ).val();
                if(value2 !='' || value2 =='')
                {
                    $(".end_date_input").datepicker({
                        dateFormat:'dd-mm-yy',
                        maxDate: '+0D',
                        // // currentText: 'Today',
                        // numberOfMonths: 1,
                        gotoCurrent: true,
                        // changeMonth: true,
                        // changeYear: false,
                        // stepMonths: 0,
                        // //showButtonPanel: true
                    });
                    $('.custom_date_option').val('custom_date');
                }
            }
            else
            {
                $('.custom_date_div').css('display', 'none');
                $('.custom_date_option_div').css('display', 'block');
            }
            $('.graph_undo').on('click', function()
            {
                var value=$( ".custom_date_option option:selected" ).val();
                if(value=='custom_date')
                {
                    $(".custom_date_option option:selected").prop("selected", false)
                    $('.custom_date_div').css('display', 'none');
                    $('.custom_date_option_div').css('display', 'block');
                }
            });
        });
    </script>

@endsection

