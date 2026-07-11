@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])

@section('content')
@include('leads.sidebar')

<div class="ps-main__wrapper">
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
    @if (session('error'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Error..!.. </strong> {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    <header class="header--dashboard">
        <div class="header__left">
            <h3>Reports</h3>
        </div>
        <div class="header__center">

        </div>

    </header>
    <section class="ps-items-listing">

        <div class="ps-section__header">
            <div class="row">
                <form class="ps-form--filter" action="{{url('user/reports')}}" method="get" autocomplete="off">
                    <!--@csrf
                    @method('post')-->

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Select User </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <select class="form-control" name="user_id">

                                    @if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                                        <option value="0">All</option>
                                        @endif
                                    <?php
                                        $user_id = 0;
                                            $user_id = app('request')->input('user_id');

                                        ?>
                                    @foreach($users as $user)
                                    <option <?php
                                    if ($user_id == $user->id) {
                                        echo 'selected';
                                    }
                                    ?> value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 custom_date_option_div">
                        <div class="form-group">
                            <label>Task </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <select class="form-control custom_date_option" name="task"  >
                                    <option <?php
                                    if (app('request')->input('task') == 'all') {
                                        echo 'selected';
                                    }
                                    ?> value="all">All Days</option>

                                    <option <?php
                                    if (app('request')->input('task') == 'today') {
                                        echo 'selected';
                                    }
                                    ?> value="today">Today</option>

                                    <option <?php
                                    if (app('request')->input('task') == 'yesterday') {
                                        echo 'selected';
                                    }
                                    ?> value="yesterday">Yesterday</option>

                                    <option <?php
                                    if (app('request')->input('task') == 'week') {
                                        echo 'selected';
                                    }
                                    ?> value="week">Week</option>

                                    <option <?php
                                    if (app('request')->input('task') == 'half_month') {
                                        echo 'selected';
                                    }
                                    ?> value="half_month">15 Days</option>

                                    <option <?php
                                    if (app('request')->input('task') == 'month') {
                                        echo 'selected';
                                    }
                                    ?> value="month">Month</option>

                                    <option <?php
                                    if (app('request')->input('task') == 'three_month') {
                                        echo 'selected';
                                    }
                                    ?> value="three_month">3 Months</option>

                                    <option <?php
                                    if (app('request')->input('task') == 'six_month') {
                                        echo 'selected';
                                    }
                                    ?> value="six_month"> 6 Months</option>

                                    <option <?php
                                    if (app('request')->input('task') == 'year') {
                                        echo 'selected';
                                    }
                                    ?> value="year">Year</option>

                                    <option
                                    <?php
                                        if (app('request')->input('task') == 'custom_date') {
                                        echo 'selected';
                                    }
                                    ?>
                                    value="custom_date" >Custome Date</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 custom_date_select" style="display: none;">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input class="form-control custom_date_input_start" type="text" name="custom_date_start"
                                value="{{app('request')->input('custom_date_start')}}"  placeholder="Start Date"
                                <?php Session::put('custom_date_start_session', app('request')->input('custom_date_start') ) ?>
                            />
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 custom_date_select" style="display: none;">
                        <div class="form-group">
                            <label>End Date</label>
                            <input class="form-control custom_date_input_end" type="text" name="custom_date_end"
                                value="{{app('request')->input('custom_date_end')}}"  placeholder="End Date"
                                <?php Session::put('custom_date_end_session', app('request')->input('custom_date_end') ) ?>
                            />
                        </div>
                        <button class="undo" style="font-size:16px"> Go Back <i class="fa fa-undo"></i></button>
                    </div>




                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label>Type </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <select class="form-control" name="type">

                                    <option value="0" <?php if (app('request')->input('type') == '0') { echo 'selected'; }?> >
                                        All
                                    </option>

                                    <option value="Calls" <?php if (app('request')->input('type') == 'Calls') { echo 'selected'; }?> >
                                        Calls
                                    </option>

                                    <option value="Meetings" <?php if (app('request')->input('type') == 'Meetings') { echo 'selected'; } ?> >
                                        Meetings
                                    </option>

                                    <option value="Sales" <?php if (app('request')->input('type') == 'Sales') { echo 'selected'; } ?> >
                                        Sales
                                    </option>

                                    <option value="Affiliators" <?php if (app('request')->input('type') == 'Affiliators') { echo 'selected'; } ?> >
                                        Affiliators
                                    </option>

                                    <option value="Dealers" <?php if (app('request')->input('type') == 'Dealers') { echo 'selected'; } ?> >
                                        Dealers
                                    </option>

                                    <option value="Freelancers" <?php if (app('request')->input('type') == 'Freelancers') { echo 'selected'; } ?> >
                                        Freelancers
                                    </option>

                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- <input type="hidden" name="task" value="" /> --}}



                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><label>&nbsp; </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <input class="ps-btn success form-control" type="submit" name="submit" value="Filter">
                            </div>
                        </div>
                    </div> --}}


                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><label>&nbsp; </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <input class="ps-btn success form-control" type="submit" name="action" value="Filter">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><label>&nbsp; </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <input class="ps-btn success form-control" type="submit" name="action" value="PDF">
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>

        <?php
            // $user_id = 0;
            if(app('request')->input('task') != ''){
                $select_task = app('request')->input('task');
            }else{
                $select_task = 'all';
            }

            $whatsapp_call='Whatsapp Call';
            $call_attempt='Call Attempt';

            ?>

        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th style="text-align:center;">Call Attempt</th>
                            <th style="text-align:center;"> Whatsapp Call </th>
                        </tr>
                    </thead>
                    <tbody>


                    <?php //$data[19]['followed_up'] = 1000; ?>
                        @foreach($users as $record)

                        <?php if(isset($data[$record->id]) && !empty($data[$record->id])){ ?>
                            <tr class="gradeX">

                                <td style="width: 15%; ">{{$record->name}}</td>
                                <td style=" text-align:center;"><a href="{{url('report_info/'.$record->id.'/'.$select_task.'/'.$call_attempt )}}" target="_blank">{{$data[$record->id]['call_attempt']}}</a></td>
                                <td style=" text-align:center;"><a href="{{url('report_info/'.$record->id.'/'.$select_task.'/'.$whatsapp_call )}}" target="_blank">{{$data[$record->id]['whatsapp_call']}} </a></td>

                            </tr>
                            <?php } ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

@endsection
