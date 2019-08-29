<?php

use Carbon\Carbon;
// use Database\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            [
                // 1
                //'title'       => 'Terms and conditions',
                'page_slug'   => 'terms-and-conditions',
                // 'description' => $faker->text($maxNbChars = 255),
                'seo_title' => 'Terms and Conditions',
                'seo_keyword' => 'GBA, repository, terms and conditions',
                'seo_description' => 'Terms and Conditions',
                'status' => '1',
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                // 2
                //'title'       => 'imprint',
                'page_slug'   => 'imprint',
                // 'description' => $faker->text($maxNbChars = 255),
                'seo_title' => 'Impressum',
                'seo_keyword' => 'GBA, repository, imprint',
                'seo_description' => 'Imprint',
                'status' => '1',
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                // 3
                //'title'       => 'Terms and conditions',
                'page_slug'   => 'about',
                // 'description' => $faker->text($maxNbChars = 255),
                'seo_title' => 'About',
                'seo_keyword' => 'GBA, repository, about',
                'seo_description' => 'About',
                'status' => '1',
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                // 4
                //'title'       => 'Terms and conditions',
                'page_slug'   => 'home-welcome',
                // 'description' => $faker->text($maxNbChars = 255),
                'seo_title' => 'About',
                'seo_keyword' => 'GBA, repository, about',
                'seo_description' => 'About',
                'status' => '1',
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ]);
       
        DB::table('page_translations')->insert([
            [
                'page_id' => 1, //terms and conditions
                'locale' => 'de',
                'title' => 'Geschäftsbedingungen',
                'description' => '<h2>1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Open Access und FAIR Erklärung</h2><p>iwas große Fokus liegt beim GBA-RDR beim Erlangen eines hohen Grades an die Zugänglichkeit und nachhaltige Verfügbarkeit für alle interessierten Nutzer. Dadurch soll eine <strong>verantwortungsvolle </strong>Weiterverwertung sowie Weiterentwicklung der Daten ermöglicht werden. Ebenfalls wird von Seitens des GBA-RDRs großen Wert auf das Ermöglichen von der Weiterverwendung von den publizierten Forschungsdaten gelegt. Dies wird durch das Einhalten der Prinzipien von Open Access und FAIR erlangt.</p><p>Unter Open Access wird der kostenfreie sowie öffentliche Zugang zu den im RDR archivierten Datenpublikationen verstanden.</p><p>Das Prinzip FAIR<a href="#_ftn1">[1]</a>, welches ein Akronym für <i>Findability</i> (Auffindbarkeit), <i>Accessibility</i> (Zugänglichkeit), <i>Interoperability</i> (Kompatibilität), und <i>Reusability</i> (Wiederverwendbarkeit) ist, unterstützt eine nachhaltige wissenschaftliche Datenpflege sowie –verwaltung. Erst durch die garantierte Auffindbarkeit, volle Zugänglichkeit zu den Daten, gewährleistete Kompatibilität mit anderen Datenbeständen so wie die wissenschaftliche Wiederverwendbarkeit, erhält die Datenpublikation einen großen Wert.</p><p>Das GBA-RDR behält sich vor, bei Einzelfällen und begründet Datenpublikationen nur eingeschränkt zur Verfügung zu stellen oder/und mit einem Embargo zu belegen. Grundsätzlich sollen die Nutzer die publizierten Daten uneingeschränkt lesen, kopieren, drucken und je nach vergebener Lizenz verarbeiten sowie verteilen können. Dabei verpflichtet sich der Nutzer ausnahmslos die Daten zu zitieren. Die Urheberrechte der Autorenschaft werden dabei nicht berührt.</p><p>&nbsp;</p><h2>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Leitlinie</h2><ul><li>Ziele</li><li>Anforderungen an die elektronische Publikation</li><li>Urheber- und Nutzungsrechte</li><li>Sacherschließung auf dem Publikationsserver</li><li>Archivierung von Publikationen</li><li>Technische Eigenschaften des Publikationsservers</li><li>Organisatorische Regelungen</li></ul><p>&nbsp;</p><h2>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rechte und Pflichten</h2><p>Die im GBA-RDR veröffentlichten Informationen und Metadaten unterliegen grundsätzlich den Open-Access-Bedingungen, wenn nicht anders angegeben. Die publizierten Datensets unterliegen einem definierten Zugriffs- sowie Nutzungsrecht welche in den Metadaten eindeutig beschrieben sind.</p><p>&nbsp;</p><p>Zugriffsrecht</p><p>Es wird zwischen uneingeschränkten und eingeschränkten Zugriffsrecht unterschieden. Während bei dem uneingeschränkten Zugriffsrecht voller Zugriff auf die Daten und den dazugehörigen Informationen besteht, so kann ein eingeschränktes Zugriffsrecht auf bestimmte oder unbestimmte Zeit sowie auf alle oder nur auf Teile der Daten bestehen. Die detaillierte Zugriffsrechtsbestimmung sowie die Dauer einer Zugriffsbeschränkung sind in den Metadaten vermerkt. Grundsätzlich wird ein uneingeschränktes Zugriffsrecht vergeben. Unter bestimmten Voraussetzungen jedoch kann diese eingeschränkt sein.</p><p><br>&nbsp;</p><p>Nutzungsrecht</p><p>Durch die Vergabe von Creative Commons Lizenzen werden jeder Datenpublikation mit definierten Nutzungsrechte ausgestattet. Die dabei verwendete Lizenz wird verpflichtend mit den zur Datenpublikation gehörenden Metadaten bereitgestellt.</p><p>&nbsp;</p><p><br>Urheberrecht</p><p>Die Urheberrechte der auf dem GBA-RDR Server veröffentlichten Dokumenten liegen ausnahmslos bei den jeweiligen Autoren.</p><p>Der Urheber räumt jedoch durch die Publikation der Daten ein grundsätzliches Zugriffs- sowie Nutzungsrecht ein, welches mit der Datenpublikation geklärt und in den Metadaten verankert ist. Zusätzlich stimmt der Urheber zu, dass die Metadaten des Dokuments unentgeltlich anderen öffentlich einsehbaren Datenbanken zur Verfügung gestellt werden und somit an der Forschungsdateninfrastruktur teilnimmt.</p><p>Mit der Publikation sowie der Archivierung der Daten und allen dazugehörigen Dokumenten im GBA-RDR erklärt der Urheber keine Rechte Dritter verletzt zu haben. Der Urheber verpflichtet sich vor dem Einreichen der Daten und allen dazugehörigen Dokumenten, alle darin involvierten Miturheber, Co-Autoren und Drittmittelgeber in Kenntnis zu setzen. Wenn die zu veröffentlichenden Daten auf Fremddaten aufbauen oder diese ergänzen, so ist der Urheber der aktuellen Daten und den dazugehörigen Dokumenten verpflichtet die Rec</p>',
            ],
            [
                'page_id' => 1, //terms and conditions
                'locale' => 'en',
                'title' => 'Terms and Conditions',
                'description' => '<h2>english text for terms and conditions</h2>',
            ],
            [
                'page_id' => 2, //imprint
                'locale' => 'de',
                'title' => 'Impressum',
                'description' => '<p><strong>Für den Inhalt verantwortlich: hfjklög</strong></p><p>Geologische Bundesanstaltdsfsdf</p><p>Neulinggasse 38, 1030 Wien</p><p>» office@geologie.ac.at</p><p>&nbsp;</p><p>Telefon: +43-1-7125674</p><p>Fax: +43-1-7125674-56</p><p>&nbsp;</p><p>Technische Umsetzung und Betreuung</p><p>Geologische Bundesanstalt</p><p>Abteilung Geoinformation und Abteilung IT &amp; GIS</p><p>Neulinggasse 38, 1030 Wien</p><p>» repository@geologie.ac.at</p><p>&nbsp;</p><p>Bei technischen Problemen steht Ihnen das RDR-Team, erreichbar unter repository@geologie.ac.at, zur Seite.</p><p>&nbsp;</p><p>Hinweise und Haftungsausschluss</p><p>Eine Haftung oder Garantie für Aktualität, Richtigkeit und Vollständigkeit der zur Verfügung gestellten Informationen und Daten ist ausgeschlossen.</p><p>Dieser Hinweis gilt auch für alle anderen Website, auf die durch Hyperlinks verwiesen wird. Die Geologische Bundesanstalt ist für den Inhalt von Websites, die mittels einer solchen Verbindung erreicht werden, nicht verantwortlich.</p><p>&nbsp;</p><p>Bildernachweis</p><p>### Muss dann direkt auf der RDR Webpage ausgefüllt werden sobald diese existiert ###</p><p>&nbsp;</p>',
            ],
            [
                'page_id' => 2, //imprint
                'locale' => 'en',
                'title' => 'Imprint',
                'description' => '<h2>english text for imprint</h2>',
            ],
            [
                'page_id' => 3, //about
                'locale' => 'de',
                'title' => 'Über uns',
                'description' => '<p>RDR (Research Data Repository) ist ein interdisziplinäres digitales Datenarchiv zur Archivierung und Publikation von Forschungsdaten aus abgeschlossenen wissenschaftlichen Studien und Projekten.</p><p>RDR focuses on disciplines who do not have a tradition of data sharing thus ensuring better availability, sustainable preservation and (independent) publication capacity of their research data.</p>',
            ],
            [
                'page_id' => 3, //about
                'locale' => 'en',
                'title' => 'About Us',
                'description' => '<h2>english text for about us</h2>',
            ],
            [
                'page_id' => 4, //home-welcome
                'locale' => 'de',
                'title' => 'Data Research Repository',
                'description' => '<div>
                <p>Die Bibliothek bietet allen Angehörigen der Hochschule – Lehrenden
                        und Studierenden – die Möglichkeit, elektronisch erzeugte, qualifizierte
                        Dokumente über ihr Online-Publikations-System kostenlos zu
                        veröffentlichen.  Die Texte stehen nach ihrer Veröffentlichung weltweit
                        im Internet zur Verfügung und werden von der Bibliothek dauerhaft
                        archiviert. Die Dokumente sind über Bibliothekskataloge und über die
                        Suchmaschinen des WWW erschlossen und zugänglich.</p>                
                <p>Wenn Sie nach Texten der Hochschule suchen wollen, wählen Sie bitte
                        das Menü "Suchen"; dort stehen Ihnen verschiedene Recherchemöglichkeiten
                        zur Verfügung. Wollen Sie ein Dokument publizieren, wählen Sie bitte das
                        Menü "Veröffentlichen"; mit wenigen Schritten können Sie dort Ihr
                        Dokument an den Hochschulschriftenserver übertragen.</p>
                </div>',
            ],
            [
                'page_id' => 4, //home-welcome
                'locale' => 'en',
                'title' => 'Data Research Repository',
                'description' => '<div>
                <p>Theee library offers to publish electronically generated and qualified documents on its online publication system. 
                This service is for university members only and free of charge. After publication,
                the texts are available worldwide on the Internet and will be archived permanently by the library. 
                The documents are indexed and made accessible in library catalogues and Web search engines.</p>   
                </div>',
            ],
         ]);
    }
}
