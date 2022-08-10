<?php

namespace Database\Seeders;

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
                'page_slug' => 'terms-and-conditions',
                // 'description' => $faker->text($maxNbChars = 255),
                'seo_title' => 'Terms and Conditions',
                'seo_keyword' => 'GBA, repository, terms and conditions',
                'seo_description' => 'Terms and Conditions',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 2
                //'title'       => 'imprint',
                'page_slug' => 'imprint',
                // 'description' => $faker->text($maxNbChars = 255),
                'seo_title' => 'Impressum',
                'seo_keyword' => 'GBA, repository, imprint',
                'seo_description' => 'Imprint',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 3
                //'title'       => 'Terms and conditions',
                'page_slug' => 'about',
                // 'description' => $faker->text($maxNbChars = 255),
                'seo_title' => 'About',
                'seo_keyword' => 'GBA, repository, about',
                'seo_description' => 'About',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 4
                //'title'       => 'Terms and conditions',
                'page_slug' => 'home-welcome',
                // 'description' => $faker->text($maxNbChars = 255),
                'seo_title' => 'About',
                'seo_keyword' => 'GBA, repository, about',
                'seo_description' => 'About',
                'status' => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::table('page_translations')->insert([
            [
                'page_id' => 1, //terms and conditions
                'locale' => 'de',
                'title' => 'Terms and Conditions',
                'description' => '<p>Die Domain-Inhaberin (die GBA), explizit aber die Autoren der Website 
                Tethys RDR (www.tethys.at), übernehmen keine Gewähr für die Aktualität, Richtigkeit 
                und Vollständigkeit der bereitgestellten Informationen. Haftungsansprüche gegen die Autoren, 
                die sich auf Schäden materieller oder ideeller Art beziehen, die durch die Nutzung oder 
                Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und 
                unvollständiger Informationen verursacht wurden, werden ausdrücklich ausgeschlossen, 
                soweit nicht Vorsatz oder grobe Fahrlässigkeit der Autoren vorliegt. 
                Die Autoren behalten es sich ausdrücklich vor, Teile der Seiten oder das gesamte 
                Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die 
                Veröffentlichung zeitweise oder endgültig einzustellen.</p>
                
                <h5>Für den Inhalt verantwortlich</h5>
                <p>Bei direkten oder indirekten Verweisen auf fremde Internetseiten (Links), 
                die außerhalb des Verantwortungsbereichs der Autoren liegen, wird eine Haftung 
                nur dann übernommen, wenn die Autoren von den Inhalten Kenntnis haben, 
                dies zumutbar ist und diese über die technischen Mittel verfügen, 
                um deren Verwendung bei rechtswidrigen Inhalten zu verhindern. 
                Die Autoren erklären hiermit ausdrücklich, dass zum Zeitpunkt der Linksetzung 
                keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. 
                Die Autoren haben keinerlei Einfluss auf die aktuelle und zukünftige Gestaltung 
                und auf die Inhalte der verknüpften Seiten. Deshalb distanzieren sie sich hiermit 
                ausdrücklich von allen Inhalten aller gelinkten/verknüpften Seiten, die nach der 
                Linksetzung verändert wurden. Diese Feststellung gilt auch für alle innerhalb 
                des eigenen Internetangebotes gesetzten Links und Verweise. Für illegale, 
                fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der 
                Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, 
                haftet allein der Anbieter der Seite, auf die verwiesen wurde, nicht derjenige, der via 
                Link auf die betreffende Seite verwiesen wurde.</p>

                <h5>Urheber- und Kennzeichenrecht</h5>
                <p>Die Autoren sind bestrebt, in allen Publikationen die Urheberrechte aller 
                verwendeten Grafiken und Texte zu beachten, von ihnen selbst erstellte Grafiken 
                und Texte zu nutzen oder auf lizenzfreie Grafiken und Texte zurückzugreifen. 
                Alle innerhalb des Internetangebotes genannten und von Dritten registrierten 
                Marken- und Warenzeichen unterliegen uneingeschränkt den Bestimmungen des jeweils 
                gültigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigentümer. 
                Allein aufgrund der bloßen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen 
                nicht den Rechten Dritter unterliegen. Sofern nicht anders angegeben, wird der Inhalt 
                dieser Website unter einer Creative Commons Attribution 3.0-Lizenz lizenziert.</p>

                <h5>Datenschutz-Bestimmungen</h5>
                <p>Mit dieser Datenschutzerklärung möchte unsere gastgebende Einrichtung, 
                die „Geologische Bundesanstalt“, die Öffentlichkeit über Art, Umfang und Zweck 
                der von uns erhobenen, verwendeten und verarbeiteten personenbezogenen Daten informieren. 
                Darüber hinaus werden betroffene Personen mittels dieser Datenschutzerklärung über die 
                ihnen zustehenden Rechte informiert.</p>
                <p>Die Nutzung der Internetseiten von Tethys RDR ist ohne Angabe personenbezogener Daten möglich. 
                Wenn eine betroffene Person jedoch unsere Dienste wie das Übermitteln von Datensätzen nutzen 
                oder mit uns in Kontakt treten möchte, ist die Verarbeitung personenbezogener Daten erforderlich. 
                Wenn die Verarbeitung personenbezogener Daten notwendig ist und keine gesetzliche Grundlage für eine 
                solche Verarbeitung besteht, holen wir in der Regel die Zustimmung der betroffenen Person ein. 
                Wir erfassen Informationen von Ihnen, wenn Sie sich auf unserer Website anmelden.</p>
                <p>Die Verarbeitung personenbezogener Daten von datenpublizierenden Autoren, 
                deren Co-Autoren sowie Beitragende, wie Name, E-Mail-Adresse und optional die ORCID ID 
                erfolgt stets durch die datenpublizierenden Autoren im Einklang mit der 
                Allgemeinen Datenschutzgrundverordnung (DSGVO) nach Artikel 6, Absatz 1b. 
                Diese Informationen werden zum Zwecke der Anmeldung der datenpublizierenden Autoren und zum 
                Zwecke der Umsetzung der guten wissenschaftlichen Praxis gesammelt. Damit einher geht, 
                dass nach erfolgreichem Publizieren der Daten keine Löschung oder Veränderung der Daten und 
                Metadaten inklusive aller personenbezogenen Daten möglich ist.</p>
                <p>Als für die Verarbeitung Verantwortlicher hat Tethys RDR zahlreiche technische und 
                organisatorische Maßnahmen getroffen, um den bestmöglichen Schutz der über diese Website 
                verarbeiteten personenbezogenen Daten sicherzustellen. Internetbasierte Datenübertragungen 
                können jedoch grundsätzlich Sicherheitslücken aufweisen, so dass ein absoluter Schutz möglicherweise 
                nicht gewährleistet ist. </p>
                <p>Sie haben grundsätzlich das Recht auf Auskunft, Berichtigung, Löschung, Einschränkung, 
                Datenübertragbarkeit und Widerspruch. 
                Dafür wenden Sie sich bitte an die Datenschutzbeauftragte:</p>
                <span>Dr. Viktoria Haider<span><br/>
                <span>E-Mail: <a href= "mailto:datenschutz@geologie.ac.at">datenschutz@geologie.ac.at</a></span><br/>
                <p>Wenn Sie glauben, dass die Verarbeitung Ihrer Daten gegen das Datenschutzrecht 
                verstößt oder Ihre datenschutzrechtlichen Ansprüche sonst in einer Weise verletzt 
                worden sind, können Sie bei der dafür zuständigen Aufsichtsbehörde eine Beschwerde einlegen:</p>
                <span>Österreichische Datenschutzbehörde<span><br/>
                <span>Barichgasse 40–42, 1030 Wien<span><br/> 
                <span>Telefon: +43 1 52 152‐0<span><br/> 
                <span>E-Mail: <a href= "mailto:dsb@dsb.gv.at">dsb@dsb.gv.at</a></span><br/>

                <h5>Erfassung allgemeiner Daten und Informationen</h5>
                <p>Die Website von Tethys RDR sammelt eine Reihe von allgemeinen Daten und Informationen, 
                wenn eine betroffene Person oder ein automatisiertes System die Website aufruft. Diese allgemeinen Daten 
                und Informationen werden in den Server-Protokolldateien gespeichert. 
                Gesammelt werden (1) die verwendeten Browsertypen und -versionen, (2) das vom zugreifenden 
                System verwendete Betriebssystem, (3) die Website, von der aus ein zugreifendes System auf unsere Website 
                gelangt (sogenannte Referrer), (4) die Sub-websites, (5) Datum und Uhrzeit des Zugriffs auf die Internetseite, 
                (6) eine Internetprotokolladresse (IP-Adresse), (7) der Internetdienstanbieter des zugreifenden Systems 
                und (8) alle anderen ähnlichen Daten und Informationen, die im Falle von Angriffen auf unsere 
                Informationstechnologiesysteme verwendet werden können.</p>
                <p>Bei Verwendung dieser allgemeinen Daten und Informationen kann Tethys RDR keine Rückschlüsse 
                auf die betroffene Person ziehen. Diese Informationen werden vielmehr benötigt, um (1) den Inhalt 
                unserer Website korrekt bereitzustellen, (2) den Inhalt unserer Website sowie deren Werbung zu optimieren, 
                (3) die langfristige Überlebensfähigkeit unserer Informationstechnologiesysteme und der Website-Technologie 
                sicherzustellen und (4) den Strafverfolgungsbehörden, welche für die strafrechtliche Verfolgung im Falle eines 
                Cyberangriffs erforderlichen Informationen zur Verfügung stellen. Daher analysiert Tethys RDR anonym erhobene 
                Daten und Informationen statistisch mit dem Ziel, den Datenschutz und die Datensicherheit unserer Institution 
                zu erhöhen und ein optimales Schutzniveau für die von uns verarbeiteten personenbezogenen Daten zu gewährleisten. 
                Die anonymen Daten der Server-Logfiles werden getrennt von allen personenbezogenen Daten einer 
                betroffenen Person gespeichert.</p>

                <h5>Anmeldung/Registrierung auf unserer Website</h5>
                <p>Die betroffene Person hat die Möglichkeit, sich auf der Website des für die Verarbeitung Verantwortlichen 
                unter Angabe personenbezogener Daten anzumelden (zu registrieren). Welche personenbezogenen Daten an die 
                Steuerung übermittelt werden, bestimmt die jeweilige Eingabemaske des Anmeldeformulars. Die von der betroffenen 
                Person eingegebenen personenbezogenen Daten werden ausschließlich für den internen Gebrauch durch den für die 
                Verarbeitung Verantwortlichen und für eigene Zwecke erhoben und gespeichert. Der Controller kann die 
                Übertragung an einen oder mehrere Prozessoren (z.B. einen wissenschaftlichen Herausgeber) anfordern, 
                die personenbezogene Daten auch für einen internen Zweck verwenden, der dem Controller zuzuordnen ist.</p>
                <p>Durch die Registrierung auf der Website des Controllers werden auch die vom Internet Service Provider (ISP) 
                zugewiesene und vom Betroffenen verwendete IP-Adresse – Datum und Uhrzeit der Registrierung – gespeichert. 
                Die Speicherung dieser Daten erfolgt vor dem Hintergrund, dass nur so ein Missbrauch unserer Dienste verhindert 
                und gegebenenfalls eine Aufklärung der begangenen Verstöße ermöglicht wird. Insofern ist die Speicherung dieser 
                Daten erforderlich, um die Steuerung abzusichern. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht 
                an Dritte weitergegeben, es sei denn, es besteht eine gesetzliche Verpflichtung zur Weitergabe der Daten oder 
                die Übermittlung dient der strafrechtlichen Verfolgung.</p>
                <p>Die Registrierung der betroffenen Person mit der freiwilligen Angabe personenbezogener Daten soll es dem für 
                die Verarbeitung Verantwortlichen ermöglichen, die betroffenen Inhalte oder Dienste anzubieten, die aufgrund 
                der Art der betreffenden Angelegenheit nur registrierten Nutzern angeboten werden dürfen. Registrierte Personen 
                können die bei der Registrierung angegebenen personenbezogenen Daten jederzeit ändern oder vollständig aus dem 
                Datenbestand des Verantwortlichen löschen lassen.</p>
                <p>Der für die Verarbeitung Verantwortliche teilt jeder betroffenen Person auf Anfrage jederzeit mit, welche 
                personenbezogenen Daten über die betroffene Person gespeichert sind. Darüber hinaus berichtigt oder löscht 
                der für die Datenverarbeitung Verantwortliche personenbezogene Daten auf Verlangen oder unter 
                Angabe der betroffenen Person, sofern keine gesetzlichen Aufbewahrungspflichten bestehen. Ein in 
                dieser Datenschutzerklärung ausdrücklich benannter Datenschutzbeauftragter sowie die gesamten 
                Mitarbeiter des für die Verarbeitung Verantwortlichen stehen der betroffenen Person als Ansprechpartner 
                zur Verfügung.</p>

                <h5>Kontaktmöglichkeit über die Website</h5>
                <p>Die Website von Tethys RDR enthält Informationen, die einen schnellen elektronischen Kontakt zu unserer 
                Einrichtung sowie eine direkte Kommunikation mit uns ermöglichen. Dazu gehört auch eine allgemeine 
                Adresse der sogenannten elektronischen Post (E-Mail-Adresse). Wenn eine betroffene Person den für 
                die Verarbeitung Verantwortlichen per E-Mail über das Kontaktformular kontaktiert, werden die von 
                der betroffenen Person übermittelten personenbezogenen Daten automatisch gespeichert. Diese von einer 
                betroffenen Person freiwillig übermittelten personenbezogenen Daten werden zum Zwecke der Verarbeitung 
                oder Kontaktaufnahme mit der betroffenen Person gespeichert. Bei Nutzung des Kontaktformulars erfolgt 
                keine Weitergabe dieser personenbezogenen Daten an Dritte.</p>

                <h5>Übermittlung wissenschaftlicher Daten über die Website</h5>
                <p>Die Website von Tethys RDR enthält eine Webanwendung zum Einreichen von wissenschaftlichen Datensätzen, 
                die in der Tethys -Datenbank gespeichert werden, um sie zu veröffentlichen. Wenn eine betroffene Person 
                mit dem Antrag auf Einreichung wissenschaftlicher Daten Kontakt zum für die Verarbeitung Verantwortlichen 
                aufnimmt, werden die von der betroffenen Person übermittelten personenbezogenen Daten automatisch gespeichert. 
                Diese von einer betroffenen Person freiwillig übermittelten personenbezogenen Daten werden zum Zwecke der 
                Verarbeitung oder Kontaktaufnahme mit der betroffenen Person gespeichert.</p>
                <p>Um wissenschaftliche Datensätze erfolgreich bei Tethys RDR einzureichen, muss die betroffene Person einige 
                zusätzliche personenbezogene Daten (z.B. Name der Autoren, der Mitautoren und Beitragende) bereitstellen, 
                damit diese Datensätze in der wissenschaftlichen Gemeinschaft korrekt zitiert werden können. 
                Diese Informationen werden der Öffentlichkeit nach dem Einreichungsprozess über Datensatz-Metadaten 
                (im XML- oder JSON-Format und über die Tethys-Website) zur Verfügung gestellt. Dies ist eine Voraussetzung 
                für das wissenschaftliche Publizieren. Veröffentlichungen zu wissenschaftlichen Daten, einschließlich der oben 
                genannten personenbezogenen Daten, können von Dritten (z.B. Bibliotheken, Datenportalen) unter Verwendung der 
                Metadaten und Datendienste von Tethys RDR verwendet werden.</p>

                <h5>Nutzung von Content Delivery Networks (CDN)</h5>
                <p>Auf dieser Website hat der Controller Javascript, Schriftarten und Bilder integriert, die von 
                Content Delivery Networks bereitgestellt werden. Ein Content Delivery Network (CDN) ist ein geografisch 
                verteiltes Netzwerk von Proxy-Servern und deren Rechenzentren. Ziel ist es, den Service räumlich auf die 
                Endbenutzer zu verteilen, um eine hohe Verfügbarkeit und Leistung zu gewährleisten. Während dieses technischen 
                Verfahrens können Dritte Kenntnis von personenbezogenen Daten, wie der IP-Adresse der betroffenen 
                Person, erlangen. Die bei Tethys verwendeten CDN-Dienste wurden vom Controller auf DSGVO-Konformität geprüft.
                </p>

                <h5>Routinemäßige Löschung und Sperrung personenbezogener Daten</h5>
                <p>Der für die Verarbeitung Verantwortliche verarbeitet und speichert die personenbezogenen Daten der betroffenen 
                Person nur für den Zeitraum, der zur Erreichung des Zwecks der Speicherung erforderlich ist, oder, soweit dies 
                vom europäischen Gesetzgeber oder anderen Gesetzgebern in Gesetzen oder Verordnungen, denen der für die 
                Verarbeitung Verantwortliche unterliegt, gewährt wird.</p>
                <p>Ist der Speicherzweck nicht anwendbar oder läuft eine vom europäischen Gesetzgeber oder einem anderen 
                zuständigen Gesetzgeber festgelegte Speicherfrist ab, werden die personenbezogenen Daten gemäß den gesetzlichen 
                Bestimmungen routinemäßig gesperrt oder gelöscht.</p>
                <p>Bitte beachten Sie auch unsere rechtlichen Hinweise zu Nutzung, Haftungsausschluss und 
                Haftungsbeschränkungen für die Nutzung unserer Website.</p>

                <h5>Uns kontaktieren</h5>
                <p>Bei Fragen zu dieser Datenschutzrichtlinie können Sie sich an uns wenden 
                (<a href="/contact" target=_blank>Kontakt</a>).</p>

                <h5>Änderungen unserer Datenschutzerklärung</h5>
                <p>Wenn wir uns entscheiden, unsere Datenschutzrichtlinie zu ändern, werden wir diese Änderungen auf dieser 
                Seite veröffentlichen. Diese Richtlinie wurde zuletzt am 25.03.2020 geändert.</p>

                <h5>OpenStreetMap</h5>
                <p>Diese Seite nutzt über eine Programmierschnittstelle (Application Programming Interface, API) das 
                Open Source-Mapping-Werkzeug „OpenStreetMap“ (OSM). Anbieter ist die OpenStreetMap Foundation. 
                Zur Nutzung der Funktionen von OpenStreetMap ist es notwendig, Ihre IP-Adresse zu speichern. 
                Diese Informationen werden in der Regel an einen Server von OpenStreetMap übertragen und dort gespeichert. 
                Der Anbieter dieser Seite hat keinen Einfluss auf diese Datenübertragung. Die Nutzung von OpenStreetMap 
                erfolgt im Interesse einer ansprechenden Darstellung unserer Online-Angebote und an einer leichten 
                Auffindbarkeit der von uns auf der Website angegebenen Orte. Dies stellt ein berechtigtes Interesse 
                im Sinne von Artikel 6, Absatz 1 lit. f DSGVO dar. Mehr Informationen zum Umgang mit Nutzerdaten finden 
                Sie in der Datenschutzseite von OpenStreetMap und auf 
                <a href="http://wiki.openstreetmap.org/wiki/Legal_FAQ" target=_blank>/wiki.openstreetmap.org/wiki/Legal_FAQ</a>.
                </p>

                <h5>Rechtswirksamkeit dieses Haftungsausschlusses</h5>
                <p>Dieser Haftungsausschluss ist als Teil des Internetangebotes zu betrachten, von dem aus auf diese Seite 
                verwiesen wurde. Sollten Teile des Textes oder eines Wortlautes nicht, nicht vollständig oder 
                nicht mehr dem geltenden Recht entsprechen, so wird hierdurch die Gültigkeit oder der Inhalt 
                der übrigen Teile des Dokumentes nicht berührt.</p>
                ',
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
                'description' => '<h5>Für den Inhalt verantwortlich</h5>
                <span>Geologische Bundesanstalt</span><br/>
                <span>Neulinggasse 38, 1030 Wien<span><br/>
                <span>office@geologie.ac.at<span><br/>              
                <span>Telefon: +43-1-7125674<span><br/>
                <span>Fax: +43-1-7125674-56<span><br/>              
                <h5>Technische Umsetzung und Betreuung</h5>
                <span>Geologische Bundesanstalt<span><br/>
                <span>Hauptabteilung Informationsdienste<span><br/>
                <span>Neulinggasse 38, 1030 Wien<span><br/>
                <span>repository@geologie.ac.at<span><br/>                
                <p>Bei technischen Problemen steht Ihnen das RDR-Team, erreichbar unter repository@geologie.ac.at, 
                gerne zur Seite.</p>               
                <h5>Hinweise und Haftungsausschluss</h5>
                <p>Eine Haftung oder Garantie für Aktualität, Richtigkeit und 
                Vollständigkeit der zur Verfügung gestellten Daten ist ausgeschlossen.</p>
                <p>Dieser Hinweis gilt auch für alle anderen Webseiten, auf die durch Hyperlinks verwiesen wird. 
                Die Geologische Bundesanstalt ist für den Inhalt von Webseiten, 
                die mittels einer solchen Verbindung erreicht werden, nicht verantwortlich.</p>',
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
                <p>The library offers to publish electronically generated and qualified documents on its online publication system.
                This service is for university members only and free of charge. After publication,
                the texts are available worldwide on the Internet and will be archived permanently by the library.
                The documents are indexed and made accessible in library catalogues and Web search engines.</p>
                </div>',
            ],
        ]);
    }
}
