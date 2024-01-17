<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->delete();
        DB::table('roles')->delete();

        $roleAdmin = Role::create(['name' => 'Administrador']);
        $roleManager = Role::create(['name' => 'Gerente']);
        Role::create(['name' => 'Entregador']);

        // Usuários
        $permissionsUsers = ['Cadastrar usuário', 'Ativar/Desativar usuário', 'Editar usuário', 'Listar usuários', 'Atribuir/Desatribuir usuário'];
        foreach($permissionsUsers as $permissionUser) {
            $permission = Permission::create(['name' =>  $permissionUser, 'group' => 'Usuários']);
            $roleAdmin->givePermissionTo($permission);
        }

        // Entregadores
        $permissionsDelivery = ['Listar entregadores'];
        foreach($permissionsDelivery as $permissionDelivery) {
            $permission = Permission::create(['name' =>  $permissionDelivery, 'group' => 'Entregadores']);
            $roleAdmin->givePermissionTo($permission);
            $roleManager->givePermissionTo($permission);
        }

        // Clientes
        $permissionsCustomer = ['Cadastrar cliente', 'Listar clientes', 'Editar cliente'];
        foreach($permissionsCustomer as $permissionCustomer) {
            $permission = Permission::create(['name' =>  $permissionCustomer, 'group' => 'Clientes']);
            $roleAdmin->givePermissionTo($permission);
            $roleManager->givePermissionTo($permission);
        }

        // Permissões
        $selfPermissions = ['Listar permissões'];
        foreach($selfPermissions as $selfPermission) {
            $permission = Permission::create(['name' =>  $selfPermission, 'group' => 'Permissões']);
            $roleAdmin->givePermissionTo($permission);
        }
    }
}
