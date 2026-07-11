@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Products')])
@section('content')
@include('users.rolepermissions.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3> Create Role Permissions</h3>
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

            <form method="post" action="{{ url('role-in-permission/store') }}">
                @csrf

                <div class="ps-form__content">

                    <div class="row">
                        <div class="col-sm-2">
                            <label>Role</label>
                        </div>
                        <div class="form-group col-sm-8">
                            <select class="ps-select full_width" name="role_id" required>
                                <option value="" selected disabled>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ (old("role_id") == $role->id ? "selected" : "" )}}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-check all_permission">
                                <input class="cursor_pointer" type="checkbox" id="all_permissions" name='all_permissions' @if(old('all_permissions')) checked @endif >
                                <label class="cursor_pointer" for="all_permissions">All Permissions</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-5">
                            <hr class="mt-2 mb-4">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            @error('permissions')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    @foreach($permission_groups as $group)
                        <div class="row">
                            <div class="col-md-3 col-5">
                                <div>
                                    <input class="cursor_pointer select_whole_group" name="group_names[]" value="{{$group->group_name}}" @if(is_array(old('group_names')) && in_array($group->group_name, old('group_names'))) checked @endif type="checkbox" id="{{$group->group_name}}-group" onclick="toggleGroupName('{{$group->group_name}}')">
                                    <strong>
                                        <label class="cursor_pointer capital_letter" for="{{$group->group_name}}-group">{{ $group->group_name }}</label>
                                    </strong>
                                </div>
                            </div>

                            @php
                                $permissions = App\Models\User::getPermissionByGroupName($group->group_name);
                            @endphp

                            <div class="col-md-9 col-7">
                                @foreach($permissions as $permission)
                                    <div>
                                        <input class="cursor_pointer select_permissions {{$group->group_name}}-group-options" type="checkbox" name="permissions[]" value="{{$permission->id}}" @if(is_array(old('permissions')) && in_array($permission->id, old('permissions'))) checked @endif id="permission-{{$permission->id}}" onclick="updateGroupName('{{$group->group_name}}')">
                                        <label class="cursor_pointer capital_letter" for="permission-{{$permission->id}}">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                                <hr>
                            </div>
                        </div>
                    @endforeach



                    <div class="ps-form__bottom">
                        <a class="ps-btn ps-btn--black" href="{{url('role-in-permissions')}}">Cancel</a>
                        <button class="ps-btn" type="submit">Submit</button>
                    </div>

                </div>

            </form>

        </div>

    </section>
</div>




<script type="text/javascript">

    $('#all_permissions').click(function(){
        if ($(this).is(':checked')) {
            $('input[type = checkbox]').prop('checked',true);
        }else{
            $('input[type = checkbox]').prop('checked',false);
        }
    });

    function toggleGroupName(group_name)
    {
        var main_group = document.getElementById(group_name+"-group");
        var group_options= document.getElementsByClassName(group_name+"-group-options");
        for (var i = 0; i < group_options.length; i++)
        {
            group_options[i].checked = main_group.checked;
        }
        check_all_permissions();
    }

    function updateGroupName(group_name){
        var main_group = document.getElementById(group_name+"-group");
        var group_options= document.getElementsByClassName(group_name+"-group-options");
        var allChecked = true;
        for (var i = 0; i < group_options.length; i++){
            if (!group_options[i].checked){
                allChecked = false;
                $('#all_permissions').prop('checked', false);
                break;
            }
        }
        main_group.checked = allChecked;
        check_all_permissions();
    }

    function check_all_permissions()
    {
        var permission_checkboxes= document.getElementsByClassName("select_permissions");
        var allChecked = true;

        // Convert HTMLCollection to an array
        var checkboxArray = Array.from(permission_checkboxes);

        checkboxArray.forEach(function(checkbox){
            if (!(checkbox.checked))
            {
                allChecked = false;
                return;
            }
        });

        if (allChecked){
            $('#all_permissions').prop('checked',true);
        } else {
            $('#all_permissions').prop('checked',false);
        }
    }

</script>

@endsection
