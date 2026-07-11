<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        // php artisan db:seed          ==> command run


        $ceo_exist = Role::where('name', 'CEO')->exists();
        if (!$ceo_exist){
            $ceo_role = Role::create(['name' => 'CEO']);
        }else{
            $ceo_role = Role::where('name', 'CEO')->first();
        }

        $coo_exist = Role::where('name', 'COO')->exists();
        if (!$coo_exist){
            $coo_role = Role::create(['name' => 'COO']);
        }else{
            $coo_role = Role::where('name', 'COO')->first();
        }

        $head_of_sale_exist = Role::where('name', 'Head-of-Sale')->exists();
        if (!$head_of_sale_exist){
            $head_of_sale_role = Role::create(['name' => 'Head-of-Sale']);
        }else{
            $head_of_sale_role = Role::where('name', 'Head-of-Sale')->first();
        }

        $manager_exist = Role::where('name', 'Manager')->exists();
        if (!$manager_exist){
            Role::create(['name' => 'Manager']);
        }

        $bdo_exist = Role::where('name', 'BDO')->exists();
        if (!$bdo_exist){
            Role::create(['name' => 'BDO']);
        }



        // Creating All Permissions



        // Create Permissions For lead
        $lead_view_exist = Permission::where('name', 'lead.view')->exists();
        if (!$lead_view_exist){
            Permission::create([ 'name' => 'lead.view'  , 'group_name' => 'lead']);
        }
        $lead_create_exist = Permission::where('name', 'lead.create')->exists();
        if (!$lead_create_exist){
            Permission::create([ 'name' => 'lead.create'  , 'group_name' => 'lead']);
        }
        $lead_edit_exist = Permission::where('name', 'lead.edit')->exists();
        if (!$lead_edit_exist){
            Permission::create([ 'name' => 'lead.edit'  , 'group_name' => 'lead']);
        }
        $lead_share_exist = Permission::where('name', 'lead.share')->exists();
        if (!$lead_share_exist){
            Permission::create([ 'name' => 'lead.share'  , 'group_name' => 'lead']);
        }
        $lead_transfer_exist = Permission::where('name', 'lead.transfer')->exists();
        if (!$lead_transfer_exist){
            Permission::create([ 'name' => 'lead.transfer'  , 'group_name' => 'lead']);
        }
        $lead_trash_exist = Permission::where('name', 'lead.trash')->exists();
        if (!$lead_trash_exist){
            Permission::create([ 'name' => 'lead.trash'  , 'group_name' => 'lead']);
        }


        // Create Permissions For Trashed Leads
        $trashed_lead_view_exist = Permission::where('name', 'trashed.lead.view')->exists();
        if (!$trashed_lead_view_exist){
            Permission::create([ 'name' => 'trashed.lead.view'  , 'group_name' => 'trashed-lead']);
        }
        $trashed_lead_share_exist = Permission::where('name', 'trashed.lead.share')->exists();
        if (!$trashed_lead_share_exist){
            Permission::create([ 'name' => 'trashed.lead.share'  , 'group_name' => 'trashed-lead']);
        }
        $trashed_lead_transfer_exist = Permission::where('name', 'trashed.lead.transfer')->exists();
        if (!$trashed_lead_transfer_exist){
            Permission::create([ 'name' => 'trashed.lead.transfer'  , 'group_name' => 'trashed-lead']);
        }
        $trashed_lead_restore_exist = Permission::where('name', 'trashed.lead.restore')->exists();
        if (!$trashed_lead_restore_exist){
            Permission::create([ 'name' => 'trashed.lead.restore'  , 'group_name' => 'trashed-lead']);
        }
        $trashed_lead_delete_exist = Permission::where('name', 'trashed.lead.delete')->exists();
        if (!$trashed_lead_delete_exist){
            Permission::create([ 'name' => 'trashed.lead.delete'  , 'group_name' => 'trashed-lead']);
        }

        // Create Permissions For client
        $_client_view_exist = Permission::where('name', 'client.view')->exists();
        if (!$_client_view_exist){
            Permission::create([ 'name' => 'client.view'  , 'group_name' => 'client']);
        }
        $client_create_exist = Permission::where('name', 'client.create')->exists();
        if (!$client_create_exist){
            Permission::create([ 'name' => 'client.create'  , 'group_name' => 'client']);
        }
        $client_edit_exist = Permission::where('name', 'client.edit')->exists();
        if (!$client_edit_exist){
            Permission::create([ 'name' => 'client.edit'  , 'group_name' => 'client']);
        }
        $client_trash_exist = Permission::where('name', 'client.trash')->exists();
        if (!$client_trash_exist){
            Permission::create([ 'name' => 'client.trash'  , 'group_name' => 'client']);
        }

        // Create Permissions For Trash clients
        $trashed_client_view_exist = Permission::where('name', 'trashed.client.view')->exists();
        if (!$trashed_client_view_exist){
            Permission::create([ 'name' => 'trashed.client.view'  , 'group_name' => 'trashed-client']);
        }
        $trashed_client_restore_exist = Permission::where('name', 'trashed.client.restore')->exists();
        if (!$trashed_client_restore_exist){
            Permission::create([ 'name' => 'trashed.client.restore'  , 'group_name' => 'trashed-client']);
        }
        $trashed_client_delete_exist = Permission::where('name', 'trashed.client.delete')->exists();
        if (!$trashed_client_delete_exist){
            Permission::create([ 'name' => 'trashed.client.delete'  , 'group_name' => 'trashed-client']);
        }


        // Create Permissions For Affiliators
        $_affiliator_view_exist = Permission::where('name', 'affiliator.view')->exists();
        if (!$_affiliator_view_exist){
            Permission::create([ 'name' => 'affiliator.view'  , 'group_name' => 'affiliator']);
        }
        $affiliator_create_exist = Permission::where('name', 'affiliator.create')->exists();
        if (!$affiliator_create_exist){
            Permission::create([ 'name' => 'affiliator.create'  , 'group_name' => 'affiliator']);
        }
        $affiliator_edit_exist = Permission::where('name', 'affiliator.edit')->exists();
        if (!$affiliator_edit_exist){
            Permission::create([ 'name' => 'affiliator.edit'  , 'group_name' => 'affiliator']);
        }
        $affiliator_trash_exist = Permission::where('name', 'affiliator.trash')->exists();
        if (!$affiliator_trash_exist){
            Permission::create([ 'name' => 'affiliator.trash'  , 'group_name' => 'affiliator']);
        }


        // Create Permissions For Trashed Affiliators
        $trashed_affiliator_view_exist = Permission::where('name', 'trashed.affiliator.view')->exists();
        if (!$trashed_affiliator_view_exist){
            Permission::create([ 'name' => 'trashed.affiliator.view'  , 'group_name' => 'trashed-affiliator']);
        }
        $trashed_affiliator_restore_exist = Permission::where('name', 'trashed.affiliator.restore')->exists();
        if (!$trashed_affiliator_restore_exist){
            Permission::create([ 'name' => 'trashed.affiliator.restore'  , 'group_name' => 'trashed-affiliator']);
        }
        $trashed_affiliator_delete_exist = Permission::where('name', 'trashed.affiliator.delete')->exists();
        if (!$trashed_affiliator_delete_exist){
            Permission::create([ 'name' => 'trashed.affiliator.delete'  , 'group_name' => 'trashed-affiliator']);
        }


        // Create Permissions For Inventory
        $inventory_view_exist = Permission::where('name', 'inventory.view')->exists();
        if (!$inventory_view_exist){
            Permission::create([ 'name' => 'inventory.view'  , 'group_name' => 'inventory']);
        }
        $inventory_import_exist = Permission::where('name', 'inventory.import ')->exists();
        if (!$inventory_import_exist){
            Permission::create([ 'name' => 'inventory.import'  , 'group_name' => 'inventory']);
        }
        $inventory_export_exist = Permission::where('name', 'inventory.export ')->exists();
        if (!$inventory_export_exist){
            Permission::create([ 'name' => 'inventory.export'  , 'group_name' => 'inventory']);
        }
        $inventory_approve_option_exist = Permission::where('name', 'inventory.approve.option ')->exists();
        if (!$inventory_approve_option_exist){
            Permission::create([ 'name' => 'inventory.approve.option'  , 'group_name' => 'inventory']);
        }
        $inventory_existence_change_exist = Permission::where('name', 'inventory.existence.change')->exists();
        if (!$inventory_existence_change_exist){
            Permission::create([ 'name' => 'inventory.existence.change'  , 'group_name' => 'inventory']);
        }
        $inventory_history_exist = Permission::where('name', 'inventory.history')->exists();
        if (!$inventory_history_exist){
            Permission::create([ 'name' => 'inventory.history'  , 'group_name' => 'inventory']);
        }
        $inventory_edit_exist = Permission::where('name', 'inventory.edit')->exists();
        if (!$inventory_edit_exist){
            Permission::create([ 'name' => 'inventory.edit'  , 'group_name' => 'inventory']);
        }

        $inventory_prices_update_exist = Permission::where('name', 'inventory.prices.update')->exists();
        if (!$inventory_prices_update_exist){
            Permission::create([ 'name' => 'inventory.prices.update'  , 'group_name' => 'inventory-prices']);
        }
        $discount_prices_view_exist = Permission::where('name', 'discount.prices.view')->exists();
        if (!$discount_prices_view_exist){
            Permission::create([ 'name' => 'discount.prices.view'  , 'group_name' => 'inventory-prices']);
        }
        $discount_prices_edit_exist = Permission::where('name', 'discount.prices.edit')->exists();
        if (!$discount_prices_edit_exist){
            Permission::create([ 'name' => 'discount.prices.edit'  , 'group_name' => 'inventory-prices']);
        }


        // Create Permissions For New lead
        $new_lead_view_exist = Permission::where('name', 'new-leads.view')->exists();
        if (!$new_lead_view_exist){
            Permission::create([ 'name' => 'new-leads.view'  , 'group_name' => 'new-leads']);
        }
        $facebook_lead_sync_exist = Permission::where('name', 'facebook.leads.sync')->exists();
        if (!$facebook_lead_sync_exist){
            Permission::create([ 'name' => 'facebook.leads.sync'  , 'group_name' => 'new-leads']);
        }
        $client_import_option_exist = Permission::where('name', 'client.import.option')->exists();
        if (!$client_import_option_exist){
            Permission::create([ 'name' => 'client.import.option'  , 'group_name' => 'new-leads']);
        }
        $facebook_lead_import_exist = Permission::where('name', 'facebook.leads.import')->exists();
        if (!$facebook_lead_import_exist){
            Permission::create([ 'name' => 'facebook.leads.import'  , 'group_name' => 'new-leads']);
        }
        $assign_leads_option_exist = Permission::where('name', 'assign.leads.option')->exists();
        if (!$assign_leads_option_exist){
            Permission::create([ 'name' => 'assign.leads.option'  , 'group_name' => 'new-leads']);
        }


        // Create Permissions For Staff Page
        $staff_view_exist = Permission::where('name', 'staff.view')->exists();
        if (!$staff_view_exist){
            Permission::create([ 'name' => 'staff.view'  , 'group_name' => 'staff']);
        }
        $staff_tree_view_exist = Permission::where('name', 'staff.tree.view')->exists();
        if (!$staff_tree_view_exist){
            Permission::create([ 'name' => 'staff.tree.view'  , 'group_name' => 'staff']);
        }
        $staff_create_exist = Permission::where('name', 'staff.create')->exists();
        if (!$staff_create_exist){
            Permission::create([ 'name' => 'staff.create'  , 'group_name' => 'staff']);
        }
        $staff_edit_exist = Permission::where('name', 'staff.edit')->exists();
        if (!$staff_edit_exist){
            Permission::create([ 'name' => 'staff.edit'  , 'group_name' => 'staff']);
        }
        $staff_trash_exist = Permission::where('name', 'staff.trash')->exists();
        if (!$staff_trash_exist){
            Permission::create([ 'name' => 'staff.trash'  , 'group_name' => 'staff']);
        }


        // Create Permissions For Trashed Staff
        $trashed_staff_view_exist = Permission::where('name', 'trashed.staff.view')->exists();
        if (!$trashed_staff_view_exist){
            Permission::create([ 'name' => 'trashed.staff.view'  , 'group_name' => 'trashed-staff']);
        }
        $trashed_staff_restore_exist = Permission::where('name', 'trashed.staff.restore')->exists();
        if (!$trashed_staff_restore_exist){
            Permission::create([ 'name' => 'trashed.staff.restore'  , 'group_name' => 'trashed-staff']);
        }
        $trashed_staff_delete_exist = Permission::where('name', 'trashed.staff.delete')->exists();
        if (!$trashed_staff_delete_exist){
            Permission::create([ 'name' => 'trashed.staff.delete'  , 'group_name' => 'trashed-staff']);
        }



        // Create Permissions For Targets Page
        $target_view_exist = Permission::where('name', 'target.view')->exists();
        if (!$target_view_exist){
            Permission::create([ 'name' => 'target.view'  , 'group_name' => 'target']);
        }
        $target_create_exist = Permission::where('name', 'target.create')->exists();
        if (!$target_create_exist){
            Permission::create([ 'name' => 'target.create'  , 'group_name' => 'target']);
        }
        $target_repeat_exist = Permission::where('name', 'target.repeat')->exists();
        if (!$target_repeat_exist){
            Permission::create([ 'name' => 'target.repeat'  , 'group_name' => 'target']);
        }
        $target_edit_exist = Permission::where('name', 'target.edit')->exists();
        if (!$target_edit_exist){
            Permission::create([ 'name' => 'target.edit'  , 'group_name' => 'target']);
        }
        $target_delete_exist = Permission::where('name', 'target.delete')->exists();
        if (!$target_delete_exist){
            Permission::create([ 'name' => 'target.delete'  , 'group_name' => 'target']);
        }





        // Create Permissions For Permission
        $permission_view_exist = Permission::where('name', 'permission.view')->exists();
        if (!$permission_view_exist){
            Permission::create([ 'name' => 'permission.view'  , 'group_name' => 'permission']);
        }
        $permission_create_exist = Permission::where('name', 'permission.create')->exists();
        if (!$permission_create_exist){
            Permission::create([ 'name' => 'permission.create'  , 'group_name' => 'permission']);
        }
        $permission_edit_exist = Permission::where('name', 'permission.edit')->exists();
        if (!$permission_edit_exist){
            Permission::create([ 'name' => 'permission.edit'  , 'group_name' => 'permission']);
        }
        $permission_delete_exist = Permission::where('name', 'permission.delete')->exists();
        if (!$permission_delete_exist){
            Permission::create([ 'name' => 'permission.delete'  , 'group_name' => 'permission']);
        }


        // Create Permissions For Role
        $role_view_exist = Permission::where('name', 'role.view')->exists();
        if (!$role_view_exist){
            Permission::create([ 'name' => 'role.view'  , 'group_name' => 'role']);
        }
        $role_create_exist = Permission::where('name', 'role.create')->exists();
        if (!$role_create_exist){
            Permission::create([ 'name' => 'role.create'  , 'group_name' => 'role']);
        }
        $role_edit_exist = Permission::where('name', 'role.edit')->exists();
        if (!$role_edit_exist){
            Permission::create([ 'name' => 'role.edit'  , 'group_name' => 'role']);
        }
        $role_delete_exist = Permission::where('name', 'role.delete')->exists();
        if (!$role_delete_exist){
            Permission::create([ 'name' => 'role.delete'  , 'group_name' => 'role']);
        }


        // Create Permissions For Role In Permission
        $role_in_permission_view_exist = Permission::where('name', 'role-in-permission.view')->exists();
        if (!$role_in_permission_view_exist){
            Permission::create([ 'name' => 'role-in-permission.view'  , 'group_name' => 'role-in-permission']);
        }
        $role_in_permission_create_exist = Permission::where('name', 'role-in-permission.create')->exists();
        if (!$role_in_permission_create_exist){
            Permission::create([ 'name' => 'role-in-permission.create'  , 'group_name' => 'role-in-permission']);
        }
        $role_in_permission_edit_exist = Permission::where('name', 'role-in-permission.edit')->exists();
        if (!$role_in_permission_edit_exist){
            Permission::create([ 'name' => 'role-in-permission.edit'  , 'group_name' => 'role-in-permission']);
        }
        $role_in_permission_delete_exist = Permission::where('name', 'role-in-permission.delete')->exists();
        if (!$role_in_permission_delete_exist){
            Permission::create([ 'name' => 'role-in-permission.delete'  , 'group_name' => 'role-in-permission']);
        }






        $permissions = Permission::all();
        $data = array();

        foreach($permissions as $item)
        {
            // Head Of Sale Permissions
            $hod_permissions_exists = DB::table('role_has_permissions')
                        ->where('role_id', $head_of_sale_role->id)
                        ->where('permission_id', $item->id)
                        ->exists();
            if(!$hod_permissions_exists)
            {
                $data['role_id'] = $head_of_sale_role->id;
                $data['permission_id'] = $item->id;
                DB::table('role_has_permissions')->insert($data);
            }

            // CEO Permissions
            $ceo_permissions_exists = DB::table('role_has_permissions')
                        ->where('role_id', $ceo_role->id)
                        ->where('permission_id', $item->id)
                        ->exists();
            if(!$ceo_permissions_exists)
            {
                $data['role_id'] = $ceo_role->id;
                $data['permission_id'] = $item->id;
                DB::table('role_has_permissions')->insert($data);
            }

            // COO Permissions
            $coo_permissions_exists = DB::table('role_has_permissions')
                        ->where('role_id', $coo_role->id)
                        ->where('permission_id', $item->id)
                        ->exists();
            if(!$coo_permissions_exists)
            {
                $data['role_id'] = $coo_role->id;
                $data['permission_id'] = $item->id;
                DB::table('role_has_permissions')->insert($data);
            }
        }


        $hod_users = User::where('role', 5)->get();
        if(count($hod_users) > 0){
            foreach($hod_users as $hod_user)
            {
                $hod_user->roles()->detach();
                $hod_user->assignRole($head_of_sale_role->id);
            }
        }

        $ceo_users = User::where('role', 13)->get();
        if(count($ceo_users) > 0){
            foreach($ceo_users as $ceo_user){
                $ceo_user->roles()->detach();
                $ceo_user->assignRole($ceo_role->id);
            }
        }

        $coo_users = User::where('role', 14)->get();
        if(count($coo_users) > 0){
            foreach($coo_users as $coo_user){
                $coo_user->roles()->detach();
                $coo_user->assignRole($coo_role->id);
            }
        }


        // Admin User Same Like CEO Permissions
        $admin_users = User::where('role', 0)->get();
        if(count($admin_users) > 0){
            foreach($admin_users as $admin_user)
            {
                $admin_user->roles()->detach();
                $admin_user->assignRole($ceo_role->id);
            }
        }

    }
}
