<div class="ps-main__sidebar">
    <div class="ps-sidebar">
        <div class="ps-sidebar__content">
            <div class="ps-sidebar__center">
                <ul class="menu">
                    <li><a class="active" href="{{url('admin')}}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                    <li><a href="{{url('admin/leads/')}}"><i class="fa fa-gears" aria-hidden="true"></i><span>Manage Leads</span></a></li>
                    <li><a href="{{url('admin/inventory')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span>Inventory</span></a></li>
                    <li><a href="{{url('admin/clients')}}"><i class="icon-papers"></i><span>Clients</span></a></li>
                    <li><a href="{{url('admin/users')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span>Staff</span></a></li>
                    <li>
                        <a href="{{url('admin/logout')}}">
                            <i class="fa fa-tasks" aria-hidden="true"></i>Logout</a>
                    </li>
                </ul>
            </div>
            <div class="ps-sidebar__footer">
                <div class="ps-copyright"><img src="{{ asset('public/img/logo.png')}}" alt="">
                    <p>&copy;Copy Right{{date('Y')}} Fairy Marketing <br/> All rights reversed.</p>
                </div>
            </div>
        </div>
    </div>
</div>
