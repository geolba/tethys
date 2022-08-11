<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
// use Database\DisableForeignKeys;
// use Database\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
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
