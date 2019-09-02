<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CollectionsTableSeeder extends Seeder
{
    public function run()
    {
       
        DB::table('collections_roles')->insert([
            [
                'name' => 'bk',
                'oai_name' => 'bk',
                'position' => 1,
                'visible' => true,
                'visible_frontdoor' => true,
                'visible_oai' => true,
            ],
            [
                'name' => 'ccs',
                'oai_name' => 'ccs',
                'position' => 2,
                'visible' => true,
                'visible_frontdoor' => true,
                'visible_oai' => true,
            ],
            [
                'name' => 'ddc',
                'oai_name' => 'ddc',
                'position' => 3,
                'visible' => true,
                'visible_frontdoor' => true,
                'visible_oai' => true,
            ],
            [
                'name' => 'institutes',
                'oai_name' => 'institutes',
                'position' => 4,
                'visible' => true,
                'visible_frontdoor' => true,
                'visible_oai' => true,
            ],
            [
                'name' => 'jel',
                'oai_name' => 'jel',
                'position' => 5,
                'visible' => true,
                'visible_frontdoor' => true,
                'visible_oai' => true,
            ],
            [
                'name' => 'msc',
                'oai_name' => 'msc',
                'position' => 6,
                'visible' => true,
                'visible_frontdoor' => true,
                'visible_oai' => true,
            ],
            [
                'name' => 'pacs',
                'oai_name' => 'pacs',
                'position' => 7,
                'visible' => true,
                'visible_frontdoor' => true,
                'visible_oai' => true,
            ],
        ]);

        DB::table('collections')->insert([
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Informatik, Informationswissenschaft, allgemeine Werke',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Philosophie und Psychologie',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Religion',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Sozialwissenschaften',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Sprache',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Naturwissenschaften und Mathematik',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Technik, Medizin, angewandte Wissenschaften',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Künste und Unterhaltung',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Literatur',
            ],
            [
                'role_id' => 2,
                'parent_id' => null,
                'name' => 'Geschichte und Geografie',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Informatik, Wissen, Systeme',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Bibliografien',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Bibliotheks- und Informationswissenschaften',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Enzyklopädien, Faktenbücher',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Zeitschriften, fortlaufende Sammelwerke',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Verbände, Organisationen, Museen',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Publizistische Medien, Journalismus, Verlagswesen',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Allgemeine Sammelwerke, Zitatensammlungen',
            ],
            [
                'role_id' => 2,
                'parent_id' => 3,
                'name' => 'Handschriften, seltene Bücher',
            ],
        ]);
    }
}
