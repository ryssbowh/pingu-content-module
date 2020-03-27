<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Pingu\Content\ContentEntityType;
use Pingu\Content\Entities\ContentType;
use Pingu\Core\Seeding\DisableForeignKeysTrait;
use Pingu\Core\Seeding\MigratableSeeder;
use Pingu\Field\Entities\BundleField;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;

class S2019_08_06_174312548182_InstallContent extends MigratableSeeder
{
    use DisableForeignKeysTrait;

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        \Settings::repository('content')->create();

        $admin = Role::find(4);
        $viewContent = Permission::findOrCreate(['name' => 'view content','section' => 'Content', 'helper' => 'View all content in back-end']);
        $viewTypes = Permission::findOrCreate(['name' => 'view content types','section' => 'Content', 'helper' => 'View all content types in back end']);
        $admin->givePermissionTo(
            [
            $viewContent,
            $viewTypes,
            Permission::findOrCreate(['name' => 'view content settings','section' => 'Settings']),
            Permission::findOrCreate(['name' => 'edit content settings','section' => 'Settings']),
            Permission::findOrCreate(['name' => 'add content types','section' => 'Content']),
            Permission::findOrCreate(['name' => 'edit content types','section' => 'Content', 'helper' => 'Edit all content types (name and fields)']),
            Permission::findOrCreate(['name' => 'delete content types','section' => 'Content'], 'Delete all content types (and content associated to it)'),
            \Settings::repository('content')->accessPermission(),
            \Settings::repository('content')->editPermission(),
            ]
        );

        $menu = Menu::where(['machineName' => 'admin-menu'])->first();
        $content = MenuItem::create(
            [
            'name' => 'Content',
            'url' => 'content.admin.content',
            'deletable' => 0,
            'active' => 1,
            'permission_id' => $viewContent->id
            ], $menu
        );
        $structure = MenuItem::findByMachineName('admin-menu.structure');
        MenuItem::create(
            [
            'name' => 'Create',
            'url' => 'content.admin.create',
            'active' => 1,
            'deletable' => 0,
            ], $menu, $content
        ); 

        $article = ContentType::create(
            [
            'name' => 'Article', 
            'machineName' => 'article',
            'description' => 'A basic article with only content'
            ]
        );
    }

    /**
     * Reverts the database seeder.
     */
    public function down(): void
    {
        // Remove your data
    }
}
