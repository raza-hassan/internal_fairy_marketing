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
            <h3>All Targets</h3>
        </div>

        @if(Auth::user()->can('target.create'))
            <div class="header__right">
                <a class="ps-btn success" href="{{ url('targets') }}"><i class="icon icon-plus mr-2"></i> Add Target</a>
            </div>
        @endif

        @if(Auth::user()->can('target.repeat'))
            <div class="header__right">
                <a class="ps-btn success" href="{{ url('targets/repeat') }}"> Repeat Targets </a>
            </div>
        @endif

    </header>

    <section class="ps-items-listing">


        <div class="ps-section__header">
            <div class="ps-section__filter">

                <form class="ps-form--filter" action="{{url('target-search')}}" method="get">

                    <div class="row">


                        {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group" >
                                <strong> Target ID </strong>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" name="target_id" value="{{app('request')->input('target_id')}}" class="form-control" placeholder="Enter Target ID" >
                                </div>
                            </div>
                        </div> --}}


                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group" >
                                <strong> Start Date </strong>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" {{-- readonly --}} autocomplete="off" name="startDate" value="{{app('request')->input('startDate')}}" class="{{-- datepicker --}} form-control start_date_input" placeholder="Enter Start Date" >
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group" >
                                <strong> End Date </strong>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input type="text" {{-- readonly --}} autocomplete="off" name="endDate"value="{{app('request')->input('endDate')}}"  class="{{-- datepicker --}} form-control end_date_input" placeholder="Enter End Date" >
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>User </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control user_id" name="user_id" id="user_id">
                                        <option value="0">All</option>
                                        <?php
                                            $user_id = 0;
                                            if(app('request')->input('user_id') != ''){
                                                $user_id = app('request')->input('user_id');
                                            }else{
                                                $user_id = Auth::user()->id;
                                            }
                                        ?>
                                        @foreach($users as $user)
                                            <option <?php if($user_id == $user->id){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
                            @if(Auth::user()->can('target.edit') || Auth::user()->can('target.delete'))
                                <th> Action </th>
                            @endif
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
                            @if(Auth::user()->can('target.edit') || Auth::user()->can('target.delete'))
                                <td>
                                    <form action="{{ url('target/destroy', $target) }}" method="post">
                                        @csrf
                                        @method('delete')

                                        @if(Auth::user()->can('target.edit'))
                                            <div class="icons-list mr-5"  style="display: inline-flex">
                                                <a rel="tooltip" href="{{ url('edit/target', $target) }}" data-original-title="" title="" class="tooltip add_icon">
                                                    <i class="fa fa-edit " aria-hidden="true"></i>
                                                    <span class="tooltiptext">Edit Target</span>
                                                </a>
                                            </div>
                                        @endif

                                        @if(Auth::user()->can('target.delete'))
                                            <button type="button" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                <div class="ripple-container"></div>
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"> No Record Found! </td>
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
            // minDate: '0D',
            dateFormat:'dd-mm-yy',// Y-m-d
            format:'dd-mm-yy',
            // maxDate: '+0D',
        });//.datepicker("setDate",'now');

        $(".end_date_input").datepicker({
            // minDate: '0D',
            dateFormat:'dd-mm-yy',
            format:'dd-mm-yy',
            // maxDate: '+0D',
        });//.datepicker("setDate",'now');

	</script>

@endsection
