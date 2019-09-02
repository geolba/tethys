<?php

use Carbon\Carbon;
use Database\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'administrator',
                'display_name' => 'admin',
                'description' => 'User has access to all system functionality',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'submitter',
                'display_name' => 'submit',
                'description' => 'submitting datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'editor',
                'display_name' => 'edit',
                'description' => 'Editor checks metadata',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'reviewer',
                'display_name' => 'review',
                'description' => 'reviewer checks dataset',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
           
        ]);

        DB::table('link_accounts_roles')->insert([
            [
                'account_id' => '1', //admin
                'role_id' => '1', //administrator role
            ],
            [
                'account_id' => '2', //Submitty
                'role_id' => '2', //submitter role
            ],
            [
                'account_id' => '3', //Eddy
                'role_id' => '3', //editor role
            ],
            [
                'account_id' => '3', //Review
                'role_id' => '4', //reviewer role
            ],
        ]);

        DB::table('permissions')->insert([
            [
                // 1
                'name' => 'settings',
                'display_name' => 'Manage Settings',
                'description' => 'allow role to manage system settings',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 2
                'name' => 'page',
                'display_name' => 'cms pages',
                'description' => 'allow role to add and edit cms pages like about site',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 3
                'name' => 'dataset-list',
                'display_name' => 'list submitter datasets',
                'description' => 'allow submitter role to list all datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 4
                'name' => 'dataset-submit',
                'display_name' => 'submit datasets',
                'description' => 'allow submitter role to create/submit datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 5
                'name' => 'dataset-editor-list',
                'display_name' => 'list released, editor_accepted and  rejected_reviewer datasets',
                'description' => 'allow editor role to see all released. editor_accepted and  rejected_reviewer datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 6
                'name' => 'dataset-receive',
                'display_name' => 'receive released datasets',
                'description' => 'allow editor to accept/receive released datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 7
                'name' => 'dataset-editor-update',
                'display_name' => 'update received/accepted datasets',
                'description' => 'allow editor to update  received/accepted datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 8
                'name' => 'dataset-approve',
                'display_name' => 'approve datasets',
                'description' => 'allow editor role to approve datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 9
                'name' => 'dataset-publish',
                'display_name' => 'publish datasets',
                'description' => 'allow editor role to publish datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 10
                'name' => 'dataset-editor-reject',
                'display_name' => 'reject datasets',
                'description' => 'allow editor role to reject datasets to submitter',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 11
                'name' => 'dataset-review-list',
                'display_name' => 'list approved datasets',
                'description' => 'allow review role to see all approved datasets from reviewer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 12
                'name' => 'dataset-review',
                'display_name' => 'review datasets',
                'description' => 'allow reviewer role to review datasets',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 13
                'name' => 'dataset-review-reject',
                'display_name' => 'reject datasets',
                'description' => 'allow reviewer role to reject datasets to editor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::table('role_has_permissions')->insert([
            [
                'permission_id' => '1', //permission 'settings'
                'role_id' => '1', //administrator role
            ],
            [
                'permission_id' => '2', //permission 'page'
                'role_id' => '1', //administrator role
            ],
            [
                'permission_id' => '3', //permission 'dataset-list'
                'role_id' => '1', //administrator role
            ],
            [
                'permission_id' => '4', //permission 'dataset-submit'
                'role_id' => '1', //administrator role
            ],
            [
                'permission_id' => '3', //permission 'dataset-list'
                'role_id' => '2', //submitter role
            ],
            [
                'permission_id' => '4', //permission 'dataset-submit'
                'role_id' => '2', //submitter role
            ],
            [
                'permission_id' => '5', //permission 'dataset-editor-list'
                'role_id' => '3', //editor role
            ],
            [
                'permission_id' => '6', //permission 'dataset-receive'
                'role_id' => '3', //editor role
            ],
            [
                'permission_id' => '7', //permission 'dataset-editor-update'
                'role_id' => '3', //editor role
            ],
            [
                'permission_id' => '8', //permission 'dataset-approve'
                'role_id' => '3', //editor role
            ],
            [
                'permission_id' => '9', //permission 'dataset-publish'
                'role_id' => '3', //editor role
            ],
            [
                'permission_id' => '10', //permission 'dataset-editor-reject'
                'role_id' => '3', //editor role
            ],
            [
                'permission_id' => '11', //permission 'dataset-review-list'
                'role_id' => '4', //reviewer role
            ],
            [
                'permission_id' => '12', //permission 'dataset-review'
                'role_id' => '4', //reviewer role
            ],
            [
                'permission_id' => '13', //permission 'dataset-review-reject'
                'role_id' => '4', //reviewer role
            ],
        ]);
    }
}
