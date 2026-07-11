@extends('layouts.app', ['activePage' => 'affiliators', 'titlePage' => __('Products')])
@section('content')
@include('affiliators.sidebar')

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

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <header class="header--dashboard">
        <div class="header__left">
            <h3>Trash Affiliators ({{$affiliators->total()}})</h3>
        </div>
        <div class="header__center">
        </div>

    </header>

    <section class="ps-items-listing">

        <div class="ps-section__header">

            <div class="ps-section__filter">

                <form class="ps-form--filter" action="{{ url('trash-affiliator-search') }}" method="get">
                    <!--    @csrf
                    @method('post')-->
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Name </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="name" id="input-name" type="text" placeholder="{{ __('Enter Name') }}" value="{{app('request')->input('name')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="email" id="input-name" type="email" placeholder="{{ __('Enter Email') }}" value="{{app('request')->input('email')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Cell </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="form-control" name="phone" id="input-name" type="text" placeholder="{{ __('Enter Cell') }}" value="{{app('request')->input('phone')}}">
                                </div>
                            </div>
                        </div>
                        @if(count($users) > 0)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Allocation </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="user_id">
                                        @if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                                        <option value="0">All</option>
                                        @endif
                                        <?php
                                        $user_id = 0;
                                        if(app('request')->input('user_id') != ''){
                                            $user_id = app('request')->input('user_id');
                                        }else{
                                            $user_id = Auth::user()->id;
                                        }
                                        ?>
                                        @foreach($users as $user)
                                        <option <?php if($user_id == $user->id ){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Affiliator Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="type">
                                        <option value="0">Select Type</option>
                                        <option <?php if(app('request')->input('type') == 'Dealer'){ echo 'selected'; } ?> value="Dealer">Dealer</option>
                                        <option <?php if(app('request')->input('type') == 'Freelancer'){ echo 'selected'; } ?> value="Freelancer">Freelancer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="status">
                                        <option <?php if(app('request')->input('status') == ''){ echo 'selected'; } ?> value="">Select Status </option>
                                        <option <?php if(app('request')->input('status') == 1){ echo 'selected'; } ?> value="1">Active</option>
                                        <option <?php if(app('request')->input('status') == 0 && app('request')->input('status') != ''){ echo 'selected'; } ?> value="0">InActive</option>
                                        <option <?php if(app('request')->input('status') == 2){ echo 'selected'; } ?> value="2">Rejected</option>
                                        <option <?php if(app('request')->input('status') == 3){ echo 'selected'; } ?> value="3">Again Approval Request</option>
                                    </select>
                                </div>
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

        @if(!empty($affiliators))

            <div class="ps-section__content">

                @if(Auth::user()->can('trashed.client.delete'))
                <div class="row" style="margin-bottom:1%; text-align:center;">
                    <div class="col-md-4">
                        <span >Total Affiliators : {{count($affiliators)}}</span>
                    </div>
                    <div class="col-md-3">
                        <div class="count-checkboxes-wrapper">
                            <span id="count-checked-checkboxes">0</span> Checked
                            </div>
                    </div>

                    <div class="col-md-2">
                        <div class="count-uncheckboxes-wrapper">
                            <span id="count-unchecked-checkboxes">{{count($affiliators)}}</span> Un-Checked
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="count-uncheckboxes-wrapper" style="display: inline-flex;">
                            <form action="{{ url('multi/affiliators/delete/permanently') }}" method="post" style="margin: 0 10px;" id="multiDeleteAffilatorsForm">
                                @csrf
                                <input type="hidden" name="multi_del_affiliator_ids" id="multi_del_affiliator_ids" value="">
                                <button type="button" class="fa fa-trash" style="background: none; border: none; color: inherit; cursor: pointer; padding: 0;" data-original-title="" title="Delete Data" onclick="affiliatorsPermanentlyDeleteConfirm(event);"></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <style>
                #count-checked-checkboxes{color:red;}
                #count-unchecked-checkboxes{color:red;}
                tr.marked-row{background: #777;}
            </style>

                <div class="table-responsive affilator_class">
                    <table class="table ps-table count_form_checkboxes" id="tbl">
                        <thead>
                            <tr>
                                @if(Auth::user()->can('trashed.affiliator.delete'))
                                    <th><input type="checkbox" id="check_all_records" name="multi_records_checkbox"></th>
                                @endif
                                <th class="id-col">ID</th>
                                <th class="name-col">Name</th>
                                <th class="name-col">Business</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Total Leads</th>
                                <th>Last Leads</th>
                                <th>Address</th>
                                <th>Added</th>
                                <th>Status</th>

                                @if(Auth::user()->can('trashed.affiliator.restore') || Auth::user()->can('trashed.affiliator.delete') )
                                    <th>Actions</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affiliators as $affiliator)
                                <tr>
                                    @if(Auth::user()->can('trashed.affiliator.delete'))
                                        <td>
                                            <input id="a_chkDelete" class="chkDelete" type="checkbox" name="affiliatorids" value="{{$affiliator->id}}">
                                        </td>
                                    @endif
                                    <td class="id-col">{{ $affiliator->id }}</td>

                                    <td class="name-col">
                                        @if(Auth::user()->can('affiliator.edit'))
                                            <a href="{{route('affiliators.edit',$affiliator->id)}}"><strong>{{ $affiliator->name }}</strong></a>
                                        @else
                                            <strong>{{ $affiliator->name }}</strong>
                                        @endif
                                    </td>

                                    <td class="name-col">
                                        {{ $affiliator->business }}
                                    </td>
                                    <td>{{ $affiliator->phone }}</td>
                                    <td>{{ $affiliator->type }}</td>
                                    <?php
                                    $lead_count = $affiliator->leads_count()->count();
                                    ?>
                                    <td><a href="{{url('affiliator/leads', $affiliator->id)}}">{{ $lead_count }}</a></td>
                                    <td>
                                        <?php
                                        if ($lead_count > 0) {
                                            echo date('M d, Y', strtotime($affiliator->lastlead->created_at));
                                        } else {
                                            echo 'No Lead';
                                        }
                                        ?>
                                    </td>
                                    <td>{{ $affiliator->address }}</td>
                                    <td>
                                        {{ $affiliator->assigned->name }}<br>
                                        {{date('M d, Y', strtotime($affiliator->created_at))}}
                                    </td>
                                    <td>
                                        @if(!empty($affiliator->taskStatus))
                                        {{$affiliator->taskStatus->note}}
                                        @else
                                            No Action
                                        @endif
                                    </td>

                                    @if(Auth::user()->can('trashed.affiliator.restore') || Auth::user()->can('trashed.affiliator.delete') )
                                        <td>
                                            <div style="display: inline-flex">

                                                @if(Auth::user()->can('trashed.affiliator.restore'))
                                                    <form action="{{ url('affiliator/restore', $affiliator) }}" method="post" class="mr-10">
                                                        @csrf
                                                        <button type="button" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to Active this Record?") }}') ? this.parentElement.submit() : ''">
                                                            <i class="fa fa-lock-open" aria-hidden="true"></i>
                                                            <div class="ripple-container"></div>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(Auth::user()->can('trashed.affiliator.delete'))
                                                    <form action="{{ url('affiliator/delete/permanently', $affiliator) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="button" class="fa fa-trash " data-original-title="" title="Delete Data" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''"></button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="ps-section__footer">
                <p>Showing {{($affiliators->currentpage()-1)*$affiliators->perpage()+1}} to {{$affiliators->currentpage()*$affiliators->perpage()}}
                    of  {{$affiliators->total()}} entries
                </p>
                {{ $affiliators->appends(request()->except('page'))->links() }}
            </div>

        @else
                No Record Found!
        @endif

    </section>

</div>

@endsection
