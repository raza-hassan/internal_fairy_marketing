<div class="ps-main__sidebar" >
    <div class="ps-sidebar__content">
        <div class="ps-sidebar__center">
            <ul class="menu">


                @if (Auth::guard('affiliator')->user()->type=='Freelancer')

                    <li><a class="active" href="{{url('affiliator')}}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                    <li><a href="{{url('affiliator/leads')}}"><i class="fa fa-sliders" aria-hidden="true"></i><span>Manage Leads</span></a></li>
                    <li><a href="{{url('affiliator/todolist')}}"><i class="fa fa-list" aria-hidden="true"></i><span>Leads ToDos</span></a></li>
                    <li><a href="{{url('affiliator')}}"><i class="fa fa-list" aria-hidden="true"></i><span>Affiliator ToDos</span></a></li>
                    <li><a href="{{url('affiliator/inventory')}}"><i class="fa-solid fa-house-chimney-user" aria-hidden="true"></i><span>Inventory</span></a></li>

                @elseif(Auth::guard('affiliator')->user()->type=='Dealer')

                    <li><a class="active" href="{{url('affiliator')}}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                    <li><a href="{{url('affiliator/inventory')}}"><i class="fa-solid fa-house-chimney-user" aria-hidden="true"></i><span>Inventory</span></a></li>

                @endif


            </ul>
        </div>
    </div>
</div>
</div>
