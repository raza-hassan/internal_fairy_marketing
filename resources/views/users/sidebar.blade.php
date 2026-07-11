<div class="ps-main__sidebar">

    <div class="ps-sidebar">

        <div class="ps-sidebar__content">

            <div class="ps-sidebar__center">

                <ul class="menu">

                    @if(Auth::user()->role == 0)

                        <li><a class="active" href="{{url('admin')}}"><i class="icon-home"></i>Dashboard</a></li>
                        <li><a href="{{url('admin/user/create')}}"><i class="fa fa-user-plus" aria-hidden="true"></i>Create User</a></li>
                        <li><a href="{{url('admin/users')}}"><i class="fa-regular fa-user-gear"></i>Manage Users</a></li>
                        <li><a href="{{url('admin/managers')}}"><i class="fa fa-link" aria-hidden="true"></i>Managers</a></li>

                    @else

                        <li><a class="active" href="{{url('admin')}}"><i class="icon-home"></i>Dashboard</a></li>

                        @if(Auth::user()->can('staff.view'))
                            <li><a href="{{url('staff')}}"><i class="fa-solid fa-users-gear"></i>Manage Staff</a></li>
                            <li><a href="{{url('inactive-staff')}}"><i class="fa-solid fa-users-gear"></i>Inactive Staff</a></li>
                        @endif

                        @if(Auth::user()->can('staff.tree.view'))
                            <li><a href="{{url('view-tree')}}"><i class="fa fa-tree" aria-hidden="true"></i>Staff Tree</a></li>
                        @endif

                        @if(Auth::user()->role == 10)
                            <li><a href="{{url('/compain')}}"><i class="fa-solid fa-users-gear"></i>Compain</a></li>
                        @endif

                        <!--<li><a href="{{url('teams')}}"><i class="icon-tree"></i>Manage Teams</a></li>-->
                        <!--<li><a href="{{url('contacts')}}"><i class="icon-percent-circle"></i>Contacts</a></li>-->


                        @if(Auth::user()->can('trashed.staff.view'))
                            <li><a href="{{url('trash-staff')}}"><i class="fa-solid fa-users-gear"></i>Trash Staff</a></li>
                        @endif

                        @if(Auth::user()->can('target.view'))
                            <li><a href="{{url('all-targets')}}"><i class="fa-solid fa-users-gear"></i>Manage Targets</a></li>
                        @endif


                    @endif


                </ul>

            </div>

        </div>

    </div>

</div>

