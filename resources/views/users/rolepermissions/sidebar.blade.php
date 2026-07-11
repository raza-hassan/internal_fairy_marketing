<div class="ps-main__sidebar">
    <div class="ps-sidebar">
        <div class="ps-sidebar__content">
            <div class="ps-sidebar__center">
                <ul class="menu">

                    <li><a class="active" href="{{url('/')}}"><i class="icon-home"></i>Dashboard</a></li>

                    @if(Auth::user()->can('permission.view'))
                        <li><a href="{{url('permissions')}}"><i class="fa fa-unlock-alt" aria-hidden="true"></i>Permissions</a></li>
                    @endif

                    @if(Auth::user()->can('role.view'))
                        <li><a href="{{url('roles')}}"><i class="fa fa-asterisk" aria-hidden="true"></i>Roles</a></li>
                    @endif

                    @if(Auth::user()->can('role-in-permission.view'))
                        <li><a href="{{url('role-in-permissions')}}"><i class="fa fa-expeditedssl" aria-hidden="true"></i>Role In Permissions</a></li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>

