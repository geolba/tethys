<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();

        // DB::table('users')->insert([
        //     'name' => str_random(10),
        //     'email' => str_random(10).'@gmail.com',
        //     'password' => bcrypt('secret'),
        // ]);

   
        // $this->call('CategoryTableSeeder');
        // $this->call('BookTableSeeder');
        $this->call('MimetypeTableSeeder');
        $this->call('AccountsTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('LicencesTableSeeder');
        $this->call('LanguagesTableSeeder');
        $this->call('PagesTableSeeder');
        $this->call('CollectionsTableSeeder');
        $this->call('ProjectsTableSeeder');
        $this->call('MessagesTableSeeder');        
    }
}
