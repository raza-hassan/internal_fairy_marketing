@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.rolepermissions.sidebar')
<div class="ps-main__wrapper">

    <header class="header--dashboard">
        <div class="header__left">
            <h3> Create Permission</h3>
        </div>
    </header>

    @if (session('status'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Success! </strong> {{ session('status') }}
                </div>
            </div>
        </div>
    @endif

    @if (count($errors) > 0)
        <div class = "alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <section class="ps-new-item">

        <div class="ps-section__content">

            <form method="post" action="{{ url('permission/store') }}">
                @csrf

                <div class="ps-form__content">

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Group Name </label>
                                <div>
                                    <select class="ps-select full_width" name="group_name" required aria-required="true">
                                        <option value="" selected disabled>Select Group</option>

                                        <option value="lead"    {{ (old("group_name") == 'lead'     ? "selected" : "" )}} > Lead    </option>
                                        <option value="trashed-lead"    {{ (old("group_name") == 'trashed-lead'     ? "selected" : "" )}} > Trashed-Lead    </option>
                                        <option value="client"  {{ (old("group_name") == 'client'   ? "selected" : "" )}} > Client  </option>
                                        <option value="trashed-client"    {{ (old("group_name") == 'trashed-client'     ? "selected" : "" )}} > Trashed-Client    </option>
                                        <option value="affiliator "  {{ (old("group_name") == 'affiliator'   ? "selected" : "" )}} > Affiliator  </option>
                                        <option value="trashed-affiliator"    {{ (old("group_name") == 'trashed-affiliator'     ? "selected" : "" )}} > Trashed-Affiliator    </option>
                                        <option value="inventory"    {{ (old("group_name") == 'inventory'     ? "selected" : "" )}} > Inventory    </option>
                                        <option value="inventory-prices"    {{ (old("group_name") == 'inventory-prices'     ? "selected" : "" )}} > Inventory-Prices    </option>
                                        <option value="new-leads"    {{ (old("group_name") == 'new-leads'     ? "selected" : "" )}} > New-Leads    </option>
                                        <option value="staff"    {{ (old("group_name") == 'staff'     ? "selected" : "" )}} > Staff    </option>
                                        <option value="trashed-staff"    {{ (old("group_name") == 'trashed-staff'     ? "selected" : "" )}} > Trashed-Staff    </option>
                                        <option value="target"    {{ (old("group_name") == 'target'     ? "selected" : "" )}} > Target    </option>
                                        <option value="permission "  {{ (old("group_name") == 'permission'   ? "selected" : "" )}} > Permission  </option>
                                        <option value="role "  {{ (old("group_name") == 'role'   ? "selected" : "" )}} > Role  </option>
                                        <option value="role-in-permission "  {{ (old("group_name") == 'role-in-permission'   ? "selected" : "" )}} > Role-In-Permission  </option>

                                    </select>
                                    @error('group_name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group">
                                <label>Permission Name </label>
                                <div>
                                    <input class="form-control" type="text" name="name" type="text" required placeholder="{{ __('Permission Name') }}" value="{{old('name')}}">
                                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ps-form__bottom">
                        <a class="ps-btn ps-btn--black" href="{{url('permissions')}}">Cancel</a>
                        <button class="ps-btn" type="submit">Submit</button>
                    </div>

                </div>

            </form>

        </div>

    </section>
</div>
@endsection
