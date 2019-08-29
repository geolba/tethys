<?php

use Carbon\Carbon;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        // DB::table('users')->delete();

        // User::create([
        // 'name' => str_random(10),
        // 'email' => 'foo@gmail.com',
        // 'password' => bcrypt('secret')
        // ]);
        DB::table('accounts')->insert([
            [
                'login' => "admin",
                'email' => "repository" . '@geologie.ac.at',
                'password' => bcrypt('admin007'),
                'created_at' => Carbon::now(),
            ],
            [
                'login' => "Submitty",
                'email' => "submitter@geologie.ac.at",
                'password' => bcrypt('rdr007'),
                'created_at' => Carbon::now(),
            ],
            [
                'login' => "Eddy",
                'email' => "editor@geologie.ac.at",
                'password' => bcrypt('rdr007'),
                'created_at' => Carbon::now(),
            ],           
            [
                'login' => "Review",
                'email' => "review@geologie.ac.at",
                'password' => bcrypt('rdr007'),
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
