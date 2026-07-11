<div class="ps-main__sidebar" >
    <div class="ps-sidebar__content">
        <div class="ps-sidebar__center">
            <ul class="menu">
                <li><a class="active" href="{{url('/')}}"><i class="icon-home"></i><span>Dashboard</span></a></li>

                @if(Auth::user()->department_id != 2 && Auth::user()->department_id != 5 )

                    <li><a href="{{url('leads')}}"><i class="fa fa-sliders" aria-hidden="true"></i><span>Manage Leads</span></a></li>
                    <li><a href="{{url('todolist')}}"><i class="fa fa-list" aria-hidden="true"></i><span>Leads ToDos</span></a></li>

                    @if( Auth::user()->role == 4 || Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                        <li><a href="{{url('affiliator/todos')}}"><i class="fa fa-list" aria-hidden="true"></i><span>Affiliator ToDos</span></a></li>
                    @endif

                    @if(Auth::user()->can('lead.create'))
                        <li><a href="{{url('lead/create')}}"><i class="fa fa-plus" aria-hidden="true"></i><span>Add New Lead</span></a></li>
                    @endif

                    @if(Auth::user()->role != 9 && Auth::user()->role != 10 && Auth::user()->role != 12)
                        <li><a href="{{url('share/leads')}}"><i class="fa fa-share-alt" aria-hidden="true"></i><span>Share Leads</span></a></li>
                        <li><a href="{{url('lead/performance/graph')}}"><i class="fa fa-line-chart" aria-hidden="true"></i><span>Performance</span></a></li>
                    @endif
                @endif

                <li><a href="{{url('/all/notifications')}}"><i class="fa fa-bell"></i><span>Notifications</span></a></li>

                @if(Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
                    <li><a href="{{url('unassignedleads')}}"><i class="fa fa-user-o" aria-hidden="true"></i><span>Not Assigned Leads</span></a></li>
                  {{--<li><a href="{{url('reports')}}"><i class="fa fa-file-text" aria-hidden="true"></i><span>Reports</span></a></li> --}}
                @endif

                <li><a href="{{url('reports')}}"><i class="fa fa-file-text" aria-hidden="true"></i><span>Reports</span></a></li>

                @if(Auth::user()->can('trashed.lead.view'))
                    <li><a href="{{url('/tarsh/leads')}}"><i class="fa fa-trash"></i><span>Trash Leads</span></a></li>
                @endif

            </ul>
        </div>
    </div>
</div>
</div>
