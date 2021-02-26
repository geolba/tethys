<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Tethys\Utils\DoiClient;
use Illuminate\Http\Request;

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

    // public function testCheckDoi()
    // {
    //     $client = new DoiClient();
    //     // $this->setExpectedException('Opus\Doi\ClientException');
    //     $result = $client->checkDoi(
    //         '10.5072/tethys-999',
    //         'http://localhost/opus4/frontdoor/index/index/999'
    //     );
    //     $this->assertTrue($result);

    //     $result = $client->checkDoi(
    //         '10.5072/tethys-999',
    //         'http://localhost/opus4/frontdoor/index/index/111'
    //     );
    //     $this->assertFalse($result);
    // }

    public function testRegisterDoiWithDataCiteTestAccount()
    {
        // $this->markTestSkipped(
        //     'Test kann nur manuell gestartet werden (Zugangsdaten zum MDS-Testservice von DataCite erforderlich)'
        // );

        $myRequest = new Request();
        $myRequest->setMethod('POST');

        $myRequest->request->add(
            [
                'publish_id' => 1,
                'path' => 'https://www.demo.laudatio-repository.org/foo'
            ]
        );

        $doiController = new \App\Http\Controllers\DoiController(new DoiClient());
        $doiController->store($myRequest);
        

        // $client = new DoiClient();
        // $client->registerDoi(
        //     '10.5072/tethys-999',
        //     xmlMeta,
        //     'http://localhost/opus4/frontdoor/index/index/999'
        // );
    }
}
