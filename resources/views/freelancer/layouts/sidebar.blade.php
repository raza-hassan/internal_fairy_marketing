<div class="ps-main__sidebar">
    <div class="ps-sidebar">
        <div class="ps-sidebar__content">
            <div class="ps-sidebar__center">
                <ul class="menu">


                    @if (Auth::guard('affiliator')->user()->type=='Freelancer')

                        <li><a class="active" href="{{url('affiliator')}}"><i class="icon-home"></i>Dashboard</a></li>
                        <li><a href="{{url('affiliator/leads')}}"><i class="fa fa-user-plus" aria-hidden="true"></i>Leads</a></li>
                        <li><a href="{{url('affiliator/inventory')}}"><i class="fa-solid fa-house-chimney-user" aria-hidden="true"></i>Inventory</a></li>

                    @elseif(Auth::guard('affiliator')->user()->type=='Dealer')

                        <li><a class="active" href="{{url('affiliator')}}"><i class="icon-home"></i>Dashboard</a></li>
                        <li><a href="{{url('affiliator/inventory')}}"><i class="fa-solid fa-house-chimney-user" aria-hidden="true"></i>Inventory</a></li>

                    @endif

                </ul>
            </div>

        </div>
    </div>
</div>
