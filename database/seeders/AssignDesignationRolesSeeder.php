<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * php artisan db:seed --class=AssignDesignationRolesSeeder
 *
 * For every user whose legacy `role` (designation id) column maps to a
 * known designation, assigns the matching Spatie role by name.
 *
 * This is additive only: assignRole() attaches roles the user doesn't
 * already have and leaves every existing role assignment untouched, so a
 * user who already holds a different role (e.g. was manually promoted to
 * Manager) keeps that role and simply gains the designation-matching one
 * too. Safe to re-run — already-held roles are skipped.
 *
 * Requires RolesDataPermissionsSeeder to have run first (it creates the
 * roles referenced below).
 */
class AssignDesignationRolesSeeder extends Seeder
{
    /**
     * Designation name (from the `designation` table) => Spatie role name.
     * Kept in sync with RolesDataPermissionsSeeder::$designationRoleNames —
     * a few designation names don't exactly match the existing role name
     * (e.g. "Team Leads" designation vs "Team Lead" role), so this maps
     * explicitly rather than assuming the names are identical.
     */
    protected array $designationToRole = [
        'Manager' => 'Manager',
        'Team Leads' => 'Team Lead',
        'BDO' => 'BDO',
        'Affiliator' => 'Affiliator',
        'Head of Sales' => 'Head-of-Sale',
        'Recovery Office' => 'Recovery Office',
        'Accountant' => 'Accountant',
        'HR' => 'HR',
        'Out Sider' => 'Out Sider',
        'Digital Marketing' => 'Digital Marketing',
        'Dealor' => 'Dealor',
        'Freelancer' => 'Freelancer',
        'CEO' => 'CEO',
        'COO' => 'COO',
        'Can-See-All-Data' => 'Can-See-All-Data'
    ];

    public function run()
    {
        $assigned = 0;
        $alreadyHad = 0;
        $skippedNoDesignation = 0;

        User::whereNotNull('role')->with('designation')->chunkById(50, function ($users) use (&$assigned, &$alreadyHad, &$skippedNoDesignation) {
            foreach ($users as $user) {
                $designationName = optional($user->designation)->name;

                if (!$designationName || !isset($this->designationToRole[$designationName])) {
                    $skippedNoDesignation++;
                    continue;
                }

                $roleName = $this->designationToRole[$designationName];

                if ($user->hasRole($roleName)) {
                    $alreadyHad++;
                    continue;
                }

                $user->assignRole($roleName);
                $assigned++;
            }
        });

        $message = "AssignDesignationRolesSeeder: assigned {$assigned} new role(s), {$alreadyHad} already had their designation role, {$skippedNoDesignation} skipped (no matching designation, e.g. Admin/role=0).";

        if ($this->command) {
            $this->command->info($message);
        } else {
            echo $message . PHP_EOL;
        }
    }
}
