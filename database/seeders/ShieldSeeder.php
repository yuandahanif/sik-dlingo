<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_asuransi","view_any_asuransi","create_asuransi","update_asuransi","restore_asuransi","restore_any_asuransi","replicate_asuransi","reorder_asuransi","delete_asuransi","delete_any_asuransi","force_delete_asuransi","force_delete_any_asuransi","view_bantuan::keluarga","view_any_bantuan::keluarga","create_bantuan::keluarga","update_bantuan::keluarga","restore_bantuan::keluarga","restore_any_bantuan::keluarga","replicate_bantuan::keluarga","reorder_bantuan::keluarga","delete_bantuan::keluarga","delete_any_bantuan::keluarga","force_delete_bantuan::keluarga","force_delete_any_bantuan::keluarga","view_bantuan::penduduk","view_any_bantuan::penduduk","create_bantuan::penduduk","update_bantuan::penduduk","restore_bantuan::penduduk","restore_any_bantuan::penduduk","replicate_bantuan::penduduk","reorder_bantuan::penduduk","delete_bantuan::penduduk","delete_any_bantuan::penduduk","force_delete_bantuan::penduduk","force_delete_any_bantuan::penduduk","view_dusun","view_any_dusun","create_dusun","update_dusun","restore_dusun","restore_any_dusun","replicate_dusun","reorder_dusun","delete_dusun","delete_any_dusun","force_delete_dusun","force_delete_any_dusun","view_kartu::keluarga","view_any_kartu::keluarga","create_kartu::keluarga","update_kartu::keluarga","restore_kartu::keluarga","restore_any_kartu::keluarga","replicate_kartu::keluarga","reorder_kartu::keluarga","delete_kartu::keluarga","delete_any_kartu::keluarga","force_delete_kartu::keluarga","force_delete_any_kartu::keluarga","view_kategori::asuransi","view_any_kategori::asuransi","create_kategori::asuransi","update_kategori::asuransi","restore_kategori::asuransi","restore_any_kategori::asuransi","replicate_kategori::asuransi","reorder_kategori::asuransi","delete_kategori::asuransi","delete_any_kategori::asuransi","force_delete_kategori::asuransi","force_delete_any_kategori::asuransi","view_kategori::bantuan","view_any_kategori::bantuan","create_kategori::bantuan","update_kategori::bantuan","restore_kategori::bantuan","restore_any_kategori::bantuan","replicate_kategori::bantuan","reorder_kategori::bantuan","delete_kategori::bantuan","delete_any_kategori::bantuan","force_delete_kategori::bantuan","force_delete_any_kategori::bantuan","view_penduduk","view_any_penduduk","create_penduduk","update_penduduk","restore_penduduk","restore_any_penduduk","replicate_penduduk","reorder_penduduk","delete_penduduk","delete_any_penduduk","force_delete_penduduk","force_delete_any_penduduk","view_pertanahan","view_any_pertanahan","create_pertanahan","update_pertanahan","restore_pertanahan","restore_any_pertanahan","replicate_pertanahan","reorder_pertanahan","delete_pertanahan","delete_any_pertanahan","force_delete_pertanahan","force_delete_any_pertanahan","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_rt","view_any_rt","create_rt","update_rt","restore_rt","restore_any_rt","replicate_rt","reorder_rt","delete_rt","delete_any_rt","force_delete_rt","force_delete_any_rt","widget_ChartFilter","widget_DateOfBirthChart","widget_PersebaranPenduduk"]},{"name":"panel_user","guard_name":"web","permissions":[]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
