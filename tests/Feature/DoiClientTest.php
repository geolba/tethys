<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Tethys\Utils\DoiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoiClientTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $response = $this->get('/');
    //     $response->assertStatus(200);
    // }

    public function testCheckDoi()
    {
        $client = new DoiClient();
        // $this->setExpectedException('Opus\Doi\ClientException');
        $publish_id = 1;
        $prefix = config('tethys.datacite_prefix');
        $doiValue = $prefix . '/tethys.' . $publish_id;
        $appUrl = config('app.url');
        $landingPageUrl = $appUrl . "/dataset/" . $publish_id;
        $result = $client->checkDoi(
            $doiValue,
            $landingPageUrl
        );
        $this->assertTrue($result);

        $result = $client->checkDoi(
            '10.5072/tethys-999',
            'http://localhost/opus4/frontdoor/index/index/111'
        );
        $this->assertFalse($result);
    }

    // public function testRegisterDoiWithDataCiteTestAccount()
    // {
    //     // $this->markTestSkipped(
    //     //     'Test kann nur manuell gestartet werden (Zugangsdaten zum MDS-Testservice von DataCite erforderlich)'
    //     // );

    //     $myRequest = new Request();
    //     $myRequest->setMethod('POST');

    //     $myRequest->request->add(
    //         [
    //             'publish_id' => 1,
    //             // 'path' => 'https://www.demo.laudatio-repository.org/foo'
    //         ]
    //     );

    //     $doiController = new \App\Http\Controllers\DoiController(new DoiClient());
    //     $doiController->store($myRequest);
        

    //     // $client = new DoiClient();
    //     // $client->registerDoi(
    //     //     '10.5072/tethys-999',
    //     //     xmlMeta,
    //     //     'http://localhost/opus4/frontdoor/index/index/999'
    //     // );
    // }

    public function testGetMetadataForDoi()
    {
        $client = new DoiClient();
        // $this->setExpectedException('Opus\Doi\ClientException');
        $publish_id = 1;
        $prefix = config('tethys.datacite_prefix');
        $doiValue = $prefix . '/tethys.' . $publish_id;
        $response = $client->getMetadataForDoi(
            $doiValue
        );
        $this->assertEquals(200, $response->getStatusCode());
        $testXml = new \SimpleXMLElement($response->getBody()->getContents());
        Log::alert($testXml->saveXML());
    }
}
