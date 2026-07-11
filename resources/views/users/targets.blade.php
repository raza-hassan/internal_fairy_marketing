@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.sidebar')



    <link href="{{ asset('public/multiselect/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('public/multiselect/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
	<script src="{{ asset('public/multiselect/plugins/select2/js/select2.min.js') }}"></script>
	<script src="{{ asset('public/multiselect/js/jquery.min.js') }}"></script>


<div class="ps-main__wrapper">

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

    @if (count($errors) > 0)
        <div class = "alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <header class="header--dashboard">

        <div class="header__left">
            <h3>Targets</h3>
        </div>

    </header>

    <section class="ps-items-listing">


        @if(Auth::user()->can('target.create'))
            <div class="ps-section__header">
                <div class="ps-section__filter">
                    <form class="ps-form--filter" action="{{url('set-targets')}}" method="get">

                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" >
                                    <label>User </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <select id="user_select" class="multiple-select form-control" name="user[]" multiple="multiple"  required="required">
                                            <option value="0"> All </option>
                                            @foreach($users as $user)
                                                <option <?php if(app('request')->input('user') == $user->name){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group" >
                                    <label>User </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <select class="form-control" name="user[]" multiple  required="required">
                                            <option value="0">All</option>
                                            @foreach($users as $user)
                                                <option <?php if(app('request')->input('user') == $user->name){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> --}}


                            {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group" >
                                    <label>Number of Months </label>
                                    <div class="bootstrap-select fm-cmp-mg">

                                        <select name="nbr_of_mnth" class="form-control">
                                            <option value="1"  <?php if (app('request')->input('nbr_of_mnth') == '1')  {echo 'selected';} ?> >1  </option>
                                            <option value="2"  <?php if (app('request')->input('nbr_of_mnth') == '2')  {echo 'selected';} ?> >2  </option>
                                            <option value="3"  <?php if (app('request')->input('nbr_of_mnth') == '3')  {echo 'selected';} ?> >3  </option>
                                            <option value="4"  <?php if (app('request')->input('nbr_of_mnth') == '4')  {echo 'selected';} ?> >4  </option>
                                            <option value="5"  <?php if (app('request')->input('nbr_of_mnth') == '5')  {echo 'selected';} ?> >5  </option>
                                            <option value="6"  <?php if (app('request')->input('nbr_of_mnth') == '6')  {echo 'selected';} ?> >6  </option>
                                            <option value="7"  <?php if (app('request')->input('nbr_of_mnth') == '7')  {echo 'selected';} ?> >7  </option>
                                            <option value="8"  <?php if (app('request')->input('nbr_of_mnth') == '8')  {echo 'selected';} ?> >8  </option>
                                            <option value="9"  <?php if (app('request')->input('nbr_of_mnth') == '9')  {echo 'selected';} ?> >9  </option>
                                            <option value="10" <?php if (app('request')->input('nbr_of_mnth') == '10') {echo 'selected';} ?> >10 </option>
                                            <option value="11" <?php if (app('request')->input('nbr_of_mnth') == '11') {echo 'selected';} ?> >11 </option>
                                            <option value="12" <?php if (app('request')->input('nbr_of_mnth') == '12') {echo 'selected';} ?> >12 </option>
                                        </select>

                                    </div>
                                </div>
                            </div> --}}


                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group" >
                                    <strong> Start Date </strong>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <input type="text" readonly name="startDate" value="{{app('request')->input('startDate')}}" class="{{-- datepicker --}} form-control start_date_input" placeholder="Enter Start Date" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group" >
                                    <strong> End Date </strong>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <input type="text" readonly name="endDate"value="{{app('request')->input('endDate')}}"  class="{{-- datepicker --}} form-control end_date_input" placeholder="Enter End Date" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label> Sales Target </label>
                                    <input type="number" name="set_sales_target" class="form-control" placeholder="Sales" required value="{{app('request')->input('set_sales_target')}}">
                                    @error('set_sales_target')<span class="text-danger">{{ $message }}</span>@enderror

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label> Unit Target </label>
                                    <input type="number" name="set_unit_target" class="form-control" placeholder="Unit" required value="{{app('request')->input('set_unit_target')}}">
                                    @error('set_unit_target')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label> Lead Target </label>
                                    <input type="number" name="set_lead_target" class="form-control" placeholder="Lead" required value="{{app('request')->input('set_lead_target')}}">
                                    @error('set_lead_target')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label> Affiliator Target </label>
                                    <input type="number" name="set_affiliator_target" class="form-control" placeholder="Affiliator" required value="{{app('request')->input('set_affiliator_target')}}">
                                    @error('set_affiliator_target')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Verified Calls Target </label>
                                    <input type="number" name="set_verified_calls_target" class="form-control" placeholder="Verified Calls" required value="{{app('request')->input('set_verified_calls_target')}}">
                                    @error('set_verified_calls_target')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Client Meetings Target </label>
                                    <input type="number" name="set_client_meetings_target" class="form-control" placeholder="Client Meetings" required value="{{app('request')->input('set_client_meetings_target')}}">
                                    @error('set_client_meetings_target')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Dealer Meetings Target </label>
                                    <input type="number" name="set_dealer_meetings_target" class="form-control" placeholder="Dealer Meetings" required value="{{app('request')->input('set_dealer_meetings_target')}}">
                                    @error('set_dealer_meetings_target')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Freelancer Meeting Target </label>
                                    <input type="number" name="set_freelancer_meetings_target" class="form-control" placeholder="Freelancer Meeting" required value="{{app('request')->input('set_freelancer_meetings_target')}}">
                                    @error('set_freelancer_meetings_target')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Site Visit Target </label>
                                    <input type="number" name="set_site_visit_target" class="form-control" placeholder="Site Visit" required value="{{app('request')->input('set_site_visit_target')}}">
                                    @error('set_site_visit_target')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>


                            {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label> Contacted Client </label>
                                    <input type="number" name="set_contacted_client_target" class="form-control" placeholder="Contacted Client" value="{{app('request')->input('set_contacted_client_target')}}">
                                </div>
                            </div> --}}

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>&nbsp; </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <input class="ps-btn success form-control" type="submit" name="submit" value="Set">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" >
                                <div class="form-group">
                                    <label>&nbsp; </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <a class="ps-btn success" href="{{ url('targets') }}" style="margin-top: 0px !important;"> Reset All </a>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th> Month</th>
                            <th> Sales</th>
                            <th> Unit</th>
                            <th> Lead</th>
                            <th> Affiliator</th>
                            <th> Verified Calls</th>
                            <th> Client Meetings</th>
                            <th> Dealer Meetings</th>
                            <th> Freelancer Meetings</th>
                            <th> Site Visit</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($targets as $target)
                            <tr>

                                <td>{{ $target->user_name->name }}</td>
                                <td> {{date('F Y', strtotime($target->start_date))}} </td>
                                <td>{{ $target->set_sales_target }}</td>
                                <td>{{ $target->set_unit_target }}</td>
                                <td>{{ $target->set_lead_target }}</td>
                                <td>{{ $target->set_affiliator_target }}</td>
                                <td>{{ $target->set_verified_calls_target }}</td>
                                <td>{{ $target->set_client_meetings_target }}</td>
                                <td>{{ $target->set_dealer_meetings_target }}</td>
                                <td>{{ $target->set_freelancer_meetings_target }}</td>
                                <td>{{ $target->set_site_visit_target }}</td>


                            </tr>

                        @empty
                            <tr>
                                <td colspan="6"> No Record Found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $targets->links() }}

    </section>
</div>



	<script>

		$('.multiple-select').select2({
			theme: 'bootstrap4',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			// placeholder: $(this).data('data-placeholder'),
            placeholder : "Select User",
			allowClear: Boolean($(this).data('allow-clear')),
		});

        $(".start_date_input").datepicker({
            minDate: '0D',
            dateFormat:'dd-mm-yy',// Y-m-d
            format:'dd-mm-yy',
            // maxDate: '+0D',
        }).datepicker("setDate",'now');

        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date(y, m + 1, 0);
        //    alert(lastDay);

        $(".end_date_input").datepicker({
            minDate: '0D',
            dateFormat:'dd-mm-yy',
            format:'dd-mm-yy',
            // maxDate: '+0D',
        }).datepicker("setDate",lastDay);

    </script>

@endsection
