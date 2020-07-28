<?php

use Carbon\Carbon;
// use Database\DisableForeignKeys;
// use Database\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
      
        DB::table('accounts')->insert([
            [
                'login' => "admin",
                'email' => "admin@localhost",
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ],
            [
                'login' => "Submitty",
                'email' => "submitter@localhost",
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ],
            [
                'login' => "Eddy",
                'email' => "editor@localhost",
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ],
            [
                'login' => "Review",
                'email' => "review@localhost",
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
