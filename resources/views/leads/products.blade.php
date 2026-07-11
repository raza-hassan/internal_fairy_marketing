@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Products')])

@section('content')
@include('leads.sidebar')
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
    <header class="header--dashboard">

        <div class="header__left">
            <h3>Leads</h3>
        </div>
        <div class="header__center">

        </div>

    </header>
    <section class="ps-items-listing">

        @if(!empty($leads))

        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Allocated To</th>
                            <th>Lead-ID</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Interest</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $lead)
                        <tr>
                            <td>
                                @if(!empty($lead->client))
                                <a href="#">
                                    <div class="title-flag">
                                        <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">

                                        <div class="tooltip">
                                            <strong>{{ $lead->client->name }}</strong> 
                                            <div class="tooltiptext client-info">
                                                <div class="tooltip-head">Client Information:</div>
                                                <div class="inner-side"><label>Client Name: </label><span>{{ $lead->client->name }}</span></div>
                                                <div class="inner-side"><label>Client Number: </label><span>{{ str_replace(' ','',$lead->client->phone) }}</span></div>
                                                <div class="inner-side"><label>Client Email: </label><span>{{ $lead->client->leadSource->type }}</span></div>	

                                            </div>

                                        </div>

                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    </div>
                                </a>
                                <i class="fa fa-th" aria-hidden="true"></i>

                                {{ $lead->client->leads_count()->count() }} Lead
                                @else 
                                Nothing
                                @endif


                            </td>
                            <td>
                                {{ $lead->leadAgent->name }}</br>
                                <span style="color: grey;">{{ $lead->leadAgent->designation->name }} - {{ $lead->leadAgent->department->name }}</span>
                            </td>
                            <td>

                                <a style="color: #fcb800;" href="#">
                                    {{ $lead->id }}
                                </a>
                                </br>
                                {{date('M d, Y', strtotime($lead->created_at))}}

                            </td>

                            <td>
                                @if( $lead->client->leadSource->subtype == 'user' )
                                {{ $lead->client->leadSource->type }}
                                @else 
                                {{ucfirst($lead->client->leadSource->subtype)}}<br>
                                {{ucfirst($lead->client->leadAffiliator->name)}}
                                @endif
                            </td>

                            @if(!empty($lead->taskStatus))
                            <td>
                                <?php
                                $note = $lead->taskStatus->note;
                                ?>
                                {{$lead->taskStatus->subtype}}</br>
                                @if($lead->taskStatus->subtype == 'Token Payment')
                                ({{$lead->token_amount}})</br>
                                @endif
                                @if($lead->taskStatus->subtype == 'Down Payment')
                                {{$lead->down_payment}}</br>
                                @endif
                                @if($note != '')
                                {{$lead->taskStatus->note}}</br>
                                @endif
                                <span style="color: grey;">{{date('M d, Y', strtotime($lead->taskStatus->created_at))}}</span>
                            </td>
                            @else
                            <td>No Action</td>
                            @endif
                            <td>

                                @if(!empty($lead->item))
                                {{$lead->item->name}}</br>
                                Buy Primary
                                @else
                                Null
                                @endif
                            </td>

                            <td>
                                <?php
                                if (!empty($lead->item)) {
                                    $item_id = $lead->item->id;
                                } else {
                                    $item_id = 0;
                                }
                                
                                ?>

                                
                                <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" itemid="<?php echo $item_id; ?>" data-target="#account_log" id="view_history">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </a>
                                <button type="button" class="btn btn-info waves-effect create_status" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" request-type='<?php echo $lead->status_id; ?>' item_id="<?php echo $item_id; ?>" data-target="#myModalthree">
                                    <i class="fa-solid fa-list-check"></i>
                                </button>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ps-section__footer">
            {{ $leads->links() }}
        </div>
        <div class="modal fade" role="dialog" id="account_log">
            <div class="modal-dialog modal-large">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">X</button>
                    </div>
                    <div class="modal-body">
                        <table class="table ps-table">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>Item</th>
                                    <th>Status</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody id="taskrow">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModalthree" role="dialog">
            <div class="modal-dialog modal-default">
                <form method="post" action="{{ url('lead/status') }}" class="form-horizontal">
                    @csrf
                    @method('post')
                    <div class="modal-content" style="overflow-y: unset !important;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">

                            <figure class="ps-block--form-box">

                                <div class="ps-block__content">
                                    <div class="form-group">
                                        <label>Token Status
                                        </label>
                                        <select class="ps-select form-control" title="Status" name="status" required="">
                                            <option value="">--Token Status--</option>
                                            <option value="1">Accepted</option>
                                            <option value="0">Rejected</option>
                                            <option value="2">Pending</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="added_by" id="added_by" value="{{Auth::user()->id}}">
                                    <input type="hidden" name="lead_id" id="lead_id" value="">
                                    <input type="hidden" name="item_id" id="item_id" value="">
                                    <input type="hidden" name="type" id="request_type" value="">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea class="form-control" rows="2" name="notes" required="true" required="">{{ old('notes') }}</textarea>
                                    </div>



                                </div>
                            </figure>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Save changes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @else
        No Record Found!
        @endif
    </section>
</div>

@endsection