@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Performance')])
@section('content')

    @include('leads.sidebar')

        <!-- Bootstrap CSS -->
        {{-- <link href="{{ asset('progress_chart/css/bootstrap.min.css') }}" rel="stylesheet"> --}}


        <div class="container">
            @if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec" >
                    <form class="ps-form--filter" action="{{url('users/sorting')}}" method="post" >
                        @csrf
                        @method('post')

                        <div class="col-xs-12 time_periord_div">
                            <div class="form-group">
                                <label>Select Period </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control select_time_periord_option" id="change_option" name="time_period"  >

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
                                        if (app('request')->input('time_period') == 'year') {
                                            echo 'selected';
                                        }
                                        ?> value="year">Year</option>

                                        {{-- <option
                                        <?php
                                            if (app('request')->input('time_period') == 'custom_date') {
                                            echo 'selected';
                                        }
                                        ?>
                                        value="custom_date" >Custome Date</option> --}}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="custom_date_div pl-15" style="display: none;">

                            <div class="form-group">
                                <label> Date Added </label>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <input type="text" readonly name="startDate" value="{{app('request')->input('startDate')}}" class="{{-- datepicker --}} form-control start_date_input" placeholder="Enter Start Date" >
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="bootstrap-select fm-cmp-mg" style="margin-left: 5px;">
                                            <input type="text" readonly name="endDate" value="{{app('request')->input('endDate')}}" class="{{-- datepicker --}} form-control end_date_input" placeholder="Enter End Date" >
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
                                    <select name="users[]" class="form-control multi_user" multiple>
                                        @foreach ($users as $user)
                                            <option <?php if($user_id ==$user->id ) {echo 'selected';}  ?>  value="{{ $user->id }}"  @if(Auth::user()->id == $user->id) selected @endif > {{ $user->name }} </option>
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
            @endif


            <div class="row target_charts">

                <div class="col-sm-6 col-xs-4 sales_chart" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">

                        <h4 class="pt-10 mb-0 text-center text-uppercase">Sales Chart</h4>
						<hr/>
						<div class="card">
							<div class="card-body">
								<div class="sales_target"></div>
							</div>
						</div>

                    </div>
                </div>

                <div class="col-sm-6 col-xs-4 unit_chart" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">

                        <h4 class="pt-10 mb-0 text-center text-uppercase">Units Chart</h4>
						<hr/>
						<div class="card">
							<div class="card-body">
								<div class="unit_target"></div>
							</div>
						</div>

                    </div>
                </div>


            </div>

            <div class="row target_charts">

                <div class="col-sm-6 col-xs-4 leads_chart" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">

                        <h4 class="pt-10 mb-0 text-center text-uppercase">Leads Chart</h4>
						<hr/>
						<div class="card">
							<div class="card-body">
								<div class="leads_target"></div>
							</div>
						</div>

                    </div>
                </div>

                <div class="col-sm-6 col-xs-4 affiliator_chart" >
                    <div class="panel panel-primary" style="border-color: #446161; margin-top: 30px;">

                        <h4 class="pt-10 mb-0 text-center text-uppercase">Affiliators Chart</h4>
						<hr/>
						<div class="card">
							<div class="card-body">
								<div class="affiliator_target"></div>
							</div>
						</div>

                    </div>
                </div>


            </div>


            <div class="row" >
                <div class="col-md-12 col-sm-11 col-xs-6" >
                    <button class="btn btn-lg btn-block" style="background: #446161; color:white" onclick="history.back()">Go Back</button>
                </div>
            </div>

        </div>

        <script>
            // ======custom Date Select

            $('.select_time_periord_option').on('click', function()
            {
                var value=$( ".select_time_periord_option option:selected" ).val();
                if(value=='custom_date')
                {
                    $('.custom_date_div').css('display', 'block');
                    $('.time_periord_div').css('display', 'none');


                    $(".start_date_input").datepicker({
                        dateFormat:'dd-mm-yy',
                        maxDate: '+0D',
                    });
                    $(".end_date_input").datepicker({
                        dateFormat:'dd-mm-yy',
                        maxDate: '+0D',
                    });

                }
                else{
                    $('.custom_date_div').css('display', 'none');
                    $('.time_periord_div').css('display', 'block');
                }
            });

            $(document).ready(function ()
            {
                var value=$( ".select_time_periord_option option:selected" ).val();

                if (value=='custom_date')
                {
                    $('.custom_date_div').css('display', 'block');
                    $('.time_periord_div').css('display', 'none');

                    var start_date=$(".start_date_input" ).val();
                    if(start_date !='')
                    {
                        $(".start_date_input").datepicker({
                            dateFormat:'dd-mm-yy',
                            maxDate: '+0D',
                        });
                    }

                    var end_date=$(".end_date_input" ).val();
                    if(end_date !='')
                    {
                        $(".end_date_input").datepicker({
                            dateFormat:'dd-mm-yy',
                            maxDate: '+0D',
                        });
                    }

                }
                else
                {
                    $('.custom_date_div').css('display', 'none');
                    $('.time_periord_div').css('display', 'block');
                }

                $('.graph_undo').on('click', function()
                {
                    var value=$( ".select_time_periord_option option:selected" ).val();

                    if(value=='custom_date')
                    {
                        $('.select_time_periord_option').val("this_month");
                        $('.custom_date_div').css('display', 'none');
                        $('.time_periord_div').css('display', 'block');
                    }
                });

            });

        </script>




	<!--plugins-->
	<script src="{{ asset('public/progress_chart/js/jquery.min.js') }}"></script>
	<script src="{{ asset('public/progress_chart/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>

    <script>

        var all_targets_data = {!! json_encode($user_target) !!};
        // console.log(all_targets_data);

        var user_name = new Array();
        var set_sales_target = new Array();
        var achive_sales_target = new Array();
        var set_unit_target = new Array();
        var achive_unit_target = new Array();
        var set_lead_target = new Array();
        var achive_lead_target = new Array();
        var set_affiliator_target = new Array();
        var achive_affiliator_target = new Array();

        $.each( all_targets_data, function(value)
        {
            // console.log(all_targets_data[value]['set_sales_target']);

            user_name.push(all_targets_data[value]['user_name']);

            set_sales_target.push(all_targets_data[value]['set_sales_target']);
            achive_sales_target.push(all_targets_data[value]['achive_sales_target']);

            set_unit_target.push(all_targets_data[value]['set_unit_target']);
            achive_unit_target.push(all_targets_data[value]['achive_unit_target']);

            set_lead_target.push(all_targets_data[value]['set_lead_target']);
            achive_lead_target.push(all_targets_data[value]['achive_lead_target']);

            set_affiliator_target.push(all_targets_data[value]['set_affiliator_target']);
            achive_affiliator_target.push(all_targets_data[value]['achive_affiliator_target']);

        });

       	// // Sales Target chart
        var sales_target_options = {
            series: [
                {
                    name: 'Set Sales Target',
                    type: 'column',
                    // data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
                    data: set_sales_target
                },
                {
                    name: 'Achive Sales Target',
                    type: 'line',
                    // data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
                    data: achive_sales_target
                }
            ],
            chart: {
                foreColor: '#9ba7b2',
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: true
                },
            },
            stroke: {
                width: [0, 4]
            },
            plotOptions: {
                bar: {
                    //horizontal: true,
                    columnWidth: '35%',
                    endingShape: 'rounded'
                }
            },
            colors: ["#0d6efd", "#212529"],
            title: {
                text: 'Sale Sources',
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: user_name,
            xaxis: {
                // type: 'datetime'
                type: 'date'
            },
            yaxis: [{
                title: {
                    text: 'Set Target',
                },
            }, {
                opposite: true,
                title: {
                   text: 'Achived Target',
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector(".sales_target"), sales_target_options);
        chart.render();

	    // // Unit Target chart
        var unit_target_options = {
            series: [{
                name: 'Set Unit Target',
                type: 'column',
                // data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
                data: set_unit_target
            }, {
                name: 'Achive Unit Target',
                type: 'line',
                // data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
                data: achive_unit_target
            }],
            chart: {
                foreColor: '#9ba7b2',
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: true
                },
            },
            stroke: {
                width: [0, 4]
            },
            plotOptions: {
                bar: {
                    //horizontal: true,
                    columnWidth: '35%',
                    endingShape: 'rounded'
                }
            },
            colors: ["#0d6efd", "#212529"],
            title: {
                text: 'Unit Sources',
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: user_name,
            xaxis: {
                // type: 'datetime'
                type: 'date',
            },
            yaxis: [{
                title: {
                    text: 'Set Target',
                },
            }, {
                opposite: true,
                title: {
                    text: 'Achived Target',
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector(".unit_target"), unit_target_options);
        chart.render();

        // // Leads Target chart
        var leads_target_options = {
            series: [{
                name: 'Set Leads Target',
                type: 'column',
                // data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
                data: set_lead_target
            }, {
                name: 'Achive Leads Target',
                type: 'line',
                // data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
                data: achive_lead_target
            }],
            chart: {
                foreColor: '#9ba7b2',
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: true
                },
            },
            stroke: {
                width: [0, 4]
            },
            plotOptions: {
                bar: {
                    //horizontal: true,
                    columnWidth: '35%',
                    endingShape: 'rounded'
                }
            },
            colors: ["#0d6efd", "#212529"],
            title: {
                text: 'Lead Sources'
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: user_name,
            xaxis: {
                // type: 'datetime'
                type: 'date'
            },
            yaxis: [{
                title: {
                    text: 'Set Target',
                },
            }, {
                opposite: true,
                title: {
                    text: 'Achived Target',
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector(".leads_target"), leads_target_options);
        chart.render();

        // // Affiliators Target chart
        var affiliator_target_options = {
            series: [{
                name: 'Set Affiliators Target',
                type: 'column',
                // data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
                data: set_affiliator_target
            }, {
                name: 'Achive Affiliators Target',
                type: 'line',
                // data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
                data: achive_affiliator_target
            }],
            chart: {
                foreColor: '#9ba7b2',
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: true
                },
            },
            stroke: {
                width: [0, 4]
            },
            plotOptions: {
                bar: {
                    //horizontal: true,
                    columnWidth: '35%',
                    endingShape: 'rounded'
                }
            },
            colors: ["#0d6efd", "#212529"],
            title: {
                text: 'Affiliator Sources'
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: user_name,
            xaxis: {
                // type: 'datetime'
                type: 'date'
            },
            yaxis: [{
                title: {
                    text: 'Set Target',
                },
            }, {
                opposite: true,
                title: {
                   text: 'Achived Target',
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector(".affiliator_target"), affiliator_target_options);
        chart.render();

    </script>





@endsection

