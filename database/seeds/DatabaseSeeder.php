<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

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

		$this->call('StudentTableSeeder');
		$this->call('CategoryTableSeeder');
		$this->call('ShelfTableSeeder');
		$this->call('BookTableSeeder');
		$this->call('PeriodeTableSeeder');
		$this->call('UserTableSeeder');
		$this->call('CollectionTableSeeder');
		$this->call('DocumentTableSeeder');
		$this->command->info('User table seeded!');
	}

}

class UserTableSeeder extends Seeder {

    public function run()
    {
        // DB::table('users')->delete();

        // User::create([
		// 'name' => str_random(10),
		// 'email' => 'foo@gmail.com',
		// 'password' => bcrypt('secret')
		// ]);
			DB::table('users')->insert([
			[
            'name' => "user1",
            'email' => "user1".'@gmail.com',
            'password' => bcrypt('secret')
			],
			[
            'name' => "admin",
            'email' => 'arno.kaimbacher@hotmail.de',
            'password' => bcrypt('admin007')
			]
       		]);

    }

}

class PeriodeTableSeeder extends Seeder {

    public function run()
    {
        // DB::table('users')->delete();

        // User::create([
		// 'name' => str_random(10),
		// 'email' => 'foo@gmail.com',
		// 'password' => bcrypt('secret')
		// ]);
			DB::table('periodes')->insert([
            'id' => '1',
            'days' => '100',		
			'created_at' => '2015-06-09 02:59:49',
            'updated_at' => '2015-06-10 08:14:27'
        ]);

    }

}

class CategoryTableSeeder extends Seeder {

    public function run()
    {
        // DB::table('users')->delete();

        // User::create([
		// 'name' => str_random(10),
		// 'email' => 'foo@gmail.com',
		// 'password' => bcrypt('secret')
		// ]);
			DB::table('categories')->insert([
				[
				// 'id' => '1',
				'category' => 'Sains',
				'created_at' => '2015-06-09 00:17:51',			
					'updated_at' => '2015-06-09 01:01:36'
				],
				[
					// 'id' => '2',
					'category' => 'Computer',
					'created_at' => '2015-06-09 01:07:41',			
					'updated_at' => '2015-06-09 01:07:41'
				],
				[
					// 'id' => '3',
					'category' => 'Life Lesson',
					'created_at' => '2015-06-09 01:07:50',			
					'updated_at' => '2015-06-09 01:07:50'
				],
				[
					// 'id' => '4',
					'category' => 'Fairy Tail',
					'created_at' => '2015-06-09 01:07:50',			
					'updated_at' => '2015-06-09 01:07:50'
				]
			]);

    }

}

class BookTableSeeder extends Seeder {

    public function run()
    {
        // DB::table('users')->delete();

        // User::create([
		// 'name' => str_random(10),
		// 'email' => 'foo@gmail.com',
		// 'password' => bcrypt('secret')
		// ]);
			DB::table('books')->insert([
			[
            // 'id' => '1',
			'title' => 'Laravel 5',
			'author' => 'Arno Kaimbacher',
			'year' => '2017',
			'stock' => '9',
			'category_id' => '4',
			'shelf_id' => '1',           
			'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36',
			'year_id' => '0'
			],
			[
				// 'id' => '2',
				'title' => 'Angular.Js',
				'author' => 'Mark Zuckerberg',
				'year' => '2014',
				'stock' => '5',
				'category_id' => '4',
				'shelf_id' => '3',           
				'created_at' => '2015-06-09 00:17:51',			
				'updated_at' => '2015-06-09 01:01:36',
				'year_id' => '0'
			],
			[
				// 'id' => '3',
				'title' => 'OOP with PHP',
				'author' => 'Richard Stallman',
				'year' => '1999',
				'stock' => '7',
				'category_id' => '1',
				'shelf_id' => '2',           
				'created_at' => '2015-06-09 00:17:51',			
				'updated_at' => '2015-06-09 01:01:36',
				'year_id' => '0'
			]
			]);

    }

}

class ShelfTableSeeder extends Seeder {

    public function run()
	{      

        DB::table('shelves')->insert([
			[
			'id' => '1',
			'shelf' => 'A',
			'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
			[
			'id' => '2',
			'shelf' => 'B',
			'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
			[
			'id' => '3',
			'shelf' => 'C',
			'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
			[
			'id' => '4',
			'shelf' => 'D',
			'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
			[
			'id' => '5',
			'shelf' => 'E',
			'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			]
		]);

    }
}

class StudentTableSeeder extends Seeder {

    public function run()
	{      

        DB::table('students')->insert([
			[
			'id' => '1',
			'name' => 'Arno Kaimbacher',
			'registered_at' => '1432080000',
			'borrow' => '1',
			'status' => '1',			
            'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
			[
			'id' => '2',
			'name' => 'Chelsea Islan',
			'registered_at' => '1433948676',
			'borrow' => '1',
			'status' => '1',			
            'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
			[
			'id' => '3',
			'name' => 'John Mayer',
			'registered_at' => '1434734048',
			'borrow' => '0',
			'status' => '1',			
            'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
			[
			'id' => '4',
			'name' => 'Emma Watson',
			'registered_at' => '1434734067',
			'borrow' => '1',
			'status' => '1',			
            'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
				[
			'id' => '5',
			'name' => 'Scarlet Johansson',
			'registered_at' => '1434734082',
			'borrow' => '0',
			'status' => '1',			
            'created_at' => '2015-06-09 00:17:51',			
            'updated_at' => '2015-06-09 01:01:36'
			],
		]);

    }
}

class CollectionTableSeeder extends Seeder {

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
					'created_at' =>  new DateTime(),			
					'updated_at' => new DateTime()
				],
				[
					'id' => '1',
					'number' => '0',
					'name' => 'Informatik, Informationswissenschaft, allgemeine Werke',
					'parent_id' => '0',
					'created_at' => '2015-06-09 00:17:51',			
					'updated_at' => '2015-06-09 01:01:36'
				],
				[
					'id' => '2',
					'number' => '1',
					'name' => 'Philosophie und Psychologie',
					'parent_id' => '0',
					'created_at' => '2015-06-09 01:07:41',			
					'updated_at' => '2015-06-09 01:07:41'
				],
				[
					'id' => '3',
					'number' => '2',
					'name' => 'Religion',
					'parent_id' => '0',
					'created_at' => '2015-06-09 01:07:50',			
					'updated_at' => '2015-06-09 01:07:50'
				],
				[
					'id' => '4',
					'number' => '3',
					'name' => 'Sozialwissenschaften',
					'parent_id' => '0',
					'created_at' => '2015-06-09 01:07:50',			
					'updated_at' => '2015-06-09 01:07:50'
				]
			]);

    }

}

class DocumentTableSeeder extends Seeder {

    public function run()
    {
			DB::table('documents')->insert([
				[
					'id' => '0'				
				],
				[
					'id' => '1'					
				]
			]);

			DB::table('link_documents_collections')->insert([
				[
					'document_id' => '0',
					'collection_id' => '1'				
				],
				[
					'document_id' => '1',
					'collection_id' => '1'					
				]
			]);


    }

}
