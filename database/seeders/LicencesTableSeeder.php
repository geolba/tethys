<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LicencesTableSeeder extends Seeder
{
    public function run()
    {
        // DB::table('users')->delete();

        // User::create([
        // 'name' => str_random(10),
        // 'email' => 'foo@gmail.com',
        // 'password' => bcrypt('secret')
        // ]);
        DB::table('document_licences')->insert([
            [
                'active' => true,
                'comment_internal' => "Attribution — You must give appropriate credit, provide a link to the license, 
                and indicate if changes were made. You may do so in any reasonable manner, 
                but not in any way that suggests the licensor endorses you or your use..",
                'desc_markup' => '<p><strong>Dieser Inhalt </strong>is unter einer <a href="\">
                Creative Commons License</a> lizenziert.</p>',
                'desc_text' => "You are free to:
                Share — copy and redistribute the material in any medium or  format
                Adapt — remix, transform, and build upon the material
                for any purpose, even commercially.
                This license is acceptable for Free Cultural Works.            
                The licensor cannot revoke these freedoms as long as you follow the license terms.",
                'language' => 'en',
                'link_licence' => 'https://creativecommons.org/licenses/by/3.0/de/deed.de',
                'link_logo' => 'https://licensebuttons.net/l/by/4.0/88x31.png',
                'mime_type' => 'text/html',
                'name_long' => 'Creative Commons Attribution 4.0 International (CC BY 4.0)',
                'name' => 'CC-BY-4.0',
                'pod_allowed' => true,
                'sort_order' => 1,
            ],

            [
                'active' => true,
                'comment_internal' => "Attribution — You must give appropriate credit, provide a link to the license, 
                and indicate if changes were made. You may do so in any reasonable manner, 
                but not in any way that suggests the licensor endorses you or your use.
                ShareAlike — If you remix, transform, or build upon the material, 
                you must distribute your contributions under the same license as the original.",
                'desc_markup' => '<p>Dieser Inhalt ist unter einer <a href="\">
                Creative Commons-Lizenz</a> lizenziert.</p>',
                'desc_text' => "Diese Lizenz erlaubt es anderen, Ihr Werk/Ihren Inhalt zu verbreiten, zu remixen, 
                zu verbessern und darauf aufzubauen, auch kommerziell, 
                solange Sie als Urheber des Originals genannt werden und die auf Ihrem Werk/Inhalt 
                basierenden neuen Werke unter denselben Bedingungen veröffentlicht werden. 
                Diese Lizenz wird oft mit \"Copyleft\"-Lizenzen im Bereich freier und Open Source Software verglichen. 
                Alle neuen Werke/Inhalte, die auf Ihrem aufbauen, werden unter derselben Lizenz stehen, 
                also auch kommerziell nutzbar sein. 
                Dies ist die Lizenz, die auch von der Wikipedia eingesetzt wird, empfohlen für Material, 
                für das eine Einbindung von Wikipedia-Material 
                oder anderen so lizenzierten Inhalten sinnvoll sein kann.",
                'language' => 'en',
                'link_licence' => 'https://creativecommons.org/licenses/by-sa/4.0/deed.en',
                'link_logo' => 'https://licensebuttons.net/l/by-sa/4.0/88x31.png',
                'mime_type' => 'text/html',
                'name_long' => 'Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)',
                'name' => 'CC-BY-SA-4.0',
                'pod_allowed' => true,
                'sort_order' => 2,
            ],
            [
                'active' => true,
                'comment_internal' => null,
                'desc_markup' => '<p>Dieser Inhalt ist unter einer 
                <a href="\">Creative Commons-Lizenz</a> lizenziert.</p>',
                'desc_text' => "Diese Lizenz erlaubt es anderen, Ihr Werk/Ihren Inhalt zu verbreiten, 
                zu remixen, zu verbessern und darauf aufzubauen, allerdings nur nicht-kommerziell 
                und solange Sie als Urheber des Originals genannt werden und die auf Ihrem Werk/Inhalt 
                basierenden neuen Werke unter denselben Bedingungen veröffentlicht werden.",
                'language' => 'de',
                'link_licence' => 'https://creativecommons.org/licenses/by-nc-sa/3.0/de',
                'link_logo' => 'https://i.creativecommons.org/l/by-nc-sa/3.0/de/88x31.png',
                'mime_type' => 'text/html',
                'name_long' => 'Creative Commons - Namensnennung - Keine kommerzielle Nutzung - 
                Weitergabe unter gleichen Bedingungen (CC BY-NC-SA)',
                'name' => 'CC BY-NC-SA',
                'pod_allowed' => true,
                'sort_order' => 3,
            ],
            [
                'active' => true,
                'comment_internal' => null,
                'desc_markup' => '<h2>Dieser <i>Inhalt </i>ist unter einer 
                <a href="https://creativecommons.org/licenses/by-nc/3.0/de/deed.de">
                <strong>Creative Commons-Lizenz</strong></a> 
                lizenziert.</h2><h3>Creative Commons - Namensnennung - Nicht kommerziell</h3>',
                'desc_text' => "Diese Lizenz erlaubt es anderen, Ihr Werk/Ihren Inhalt zu verbreiten, 
                zu remixen, zu verbessern und darauf aufzubauen, allerdings nur nicht-kommerziell. 
                Und obwohl auch bei den auf Ihrem Werk/Inhalt basierenden neuen Werken 
                Ihr Name mit genannt werden muss und sie nur nicht-kommerziell verwendet werden dürfen, 
                müssen diese neuen Werke nicht unter denselben Bedingungen lizenziert werden.",
                'language' => 'de',
                'link_licence' => 'https://creativecommons.org/licenses/by-nc/3.0/de/deed.de',
                'link_logo' => 'https://i.creativecommons.org/l/by-nc/3.0/de/88x31.png',
                'mime_type' => 'text/html',
                'name_long' => 'Creative Commons - Namensnennung - Nicht kommerziell (CC BY-NC)',
                'name' => 'CC BY-NC',
                'pod_allowed' => true,
                'sort_order' => 4,
            ],
            [
                'active' => true,
                'comment_internal' => "Wie cc_by_nc_nd, aber kommerzielle Nutzung erlaubt.",
                'desc_markup' => 'Dieser Inhalt ist unter einer 
                <a rel="\"license\"" href="\"https://creativecommons.org/licenses/by-nd/3.0/de/\"">
                Creative Commons-Lizenz</a> lizenziert.<!--/Creative Commons License-->
                <!-- <rdf:RDF xmlns=\"http://web.resource.org/cc/\" 
                xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" 
                xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\">\r\n	<Work rdf:about=\"\">\r\n		
                <license rdf:resource=\"https://creativecommons.org/licenses/by-nd/3.0/de/\" ></license>\r\n	
                </Work>\r\n	<License rdf:about=\"https://creativecommons.org/licenses/by-nd/3.0/de/\">
                <permits rdf:resource=\"http://web.resource.org/cc/Reproduction\"></permits>
                <permits rdf:resource=\"http://web.resource.org/cc/Distribution\"></permits>
                <requires rdf:resource=\"http://web.resource.org/cc/Notice\"></requires>
                <requires rdf:resource=\"http://web.resource.org/cc/Attribution\"></requires>
                </License></rdf:RDF> -->',
                'desc_text' => "Diese Lizenz erlaubt anderen die Weiterverbreitung Ihres Werkes/Inhaltes, 
                kommerziell wie nicht-kommerziell, solange dies ohne Veränderungen 
                und vollständig geschieht und Sie als Urheber genannt werden.",
                'language' => 'de',
                'link_licence' => 'https://creativecommons.org/licenses/by-nd/3.0/de/',
                'link_logo' => 'https://i.creativecommons.org/l/by-nd/3.0/de/88x31.png',
                'mime_type' => 'text/html',
                'name_long' => 'Creative Commons - Namensnennung - Keine Bearbeitung (CC BY-ND)',
                'name' => 'CC BY-ND',
                'pod_allowed' => true,
                'sort_order' => 5,
            ],
            [
                'active' => true,
                'comment_internal' => "Namensnennung-NichtKommerziell-KeineBearbeitung\r\n\r\n
                Dritte können die Arbeit elektronisch auf beliebigen Servern 
                anbieten oder gedruckte Kopien erstellen (aber: mit Namensnennung, \r\n
                nicht-kommerziell und keine Veränderung).",
                'desc_markup' => '<!-- Creative Commons-Lizenzvertrag -->
                Dieser Inhalt ist unter einer <a rel="\"license\"" 
                href="\"https://creativecommons.org/licenses/by-nc-nd/3.0/de/\"">Creative Commons-Lizenz</a> 
                lizenziert.<!--\r\n\r\n<rdf:RDF xmlns=\"http://web.resource.org/cc/\"\r\n    
                xmlns:dc=\"http://purl.org/dc/elements/1.1/\"\r\n    
                xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\">\r\n<Work rdf:about=\"\">\r\n   
                <dc:type rdf:resource=\"http://purl.org/dc/dcmitype/Text\" ></dc:type>\r\n   
                <license rdf:resource=\"https://creativecommons.org/licenses/by-nc-nd/3.0/de/\" >
                </license>\r\n
                </Work>\r\n\r\n<License rdf:about=\"https://creativecommons.org/licenses/by-nc-nd/3.0/de/\">\r\n   
                <permits rdf:resource=\"http://web.resource.org/cc/Reproduction\" ></permits>\r\n   
                <permits rdf:resource=\"http://web.resource.org/cc/Distribution\" ></permits>\r\n   
                <requires rdf:resource=\"http://web.resource.org/cc/Notice\" ></requires>\r\n   
                <requires rdf:resource=\"http://web.resource.org/cc/Attribution\" ></requires>\r\n   
                <prohibits rdf:resource=\"http://web.resource.org/cc/CommercialUse\" ></prohibits>\r\n
                </License>\r\n\r\n</rdf:RDF>\r\n\r\n-->',
                'desc_text' => "Dies ist die restriktivste der sechs Kernlizenzen. 
                Sie erlaubt lediglich Download und Weiterverteilung des Werkes/Inhaltes 
                unter Nennung Ihres Namens, jedoch keinerlei Bearbeitung oder kommerzielle Nutzung.",
                'language' => 'de',
                'link_licence' => 'https://creativecommons.org/licenses/by-nc-nd/3.0/de/deed.de',
                'link_logo' => 'https://i.creativecommons.org/l/by-nc-nd/3.0/de/88x31.png',
                'mime_type' => 'text/html',
                'name_long' => 'Creative Commons - Namensnennung - Nicht kommerziell - Keine Bearbeitung (CC BY-NC-ND)',
                'name' => 'CC BY-NC-ND',
                'pod_allowed' => true,
                'sort_order' => 6,
            ],
        ]);
    }
}
