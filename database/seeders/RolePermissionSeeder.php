<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        //app()['cache']->forget('spatie.permission.cache');
        // Create Roles
        $roleAdmin = Role::create(['name'=>'Super Admin']);
        // Permission List as array
        $permissions = [

            // Dashjboard Permissions
            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboardadmin',
                ]
            ],

            //Role Permissions
            [
                'group_name' => 'roles',
                'permissions' => [
                    'create.roles',
                    'store.roles',
                    'admin.roles',
                    'edit.roles',
                    'update.roles',
                    'delete.roles',
                ]
            ],

            // Employee Permissions
            [
                'group_name' => 'employees',
                'permissions' => [
                    'create.employee',
                    'store.employee',
                    'employee.index',
                    'edit.employee',
                    'update.employee',
                    'delete.employee',
                ]
            ],
            // Order Permissions
            [
                'group_name' => 'order',
                'permissions' => [
                    'order.index',
                    'create.order',
                    'store.order',
                    'edit.order',
                    'update.order',
                    'delete.order'
                ]
            ],
            // Transaction Permissions
            [
                'group_name' => 'transaction',
                'permissions' => [
                    'transaction.index',
                    'create.transaction',
                    'store.transaction',
                    'edit.transaction',
                    'update.transaction',
                    'delete.transaction'
                ]
            ],
            // Product Permissions
            [
                'group_name' => 'products',
                'permissions' => [
                    'products.create',
                    'products.store',
                    'products.index',
                    'products.edit',
                    'products.update',
                    'products.destroy',
                ]
            ],

        ];


        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                // Create Permission
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup]);
                $roleAdmin->givePermissionTo($permission);
                $permission->assignRole($roleAdmin);
            }
        }
    }
}
