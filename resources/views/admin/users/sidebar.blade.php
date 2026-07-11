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
                        <li><a href="{{url('staff')}}"><i class="fa-solid fa-users-gear"></i>Manage Staff</a></li>
                        <!--<li><a href="{{url('teams')}}"><i class="icon-tree"></i>Manage Teams</a></li>-->
                        <!--<li><a href="{{url('contacts')}}"><i class="icon-percent-circle"></i>Contacts</a></li>-->
                    @endif
                </ul>
            </div>

        </div>
    </div>
</div>
