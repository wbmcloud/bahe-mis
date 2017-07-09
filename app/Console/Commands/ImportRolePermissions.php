<?php

namespace App\Console\Commands;

use App\Common\ParamsRules;
use App\Common\Utils;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportRolePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:role_permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入角色权限数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createPermissions();
        $this->attachPermissions();
    }

    protected function createPermissions()
    {
        $interface_permissions = $this->analyzePermissions();
        $roles = array_keys($interface_permissions);
        $permissions = [];
        foreach ($roles as $role) {
            $permissions = array_merge($permissions, $interface_permissions[$role]);
        }
        $permissions = Utils::arrayUnique($permissions);
        DB::table('permissions')->insert($permissions);
    }

    protected function analyzePermissions()
    {
        $interface_permissions = [];
        $now = Carbon::now()->toDateTimeString();
        foreach (ParamsRules::$interface_permission as $key => $value) {
            $roles = $value['auth'];
            if (is_string($roles) && $roles == '*') {
                $roles = Role::get()->toArray();
                $roles = array_column($roles, 'name');

            }

            foreach ($roles as $role) {
                $interface_permission['name'] = $key;
                $interface_permission['display_name'] = $key;
                $interface_permission['description'] = $value['desc'];
                $interface_permission['created_at'] = $now;
                $interface_permission['updated_at'] = $now;
                $interface_permissions[$role][] = $interface_permission;
            }
        }

        return $interface_permissions;
    }

    protected function attachPermissions()
    {
        $interface_permissions = $this->analyzePermissions();

        foreach ($interface_permissions as $role_name => $interface_permission) {
            $role = Role::where('name', $role_name)->first();
            foreach ($interface_permission as $permission) {
                $permission = Permission::where('name', $permission['name'])->first();
                $role->attachPermission($permission);
            }
        }
    }

}
