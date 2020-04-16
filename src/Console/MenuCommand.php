<?php
namespace Osi\AuthApi\Console;

use Illuminate\Console\Command;

class MenuCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'authapi:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'seeder the menu';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->migrateAndSeed();
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function migrateAndSeed()
    {
        $this->call('migrate');
        if (class_exists(\SmallRuralDog\Admin\Auth\Database\Menu::class)) {
            $this->call('db:seed', ['--class' => \Osi\AuthApi\Database\Seeder\MenuSeeder::class]);
        } else {
            $this->warn('手动增加菜单');
            $this->line('insert into `admin_menu` (`title`,`uri`)values ("接口日志","api/logs"),("接口权限","api/permissions");');
        }
    }
}
