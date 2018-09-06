<?php

use Carbon\Carbon;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PagesTableSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $title = $faker->sentence;

        $this->disableForeignKeys();
        // $this->truncate('pages');
        $page = [
            [
                'title'       => 'Terms and conditions',
                'page_slug'   => 'terms-and-conditions',
                'description' => $faker->text($maxNbChars = 255),
                'status'      => '1',
                'created_by'  => '1',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];
        DB::table('pages')->insert($page);

        $this->enableForeignKeys();
    }
}
