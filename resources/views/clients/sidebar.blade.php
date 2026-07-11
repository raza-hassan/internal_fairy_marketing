<div class="ps-main__sidebar">
    <div class="ps-sidebar">
        <div class="ps-sidebar__content">
            <div class="ps-sidebar__center">
                <ul class="menu">
                    <li><a class="active" href="{{url('/')}}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                    <li><a href="{{url('#')}}"><i class="fa fa-database"></i><span>Clients Summary</span></a></li>

                    @if(Auth::user()->can('client.create'))
                        <li><a href="{{url('clients/create')}}"><i class="icon-plus-circle"></i><span>Add New Client</span></a></li>
                    @endif

                    @if(Auth::user()->can('trashed.client.view'))
                        <li><a href="{{url('/trash/clients')}}"><i class="fa fa-trash"></i><span>Trash Clients </span></a></li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>



