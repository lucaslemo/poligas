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

        // Produtos
        $permissionsProducts = ['Cadastrar produto', 'Listar produtos', 'Editar produto'];
        foreach($permissionsProducts as $permissionsProduct) {
            $permission = Permission::create(['name' =>  $permissionsProduct, 'group' => 'Produtos']);
            $roleAdmin->givePermissionTo($permission);
            $roleManager->givePermissionTo($permission);
        }

        // Brands
        $permissionsBrands = ['Cadastrar marca', 'Listar marcas', 'Editar marca'];
        foreach($permissionsBrands as $permissionsBrand) {
            $permission = Permission::create(['name' =>  $permissionsBrand, 'group' => 'Marcas']);
            $roleAdmin->givePermissionTo($permission);
            $roleManager->givePermissionTo($permission);
        }

        // Fornecedores
        $permissionsVendors = ['Cadastrar fornecedor', 'Listar fornecedores', 'Editar fornecedor'];
        foreach($permissionsVendors as $permissionsVendor) {
            $permission = Permission::create(['name' =>  $permissionsVendor, 'group' => 'Fornecedores']);
            $roleAdmin->givePermissionTo($permission);
            $roleManager->givePermissionTo($permission);
        }

        // Endereços
        $permissionsAddresses = ['Cadastrar endereço', 'Listar endereços', 'Editar endereço', 'Excluir endereço'];
        foreach($permissionsAddresses as $permissionsAddress) {
            $permission = Permission::create(['name' =>  $permissionsAddress, 'group' => 'Endereços']);
            $roleAdmin->givePermissionTo($permission);
            $roleManager->givePermissionTo($permission);
        }

        // Estoques
        $permissionsStocks = ['Cadastrar estoque', 'Listar estoques', 'Exibir estoque', 'Excluir estoque'];
        foreach($permissionsStocks as $permissionsStock) {
            $permission = Permission::create(['name' =>  $permissionsStock, 'group' => 'Estoques']);
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
