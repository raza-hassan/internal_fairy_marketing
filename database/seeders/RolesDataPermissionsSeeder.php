<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * php artisan db:seed --class=RolesDataPermissionsSeeder
 *
 * Safe to re-run: only ever creates missing roles/permissions and adds
 * missing permission grants. Never deletes/detaches a role or permission,
 * never touches model_has_roles (no user's assigned role is changed).
 */
class RolesDataPermissionsSeeder extends Seeder
{
    /**
     * Every name in the `designation` table must end up as a Spatie role
     * with the same name. Where a role already exists under a slightly
     * different spelling (from the legacy seeder), we reuse that name
     * instead of creating a duplicate.
     */
    protected array $designationRoleNames = [
        'Manager',
        'Team Lead', // designation "Team Leads"
        'BDO',
        'Affiliator',
        'Head-of-Sale', // designation "Head of Sales"
        'Recovery Office',
        'Accountant',
        'HR',
        'Out Sider',
        'Digital Marketing',
        'Dealor',
        'Freelancer',
        'CEO',
        'COO',
    ];

    /** Roles that default to seeing ALL data (everyone else defaults to own + subordinates). */
    protected array $allDataRoleNames = ['CEO', 'COO'];

    /** Modules that get a "See Own Data" / "See All Data" permission pair. */
    protected array $dataModules = ['lead', 'client', 'affiliator', 'staff', 'target'];

    public function run()
    {
        $this->createMissingRoles();
        $permissions = $this->createDataPermissions();
        $this->assignDefaultDataPermissions($permissions);
    }

    protected function createMissingRoles(): void
    {
        foreach ($this->designationRoleNames as $roleName) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create(['name' => $roleName, 'guard_name' => 'web']);
            }
        }
    }

    protected function createDataPermissions()
    {
        $permissions = collect();

        foreach ($this->dataModules as $module) {
            foreach (['own', 'all'] as $scope) {
                $name = "{$module}.data.{$scope}";
                $permission = Permission::where('name', $name)->first();
                if (!$permission) {
                    $permission = Permission::create([
                        'name' => $name,
                        'group_name' => $module,
                        'guard_name' => 'web',
                    ]);
                }
                $permissions->push($permission);
            }
        }

        return $permissions;
    }

    protected function assignDefaultDataPermissions($permissions): void
    {
        $ownPermissions = $permissions->filter(fn ($p) => str_ends_with($p->name, '.data.own'));
        $allPermissions = $permissions->filter(fn ($p) => str_ends_with($p->name, '.data.all'));

        foreach (Role::all() as $role) {
            $toGrant = in_array($role->name, $this->allDataRoleNames, true) ? $allPermissions : $ownPermissions;

            foreach ($toGrant as $permission) {
                if (!$role->hasPermissionTo($permission->name)) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
