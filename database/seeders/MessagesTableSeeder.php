<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
// use Database\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->insert([
            [
                // 1
                'metadata_element' => 'dataset_language',
                'help_text' => 'In Abhängigkeit der Publikationssprache sollten 
                die Metadaten in Englisch oder Deutsch eingegeben werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 2
                'metadata_element' => 'dataset_type',
                'help_text' => 'Hier ist die Datenpublikation nach fix vorgegebenen Kategorien zu klassifizieren.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 3
                'metadata_element' => 'titles',
                'help_text' => 'Hier werden Titel und gegebenenfalls weitere Titel, wie übersetzter Titel, 
                Untertitel und alternativer Titel, angegeben.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 4
                'metadata_element' => 'main_title',
                'help_text' => 'Aussagekräftiger Haupttitel der Datenpublikation, mindestens vier Zeichen.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 5
                'metadata_element' => 'additional_titles',
                'help_text' => 'Durch das Anklicken des Pluszeichens können optional zusätzliche Titel mit 
                vorgegebenen Kategorien (z.B. alternativer Titel, Untertitel, übersetzter Titel) in 
                Deutsch und/oder Englisch angegeben werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 6
                'metadata_element' => 'description',
                'help_text' => 'Hier erfolgt die Beschreibung der Daten durch eine Zusammenfassung und bei Bedarf  
                durch eine Methoden- und/oder technische Beschreibung. Wenn Deutsch als Hauptsprache ausgewählt wurde, 
                so muss zusätzlich auch ein englischer Abstract publiziert werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 7
                'metadata_element' => 'main_abstract',
                'help_text' => 'Eine Beschreibung kann unter anderem eine Zusammenfassung, ein Inhaltsverzeichnis, 
                eine grafische Darstellung oder eine Freitextbeschreibung der Datenpublikation sein 
                (maximal 2.500 Zeichen).',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 8
                'metadata_element' => 'additional_descriptions',
                'help_text' => 'Zusätzliche Beschreibung nach vorgegebenen Kategorien, 
                z.B. Methode, technische Beschreibung, Übersetzung; Sprachauswahl möglich (maximal 2.500 Zeichen).',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 9
                'metadata_element' => 'creator',
                'help_text' => 'Nennen des ursprünglichen Autors oder der Autorin  der Datenpublikation. 
                Die Autorenschaft können Person(en) und Organisation(en) sein; mehrere Nennungen sind möglich; 
                Angabe von Vorname, Nachname und E-Mail ist verpflichtend. ORCID  kann optional angegeben werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 10
                'metadata_element' => 'contributor',
                'help_text' => 'Nennen der Person(en) oder Organisation(en), die bei der Erstellung der 
                Dateninhalte mitgewirkt haben. Mehrere Nennungen sind möglich, Angabe von Vorname, 
                Nachname und E-Mail ist verpflichtend.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 11
                'metadata_element' => 'orcid',
                'help_text' => 'Optionale Eingabe einer ORCID (Open Researcher and Contributor Identifier ); 
                dauerhafte digitale Kennung für Autorinnen und Autoren (Forschende).',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 12
                'metadata_element' => 'corporate_name',
                'help_text' => 'TETHYS Research Data Publisher for Geoscience Austria',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 13
                'metadata_element' => 'terms_conditions',
                'help_text' => 'Mit dem Setzen des Häkchens akzeptiere ich die Terms and Conditions und bestätige, 
                dass ich diese gelesen und verstanden habe. [Link]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 14
                'metadata_element' => 'project',
                'help_text' => 'In welchem Projekt wurde der Datensatz erzeugt?',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 15
                'metadata_element' => 'embargo_date',
                'help_text' => 'Zeitpunkt, zu dem die Datenpublikation frühestens veröffentlicht werden soll. 
                Bei Angabe eines Embargo Date werden die Metadaten schon zum Lesen freigegeben, 
                die mitpublizierten Datensätze und Dokumente sind bis zu dieser Frist für den Download 
                allerdings gesperrt und können nicht heruntergeladen werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 16
                'metadata_element' => 'geolocation',
                'help_text' => 'Ist die räumliche Abgrenzung des Gebietes der Datenpublikation nach 
                geografischen Koordinaten. Die Angabe kann durch das Aufziehen eines Rechtecks 
                in der Karte oder durch die Eingabe der Koordinaten erfolgen.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 17
                'metadata_element' => 'validate_coordinates',
                'help_text' => 'Nach dem Aufziehen eines Rechtecks in der Karte oder der Eingabe von Koordinaten, 
                müssen die Angaben validiert werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 18
                'metadata_element' => 'coverage',
                'help_text' => 'Hier können Informationen zur Höhe und/oder Tiefe in Meter und/oder 
                Angaben von Zeit als absolute Werte oder als Spanne angegeben werden. 
                Angabe der Zeit in yyyy-MM-dd HH:mm:ss.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 19
                'metadata_element' => 'dataset_references',
                'help_text' => 'Hier kann die Datenpublikation schon zu anderen Publikationen verlinkt werden 
                durch die Angabe der ID, dem Typ, die Beziehung und die Bezeichnung der Referenz.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 20
                'metadata_element' => 'reference_value',
                'help_text' => 'Hier kann die ID wie eine DOI (z.B. https://doi.pangaea.de/10.1594/PANGAEA.701578), 
                eine URL/URN (z.B. http://resource.geolba.ac.at/structure/167), 
                eine ISBN (z.B. 978-3950462555), 
                eine HANDEL (z.B. https://hdl.handle.net/20.500.11756/582326e3) 
                oder ISSN (z.B. 1563-0846) angegeben werden .',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 21
                'metadata_element' => 'reference_type',
                'help_text' => 'Typen von Ressourcenbezeichnern, wie z.B. DOI, HANDLE, ISBN, ISSN, URL, URN.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 22
                'metadata_element' => 'reference_relation',
                'help_text' => 'Vordefinierte Typen von Beziehungen. Details dazu sind im Data Policy nachzulesen.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 23
                'metadata_element' => 'reference_label',
                'help_text' => 'Freitext zur Bezeichnung oder Beschreibung der Referenz.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 24
                'metadata_element' => 'dataset_keywords',
                'help_text' => 'Es sind mindestens drei Schlüsselwörter in der Hauptsprache anzugeben. 
                Die angegebenen Schlüsselwörter kommen im Titel nicht vor. ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 25
                'metadata_element' => 'keyword_value',
                'help_text' => 'Angabe eines Schlüsselwortes in Abhängigkeit von der 
                gewählten Hauptsprache (Dataset_language).',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 26
                'metadata_element' => 'keyword_type',
                'help_text' => 'Aktuell können hier nur unkontrollierte Schlüsselwörter in Freitext  
                angegeben werden und somit keine vordefinierte Vokabulare 
                wie z.B. GEMET, AGROVOC, Keyword-Thesaurus etc.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 27
                'metadata_element' => 'rights_list',
                'help_text' => 'Nutzungsbestimmungen nach Creative Commons (Lizenzmodelle); 
                Informationen über die Rechte bzw. auch Nutzung der Datenpublikation; 
                Grundsätzlich sollten alle Beiträge für das Repository „Open Access“ sein. 
                (Creative Commons – Namensnennung). 
                Die Lizenzierung kann nach dem Publizieren nicht mehr verändert werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 28
                'metadata_element' => 'file_upload',
                'help_text' => 'Upload von Dokumenten bzw. Daten (mehrere Dokumente möglich) in 
                vorgegebenen Datenformaten, wie z.B. csv, txt, pdf, GeoPackage etc. 
                Die Daten können durch „Drag and Drop“ hineingeschoben oder durch 
                das Anklicken der Box ausgewählt werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 29
                'metadata_element' => 'file_label',
                'help_text' => 'Freitext zur Bezeichnung oder Beschreibung der hochgeladenen Datei.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 30
                'metadata_element' => 'upload_successfull_release',
                'help_text' => 'Mit Release wird die Datenpublikation gespeichert. 
                Die Datenpublikation kann später fortgesetzt oder gelöscht werden. ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 31
                'metadata_element' => 'release_define reviewer',
                'help_text' => 'Optionale Angabe eines bevorzugten Reviewers.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 32
                'metadata_element' => 'release_release',
                'help_text' => 'Mit Release wird die Datenpublikation in den Review-Prozess übergeben 
                und kann nicht mehr bearbeitet werden.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
