<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;

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
        $roles = Role::all();

        $reset_password = new Permission();
        $reset_password->name         = '/recharge/agent';
        $reset_password->display_name = '/recharge/agent'; // optional
        $reset_password->description  = '代理充值'; // optional
        $reset_password->save();

        foreach ($roles as $role) {
            if (in_array($role->name, ['super', 'admin'])) {
                $role->attachPermission($reset_password);
            }
        }
    }
}
