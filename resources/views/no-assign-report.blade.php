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



            <h3>Leads ({{$leads->total()}})</h3>



        </div>



        <div class="header__center">







        </div>



    </header>



    <section class="ps-items-listing">



        @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 )



        <div class="ps-section__header">



            <div class="ps-section__filter">



                <form class="ps-form--filter" action="{{url('noassign/search')}}" method="get">



                    <!--                    @csrf



                                        @method('post')-->



                    <div class="row">







                        @if(count($sources) > 0)



                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">



                            <div class="form-group">



                                <label>Sources </label>



                                <div class="bootstrap-select fm-cmp-mg">



                                    <select class="form-control" name="source_id">



                                        <option value="0">All</option>



                                        @foreach($sources as $source)



                                        <option <?php



                                        if (app('request')->input('source_id') == $source->id) {



                                            echo 'selected';



                                        }



                                        ?> value="{{$source->id}}">{{$source->type}}</option>



                                        @endforeach



                                    </select>



                                </div>



                            </div>



                        </div>



                        @endif







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



        @endif



        <?php



//        $assigned_leads = 0;



//        $inassigned_leads = 0;



        ?>



        @if(!empty($leads))



        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 filter-sec">



            <ul class="lead_sorting">



                <li><label>Total Leads : {{count($leads)}}</label></li>



            </ul>



        </div>



        <div class="ps-section__content">



            <div class="table-responsive">



                <table class="table ps-table">



                    <thead>



                        <tr>



                            <th><input type="checkbox" name="checkbox[]"></th>



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



                            <td><input type="checkbox" name="checkbox[]" value="{{$lead->id}}"></td>



                            <td>



                                @if(!empty($lead->client))



                                <div class="client-title tooltip">







                                    <div class="tooltiptext client-info">



                                        <div class="tooltip-head">Client Information:</div>



                                        <div class="inner-side"><label>Client Name: </label><span>{{ $lead->client->name }}</span></div>


                                        {{-- =======Showing Phone Numbers --}}
                                            @php
                                                $counter = 1;
                                            @endphp

                                            @if ($lead->client->phone)
                                                <div class="inner-side">
                                                    <label>Client Number {{ $counter }}: </label>
                                                    <span>{{ str_replace(' ', '', $lead->client->phone) }}</span>
                                                </div>
                                                @php
                                                    $counter++;
                                                @endphp
                                            @endif

                                            @if ($lead->client->clientNumbers)
                                                @foreach ($lead->client->clientNumbers as $phone)
                                                    @if($lead->client->phone != $phone->number)
                                                        <div class="inner-side">
                                                            <label>Client Number {{ $counter }}: </label>
                                                            <span>{{ str_replace(' ', '', $phone->number) }}</span>
                                                        </div>
                                                        @php
                                                            $counter++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endif

                                        {{-- =======Showing Phone Numbers --}}


                                        <div class="inner-side"><label>Client Email: </label><span>{{ $lead->client->email }}</span></div>	



                                    </div>



                                    <a href="{{url('client/edit',$lead->client_id)}}" class="client-title">



                                        <div class="title-flag">



                                            <img src="{{ asset('public/img/pak-flag.jpg')}}" style="width: 25px;">



                                            <strong>{{ $lead->client->name }}</strong>     



                                            <i class="fa fa-info-circle" aria-hidden="true"></i>



                                        </div>



                                    </a>



                                    <i class="fa fa-th" aria-hidden="true"></i>







                                    {{ $lead->client->leads_count()->count() }} Lead



                                    @else 



                                    Nothing



                                    @endif







                                </div>



                            </td>























                            {{-- ======================================================================================= --}}











                            <td>



                                @if($lead->user_id > 0)



                                {{ $lead->leadAgent->name }}    </br>







                                <span style="color: grey;">{{ $lead->leadAgent->designation->name }} - {{ $lead->leadAgent->department->name }}</span><br>







                                {{--  Lead Share With [User Name] --}}







                                {{-- @if ($lead->leadshare)



        				<span style="color: grey;">{{ $lead->leadshare->name }} </span><br>



                                @endif --}}







                                {{-- Lead Share With How much users [User Count] --}}







                                {{-- @if ($lead->shared_leads_count()->count() > 0 )



        				<span style="color: grey;">{{ $lead->shared_leads_count()->count() }}</span>



                                @endif --}}



                                @else 



                                Not Assigned



                                @endif











                            </td>











                            {{-- ======================================================================================= --}}







































                            <td>







                                <a style="color: #fcb800;" href="{{url('lead/edit',$lead->id)}}">



                                    {{ $lead->id }}



                                </a>



                                </br>



                                {{date('M d, Y', strtotime($lead->created_at))}}







                            </td>







                            <td>



                                @if( $lead->client->leadSource->subtype == 'user' )



                                {{ $lead->client->leadSource->type }}



                                @else 



                                {{ucfirst($lead->client->leadSource->subtype)}}



                                @if($lead->client->afflilate_id > 0)



                                <?php



                                $affiliator = $lead->client->leadAffiliator;



                                ?>



                                <br>



                                <div class="client-title tooltip">



                                    <div class="tooltiptext client-info">



                                        <div class="tooltip-head">Affiliator Information:</div>



                                        <div class="inner-side"><label>Name: </label><span>{{ ucfirst($affiliator->name) }}</span></div>



                                        <div class="inner-side"><label>Number: </label><span>{{ str_replace(' ','',$affiliator->phone) }}</span></div>



                                        <div class="inner-side"><label>Email: </label><span>{{ $affiliator->email }}</span></div>	



                                    </div>



                                    <a href="#" class="client-title">



                                        <div class="title-flag">



                                            <strong>{{ ucfirst($affiliator->name )}}</strong>     



                                            <i class="fa fa-info-circle" aria-hidden="true"></i>



                                        </div>



                                    </a>



                                </div>



                                @endif







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



                                @if(strlen($note) > 40)



                                {{substr($note, 0, 40).'...'}}



                                @else



                                {{$note}}



                                @endif



                                </br>



                                @endif



                                <span style="color: grey;">{{date('M d, Y', strtotime($lead->taskStatus->created_at))}}</span>



                            </td>



                            @else



                            <td>No Action</td>



                            @endif



                            <td>



                                @if(!empty($lead->project_id > 0))



                                {{$lead->project->name}}



                                @endif



                                @if(!empty($lead->item))



                                {{$lead->item->name}}</br>



                                Buy Primar



                                @else



                                </br>Unit Not Selected



                                @endif



                            </td>







                            <td style="width: 9%;">



                                <?php



                                if (!empty($lead->item)) {



                                    $item_id = $lead->item->id;



                                } else {



                                    $item_id = 0;



                                }



                                ?>







                                <a rel="tooltip" href="#" data-original-title="" title="" data-toggle="modal" data-bind="<?php echo $lead->id; ?>" data-client="<?php echo $lead->client_id; ?>" itemid="<?php echo $item_id; ?>" data-target="#tasklog" id="getTask">



                                    <i class="fa fa-calendar" aria-hidden="true"></i>



                                </a>







                            </td>



                        </tr>



                        @endforeach



                    </tbody>



                </table>



            </div>



        </div>







        <div class="ps-section__footer">



            <p>Showing {{($leads->currentpage()-1)*$leads->perpage()+1}} to {{$leads->currentpage()*$leads->perpage()}}



                of  {{$leads->total()}} entries



            </p>



            {{ $leads->appends(request()->except('page'))->links() }}



        </div>











        <div class="modal fade" id="tasklog" role="dialog">



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



                                    <th>Interest</th>



                                    <th>Task</th>



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







        @else



        No Record Found!



        @endif



    </section>



</div>







@endsection



