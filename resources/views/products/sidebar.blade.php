<div class="ps-main__sidebar">
    <div class="ps-sidebar">
        <div class="ps-sidebar__content">
            <div class="ps-sidebar__center">
                <ul class="menu">
                    <li><a class="active" href="{{url('/')}}"><i class="icon-home"></i>Dashboard</a></li>

                    @if(Auth::user()->role != 11 && Auth::user()->role != 12 )
                        <li><a href="{{url('inventory')}}"><i class="fa-solid fa-house-chimney-user"></i>Primary</a></li>
                        <li><a href="{{url('#')}}"><i class="fa-solid fa-hotel"></i>Secondary</a></li>
                    @endif

                    {{-- @if(Auth::user()->can('inventory.prices.update'))
                        <li><a href="{{url('priceupdate')}}"><i class="fa fa-gears" aria-hidden="true"></i><span>Product Prices Update</span></a></li>
                    @endif --}}

                    @if(Auth::user()->can('inventory.prices.update'))
                        <li><a href="{{url('priceupdate')}}"><i class="fa fa-arrow-up" aria-hidden="true"></i><span>Inventory Prices Update</span></a></li>
                    @endif

                    @if(Auth::user()->can('discount.prices.view'))
                        <li><a href="{{url('discountprice')}}"><i class="fa fa-money" aria-hidden="true"></i><span>Discount Price</span></a></li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>
