<?php

namespace Osi\AuthApi\Database\Seeder;

use Illuminate\Database\Seeder;
use SmallRuralDog\Admin\Auth\Database\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Menu::where('uri', 'api/logs')->count()) {
            $now = date('Y-m-d H:i:s');
            Menu::insert([
                [
                    'parent_id' => 2,
                    'order' => 0,
                    'title' => '接口日志',
                    'icon' => 'el-icon-document-copy',
                    'uri' => 'api/logs',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'parent_id' => 2,
                    'order' => 0,
                    'title' => '接口权限',
                    'icon' => 'el-icon-cpu',
                    'uri' => 'api/permissions',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}
