<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MimetypeTableSeeder extends Seeder
{
    public function run()
    {
       
        DB::table('mime_types')->insert([
            [
                'name' => 'application/x-sqlite3',
                'file_extension' => 'gpkg',
                'enabled' =>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'image/jpeg',
                'file_extension' => 'jpg|jpeg|jpe',
                'enabled' =>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'image/png',
                'file_extension' => 'png',
                'enabled' =>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'application/pdf',
                'file_extension' => 'pdf',
                'enabled' =>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'text/html',
                'file_extension' => 'htm|html',
                'enabled' =>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'text/csv',
                'file_extension' => 'csv',
                'enabled' =>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'text/plain',
                'file_extension' => 'txt|asc|c|cc|h|srt',
                'enabled' =>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'file_extension' => 'xlsx',
                'enabled' =>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
