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

    <header class="header--dashboard">

        <div class="header__left">
            <h3>Edit Target</h3>
        </div>

    </header>

    <section class="ps-items-listing">

        <div class="ps-section__header">
            <div class="ps-section__filter">

                <form class="ps-form--filter" action="{{url('update-target',$target)}}" method="post">
                    @csrf

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" >
                                <label>User </label>
                                <div class="bootstrap-select fm-cmp-mg">

                                    <select class="form-control user_id" name="user_id" id="user_id">
                                        <option selected disabled @readonly(true)>{{$target->user_name->name}}</option>
                                    </select>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group" >
                                <strong> Start Date </strong>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" readonly name="startDate" value="{{ old('startDate' , date("d-m-Y", strtotime($target->start_date) ) )}}" class="{{-- datepicker --}} form-control start_date_input" placeholder="Enter Start Date" >
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group" >
                                <strong> End Date </strong>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" readonly name="endDate"value="{{ old('endDate' ,  date("d-m-Y", strtotime($target->expiry_date) ) )}}"  class="{{-- datepicker --}} form-control end_date_input" placeholder="Enter End Date" >
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label> Sales Target </label>
                                <input type="number" name="set_sales_target" class="form-control" placeholder="Sales" value="{{ old('set_sales_target' , $target->set_sales_target) }}">
                                @error('set_sales_target')<span class="text-danger">{{ $message }}</span>@enderror

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label> Unit Target </label>
                                <input type="number" name="set_unit_target" class="form-control" placeholder="Unit" value="{{old('set_unit_target' , $target->set_unit_target)}}">
                                @error('set_unit_target')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label> Lead Target </label>
                                <input type="number" name="set_lead_target" class="form-control" placeholder="Lead" value="{{old('set_lead_target' , $target->set_lead_target)}}">
                                @error('set_lead_target')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label> Affiliator Target </label>
                                <input type="number" name="set_affiliator_target" class="form-control" placeholder="Affiliator" value="{{old('set_affiliator_target' , $target->set_affiliator_target)}}">
                                @error('set_affiliator_target')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Verified Calls Target </label>
                                <input type="number" name="set_verified_calls_target" class="form-control" placeholder="Verified Calls" value="{{old('set_verified_calls_target' , $target->set_verified_calls_target)}}">
                                @error('set_verified_calls_target')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Client Meetings Target </label>
                                <input type="number" name="set_client_meetings_target" class="form-control" placeholder="Client Meetings" value="{{old('set_client_meetings_target' , $target->set_client_meetings_target)}}">
                                @error('set_client_meetings_target')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Dealer Meetings Target </label>
                                <input type="number" name="set_dealer_meetings_target" class="form-control" placeholder="Dealer Meetings" value="{{old('set_dealer_meetings_target' , $target->set_dealer_meetings_target)}}">
                                @error('set_dealer_meetings_target')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Freelancer Meeting Target </label>
                                <input type="number" name="set_freelancer_meetings_target" class="form-control" placeholder="Freelancer Meeting" value="{{old('set_freelancer_meetings_target' , $target->set_freelancer_meetings_target)}}">
                                @error('set_freelancer_meetings_target')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Site Visit Target </label>
                                <input type="number" name="set_site_visit_target" class="form-control" placeholder="Site Visit" value="{{old('set_site_visit_target' , $target->set_site_visit_target)}}">
                                @error('set_site_visit_target')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>&nbsp; </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="ps-btn success form-control" type="submit" name="submit" value="Update">
                                </div>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>

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
        })

        $(".end_date_input").datepicker({
            minDate: '0D',
            dateFormat:'dd-mm-yy',
            format:'dd-mm-yy',
            // maxDate: '+0D',
        })

    </script>

@endsection
