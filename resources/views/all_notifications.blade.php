@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Products')])

@section('content')
    @include('leads.sidebar')


    <div class="ps-main__wrapper">

        <header class="header--dashboard">

            <div class="header__left">
                <h3>Notifications111111111111 ({{count($notifications)}})</h3>
            </div>

        </header>


        <div class="main-container" id="remove_card" >
            <div class="cards" >

                @foreach ($notifications as $notification)

                    <div class="card card-4" style="width: 49%;">
                        <br>
                        @include('partials.notification-card-body', ['notification' => $notification])
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









