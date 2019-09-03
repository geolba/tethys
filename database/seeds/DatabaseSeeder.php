<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
        $this->command->info('User table seeded!');
    }
}

class CollectionTableSeeder extends Seeder
{
    public function run()
    {
        // DB::table('users')->delete();

        // User::create([
        // 'name' => str_random(10),
        // 'email' => 'foo@gmail.com',
        // 'password' => bcrypt('secret')
        // ]);
        DB::table('collections')->insert([
            [
                'id' => '0',
                'number' => null,
                'name' => 'first collection',
                'parent_id' => null,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => '1',
                'number' => '0',
                'name' => 'Informatik, Informationswissenschaft, allgemeine Werke',
                'parent_id' => '0',
                'created_at' => '2015-06-09 00:17:51',
                'updated_at' => '2015-06-09 01:01:36',
            ],
            [
                'id' => '2',
                'number' => '1',
                'name' => 'Philosophie und Psychologie',
                'parent_id' => '0',
                'created_at' => '2015-06-09 01:07:41',
                'updated_at' => '2015-06-09 01:07:41',
            ],
            [
                'id' => '3',
                'number' => '2',
                'name' => 'Religion',
                'parent_id' => '0',
                'created_at' => '2015-06-09 01:07:50',
                'updated_at' => '2015-06-09 01:07:50',
            ],
            [
                'id' => '4',
                'number' => '3',
                'name' => 'Sozialwissenschaften',
                'parent_id' => '0',
                'created_at' => '2015-06-09 01:07:50',
                'updated_at' => '2015-06-09 01:07:50',
            ],
        ]);
    }
}