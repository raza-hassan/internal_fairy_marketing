@extends('layouts.app', ['activePage' => 'newleads', 'titlePage' => __('Products')])

@section('content')
@include('leads.sidebar')

<style>
    .ps-main__wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    .ps-section__content {
        width: 100%;
        max-width: 100%;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    table.ps-table {
        width: 100% !important;
        table-layout: auto;
    }

    .ps-table td {
        word-break: break-word;
        white-space: normal;
    }
</style>
<div class="ps-main__wrapper">
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
    @if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom:15px!important">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <header class="header--dashboard">

        <div class="header__left">
            <h3>Leads</h3>
        </div>
        <div class="header__center">

        </div>

        @if(Auth::user()->can('facebook.leads.import'))
        <div class="header__right">
            <a class="ps-btn success" href="{{ url('facebook-leads-import') }}"><i
                    class="icon icon-plus mr-2"></i>Facebook Lead</a>
        </div>
        @endif

        @if(Auth::user()->can('client.import.option'))
        <div class="header__right">
            <a class="ps-btn success" href="{{url('client-leads-import') }}"><i class="icon icon-plus mr-2"></i>Clients
                Import</a>
        </div>
        @endif

        @if(Auth::user()->can('facebook.leads.sync'))
        <div class="header__right">
            <a class="ps-btn success" href="{{url('facebookleads') }}"><i class="icon icon-gear mr-2"></i>FaceBook
                Sync</a>
        </div>
        @endif

    </header>
    <section class="ps-items-listing">

        @if(Auth::user()->can('assign.leads.option'))
        <form class="" action="{{url('leads/assigned')}}" method="post" id="count_form_checkboxes">
            @csrf
            @method('post')
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec">
                    <div class="form-group">
                        <label>Allocation </label>
                        <div class="bootstrap-select fm-cmp-mg">
                            <select class="form-control" name="user_id" required="">
                                <option value="" selected disabled>Select User</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                        <div class="form-group"><label>&nbsp; </label>
                            <div class="bootstrap-select fm-cmp-mg">
                                <input class="ps-btn success form-control" type="submit" name="submit"
                                    value="Assigne Leads">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            @if(!empty($leads))

            <div class="ps-section__content">

                @if(Auth::user()->can('assign.leads.option'))
                {{-- ============================= --}}
                <div class="row" style="margin-bottom: 20px; text-align:center;">

                    <div class="col-md-3">
                        <span>Total Leads : {{count($leads)}}</span>
                    </div>
                    <div class="col-md-3">
                        <div class="count-checkboxes-wrapper">
                            <span id="count-checked-checkboxes">0</span> Assign
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="count-uncheckboxes-wrapper">
                            <span id="count-unchecked-checkboxes">{{count($leads)}}</span> Un-Assign
                        </div>
                    </div>
                    <div class="class="col-md-3 header__right">
                        <a href="javascript:void(0);" id="btnMultiTrashLeads" title="Delete Selected Leads">
                            <i class="fa fa-trash text-danger" style="font-size:22px;cursor:pointer;"></i>
                        </a>
                    </div>


                </div>

                <style>
                    #count-checked-checkboxes {
                        color: red;
                    }

                    #count-unchecked-checkboxes {
                        color: red;
                    }

                    tr.marked-row {
                        background: #777;
                    }
                </style>
                {{-- ============================= --}}
                @endif

                <div class="table-responsive">
                    <table class="table ps-table" id="tbl">
                        <thead>
                            <tr>
                                @if(Auth::user()->can('assign.leads.option'))
                                   <th> <input type="checkbox" id="selectAll"></th>
                                @endif
                                <th>Client</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Lead-ID</th>
                                <th>Source</th>
                                <th>Interested</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $lead)
                            <tr>
                                @if(Auth::user()->can('assign.leads.option'))
                                <td><input id="a_chkDelete" class="chkDelete" type="checkbox" name="leadids[]"
                                        value="{{$lead->id}}"></td>
                                @endif

                                <td>
                                    {{ $lead->client->name }}
                                </td>
                                <td>
                                    {{ $lead->client->phone }}
                                </td>
                                <td>
                                    {{ $lead->client->email }}
                                </td>
                                <td>
                                    {{$lead->client->address}}
                                </td>

                                <td>
                                    @if(Auth::user()->can('lead.edit'))
                                    <a style="color: #fcb800;" href="{{url('lead/edit',$lead->id)}}">
                                        {{ $lead->id }}
                                    </a>
                                    @else
                                    <strong>{{ $lead->id }} </strong>
                                    @endif
                                    <br>
                                    {{date('M d, Y', strtotime($lead->created_at))}}

                                </td>
                                <td>{{$lead->client->leadSource->type}}</td>

                                <td>
                                    @if(!empty($lead->project_id > 0))
                                    {{$lead->project->name}}
                                    @else
                                    Project Unknown
                                    @endif
                                </td>


                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </form>

        @else
        No Record Found!
        @endif

        <form id="multiLeadTrashForm" action="{{ route('leads.multi-trash') }}" method="POST" style="display:none;">
            @csrf

            <div id="selectedLeadIds"></div>
        </form>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {

        function updateCount() {

            var checkedCount = $('.chkDelete:checked').length;
            var totalCount = $('.chkDelete').length;

            $('#count-checked-checkboxes').text(checkedCount);
            $('#count-unchecked-checkboxes').text(totalCount - checkedCount);
        }

        $('#selectAll').change(function () {

            $('.chkDelete').prop('checked', $(this).prop('checked'));

            $('.chkDelete').each(function () {

                if ($(this).is(':checked')) {
                    $(this).closest('tr').addClass('marked-row');
                } else {
                    $(this).closest('tr').removeClass('marked-row');
                }

            });

            updateCount();
        });

        $('.chkDelete').change(function () {

            if ($(this).is(':checked')) {
                $(this).closest('tr').addClass('marked-row');
            } else {
                $(this).closest('tr').removeClass('marked-row');
            }

            updateCount();
        });

        $('#btnMultiTrashLeads').click(function () {

            var checked = $('.chkDelete:checked');

            if (checked.length === 0) {
                alert('Please select at least one lead.');
                return;
            }

            if (!confirm('Are you sure you want to delete selected leads?')) {
                return;
            }

            $('#selectedLeadIds').html('');

            checked.each(function () {
                $('#selectedLeadIds').append('<input type="text" name="leadids[]" value="' +$(this).val() +'">');
            });

            $('#multiLeadTrashForm').submit();
        });

    });

</script>

@endsection
