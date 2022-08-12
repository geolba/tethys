<?php

namespace Database\Seeders;

// use Illuminate\Support\Carbon;
// use Database\DisableForeignKeys;
// use Database\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    public function run()
    {
        // DB::table('users')->delete();

        // User::create([
        // 'name' => str_random(10),
        // 'email' => 'foo@gmail.com',
        // 'password' => bcrypt('secret')
        // ]);
        DB::table('languages')->insert([
            [
                'part2_b' => 'ger',
                'part2_t' => 'deu',
                'part1' => 'de',
                'scope' => 'I',
                'type' => 'L',
                'ref_name' => 'German',
                'active' =>true,
            ],
            [
                'part2_b' => 'eng',
                'part2_t' => 'eng',
                'part1' => 'en',
                'scope' => 'I',
                'type' => 'L',
                'ref_name' => 'English',
                'active' =>true,
            ],
            [
                'part2_b' => 'ita',
                'part2_t' => 'ita',
                'part1' => 'it',
                'scope' => 'I',
                'type' => 'L',
                'ref_name' => 'Italian',
                'active' =>false,
            ],
            [
                'part2_b' => 'fre',
                'part2_t' => 'fra',
                'part1' => 'fr',
                'scope' => 'I',
                'type' => 'L',
                'ref_name' => 'French',
                'active' =>false,
            ],
            [
                'part2_b' => 'rus',
                'part2_t' => 'rus',
                'part1' => 'ru',
                'scope' => 'I',
                'type' => 'L',
                'ref_name' => 'Russian',
                'active' =>false,
            ],
            [
                'part2_b' => 'spa',
                'part2_t' => 'spa',
                'part1' => 'es',
                'scope' => 'I',
                'type' => 'L',
                'ref_name' => 'Spanish',
                'active' =>false,
            ],
        ]);
    }
}
