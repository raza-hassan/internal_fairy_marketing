<div class="ps-main__sidebar">
    <div class="ps-sidebar">
        <div class="ps-sidebar__content">
            <div class="ps-sidebar__center">
                <ul class="menu">
                    <li><a class="active" href="{{url('/')}}"><i class="icon-home"></i>Dashboard</a></li>
                    <li><a href="{{ url('affiliators')}} "><i class="fa-solid fa-timeline"></i>Affiliators</a></li>
                    <li><a href="{{ url('dealors')}} "><i class="fa-solid fa-people-group"></i>Dealers</a></li>
                    <li><a href="{{ url('freeliencer') }}"><i class="icon-users2"></i>Freelancers</a></li>
                    <li><a href="{{ url('affiliator/todos') }}"><i class="fa fa-list" aria-hidden="true"></i>Todos</a></li>
                    @if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                    <!--<li><a href="{{url('inactive-affiliators')}}"><i class="fa fa-user-o" aria-hidden="true"></i><span>Inactive Affiliators</span></a></li>-->
                    <!--<li><a href="{{url('overdue-affiliator-task')}}"><i class="fa fa-clock" aria-hidden="true"></i><span>Overdue Tasks</span></a></li>-->
                    @endif
                    @if(Auth::user()->can('trashed.affiliator.view'))
                    <li><a href="{{url('/trash/affiliators')}}"><i class="fa fa-trash"></i><span>Trash Affiliators
                            </span></a></li>
                    @endif
                    @if(Auth::user()->can('manage.locations'))
                    <li>
                        <a href="{{route('locations.index')}}"><i class="fa fa-map-marker"></i>
                            <span>
                                Manage Locations
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
