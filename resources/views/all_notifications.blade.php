@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Products')])

@section('content')
    @include('leads.sidebar')


    <div class="ps-main__wrapper">

        <header class="header--dashboard">

            <div class="header__left">
                <h3>Notifications ({{count($notifications)}})</h3>
            </div>

        </header>


        <div class="main-container" id="remove_card" >
            <div class="cards" >

                @foreach ($notifications as $notification)

                    <div class="card card-4" style="width: 49%;">
                        <br>
                        <div class="card__icon">
                            <p style=" font-size:18px; color: rgb(145, 0, 113)"><b>  {{$notification->type}}  </b></p>
                        </div>
                        <div class="card__title">
                                <p class="card__title" style="margin-left: 5%; margin-top: 5px;">

                                    @if($notification->redirect == 'leads' || $notification->redirect=='newleads')
                                        {{$notification->msg_body}}
                                    @else
                                        <?php echo base64_decode($notification->msg_body); ?>
                                    @endif
                                </p>
                            </a>
                        </div>
                        @if($notification->redirect=='leads')
                            <p class="card__apply"  >
                                <a href="{{url('/leads')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                    <strong> View Lead </strong><i class="fas fa-arrow-right" ></i>
                                </a>
                            </p>
                        @elseif($notification->redirect=='todolist')
                            <p class="card__apply"  >
                                <a href="{{url('todos/today')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                    <strong> View Meeting </strong><i class="fas fa-arrow-right" ></i>
                                </a>
                            </p>
                        @elseif($notification->redirect=='staff')
                            <p class="card__apply"  >
                                <a href="{{url('/staff')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                    <strong> View Staff </strong><i class="fas fa-arrow-right" ></i>
                                </a>
                            </p>
                        @elseif($notification->redirect=='inventory')
                            <p class="card__apply"  >
                                <a href="{{url('/inventory')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                    <strong> View Inventory </strong><i class="fas fa-arrow-right" ></i>
                                </a>
                            </p>
                        @elseif($notification->redirect=='newleads')
                            <p class="card__apply"  >
                                <a href="{{url('/newleads')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                    <strong> View Facebook Leads </strong><i class="fas fa-arrow-right" ></i>
                                </a>
                            </p>
                        @else
                            <p class="card__apply" >
                                <a href="{{url('/affiliators')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                    <strong> View Affiliator </strong><i class="fas fa-arrow-right" ></i>
                                </a>
                            </p>
                        @endif
                    </div>

                @endforeach

            </div>
        </div>



        <div class="ps-section__footer">
            <p>Showing {{($notifications->currentpage()-1)*$notifications->perpage()+1}} to {{$notifications->currentpage()*$notifications->perpage()}}
                of  {{$notifications->total()}} entries
            </p>
            {{ $notifications->appends(request()->except('page'))->links() }}
        </div>




    </div>


@endsection









