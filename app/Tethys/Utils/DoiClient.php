<?php
namespace App\Tethys\Utils;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Interfaces\DoiInterface;

class DoiClient implements DoiInterface
{
    private $username;
    private $password;
    private $serviceUrl;
    private $prefix;
    private $base_domain;

    public function __construct()
    {
        $datacite_environment = config('tethys.datacite_environment');
        if ($datacite_environment == "debug") {
            $this->username = config('tethys.datacite_test_username');
            $this->password = config('tethys.datacite_test_password');
            $this->serviceUrl = config('tethys.datacite_test_service_url');
            $this->prefix = config('tethys.datacite_test_prefix');
            $this->base_domain = config('tethys.test_base_domain');
        } elseif ($datacite_environment == "production") {
            $this->username = config('tethys.datacite_username');
            $this->password = config('tethys.datacite_password');
            $this->serviceUrl = config('tethys.datacite_service_url');
            $this->prefix = config('tethys.datacite_prefix');
            $this->base_domain = config('tethys.base_domain');
        }
        if (is_null($this->username) || is_null($this->password) || is_null($this->serviceUrl)) {
            $message = 'missing configuration settings to properly initialize DOI client';
            Log::error($message);
            throw new DoiClientException($message);
        }
    }

    /**
     * Creates a DOI with the given identifier
     *
     * @param string $identifier The desired DOI identifier e.g. '10.5072/tethys.999',
     * @param $xmlMeta
     * @param $landingPageUrl e.g. https://www.tethys.at/dataset/1
     *
     * @return GuzzleHttp\Psr7\Response The http response in the form of a Guzzle response
     */
    public function registerDoi($doiValue, $xmlMeta, $landingPageUrl)
    {
       
        // Schritt 1: Metadaten als XML registrieren
        // state draft
        $response = null;
        $url = $this->serviceUrl . '/metadata/' . $doiValue;
        try {
            $client = new Client([
                'auth' => [$this->username, $this->password],
                // 'base_uri' => $url,
                'verify' => false,
                'headers' => [
                    'Content-Type' => 'application/xml;charset=UTF-8',
                ],
                // 'body' => $xmlMeta,
            ]);
            // Provide the body as a string.
            $response = $client->request('PUT', $url, [
                'body' => $xmlMeta,
            ]);
        } catch (\Exception $e) {
            $message = 'request to ' . $url . ' failed with ' . $e->getMessage();
            // $this->log($message, 'err');
            throw new DoiClientException($message);
        }
        // Response Codes
        // 201 Created: operation successful
        // 401 Unauthorised: no login
        // 403 Forbidden: login problem, quota exceeded
        // 415 Wrong Content Type : Not including content type in the header.
        // 422 Unprocessable Entity : invalid XML
        if ($response->getStatusCode() != 201) {
            $message = 'unexpected DataCite MDS response code ' . $response->getStatusCode();
            // $this->log($message, 'err');
            throw new DoiClientException($message);
        }

        // Schritt 2: Register the DOI name
        // DOI und URL der Frontdoor des zugehörigen Dokuments übergeben: state findable
        $url = $this->serviceUrl . '/doi/' . $doiValue;
        try {
            $client = new Client(
                [
                    'auth' => [$this->username, $this->password],
                    'verify' => false,
                    'headers' => [
                        'Content-Type' => 'text/plain;charset=UTF-8',
                    ],
                ]
            );
            $data = "doi=$doiValue\nurl=" . $landingPageUrl;
            // $client->setRawData($data, 'text/plain;charset=UTF-8');
            $response = $client->request('PUT', $url, [
                'body' => $data,
                'headers' => [
                    'Content-Type' => 'text/plain;charset=UTF-8',
                ],
            ]);
        } catch (\Exception $e) {
            $message = 'request to ' . $url . ' failed with ' . $e->getMessage();
            // $this->log($message, 'err');
            throw new DoiClientException($message);
        }
        // Response Codes
        // 201 Created: operation successful
        // 400 Bad Request: request body must be exactly two lines: DOI and URL; wrong domain, wrong prefix;
        // 401 Unauthorised: no login
        // 403 Forbidden: login problem, quota exceeded
        // 412 Precondition failed: metadata must be uploaded first.
        
        // $this->log('DataCite response status code (expected 201): ' . $response->getStatus());
        // $this->log('DataCite response body: ' . $response->getBody());

        if ($response->getStatusCode() != 201) {
            $message = 'unexpected DataCite MDS response code ' . $response->getStatusCode();
            // $this->log($message, 'err');
            throw new DoiClientException($message);
        }
        return $response;
    }

    /* Response Status Codes
     * 200 OK: operation successful
     * 204 No Content : DOI is known to DataCite Metadata Store (MDS), but is not minted (or not resolvable e.g. due
     *     to handle's latency)
     * 401 Unauthorized: no login
     * 403 Login problem or dataset belongs to another party
     * 404 Not Found: DOI does not exist in our database (e.g. registration pending)
     *
     * @param $doiValue
     * @param $landingPageURL
     *
     * @return bool Methode liefert true, wenn die DOI erfolgreich registiert wurde und die Prüfung positiv ausfällt.
     *
     * @throws ClientException
     *
     */
    public function checkDoi($doiValue, $landingPageURL): bool
    {
        $response = null;
        $url = $this->serviceUrl . '/doi/' . $doiValue;
        try {
            $client = new Client([
                'base_uri' => $this->serviceUrl . '/doi/' . $doiValue,
                'auth' => [$this->username, $this->password],
                'verify' => false,
            ]);
            $response = $client->request('GET');
        } catch (\Exception $e) {
            $message = 'request to ' . $url . ' failed with ' . $e->getMessage();
            Log::error($message);
            // throw new \Exception($message);
            return false;
        }

        $statusCode = $response->getStatusCode();
        // in $body steht die URL zur Frontdoor, die mit der DOI verknüpft wurde
        $body = $response->getBody();

        // $this->log('DataCite response status code (expected 200): ' . $statusCode);
        // $this->log('DataCite response body (expected ' . $landingPageURL . '): ' . $body);

        return ($statusCode == 200 && $landingPageURL == $body);
    }

    public function getMetadataForDoi($doiValue)
    {
        $response = null;
        $url = $this->serviceUrl . '/metadata/' . $doiValue;
        try {
            $client = new Client([
                'auth' => [$this->username, $this->password],
                'base_uri' => $url,
                'verify' => false,
            ]);
            $response = $client->request('GET');
        } catch (\Exception $e) {
            $message = 'request to ' . $url . ' failed with ' . $e->getMessage();
            throw new DoiClientException($message);
        }
        // Response Codes
        // 200 OK: operation successful;
        // 204 No Content: the DOI is known to DataCite Metadata Store (MDS), but no metadata have been registered;
        // 401 Unauthorised: no login
        // 403 Forbidden: permission problem or dataset belongs to another party;
        // 404 Not Found: DOI does not exist in our database.
        // 422 Unprocessable Entity Metadata failed validation against the DataCite Schema.
        if ($response->getStatusCode() != 200) {
            $message = 'unexpected DataCite MDS response code ' . $response->getStatusCode();
            // $this->log($message, 'err');
            throw new DoiClientException($message);
        }
        return $response;
    }

    public function updateMetadataForDoi($doiValue, $newMeta)
    {
        $response = null;
        $url = $this->serviceUrl . '/metadata/' . $doiValue;       
        try {
            $client = new Client([
                'auth' => [$this->username, $this->password],
                // 'base_uri' => $url,
                'verify' => false,
                'headers' => [
                    'Content-Type' => 'application/xml;charset=UTF-8',
                ],
                // 'body' => $xmlMeta,
            ]);
            // Provide the body as a string.
            $response = $client->request('PUT', $url, [
                'body' => $newMeta,
            ]);
        } catch (\Exception $e) {
            $message = 'request to ' . $url . ' failed with ' . $e->getMessage();
            // $this->log($message, 'err');
            throw new DoiClientException($message);
        }
        // Response Codes
        // 201 Created: operation successful
        // 401 Unauthorised: no login
        // 403 Forbidden: login problem, quota exceeded
        // 415 Wrong Content Type : Not including content type in the header.
        // 422 Unprocessable Entity : invalid XML
        if ($response->getStatusCode() != 201) {
            $message = 'unexpected DataCite MDS response code ' . $response->getStatusCode();
            // $this->log($message, 'err');
            throw new DoiClientException($message);
        }
    }

    /**
     * Markiert den Datensatz zur übergebenen DOI als inaktiv - Status registered (not findable)
     *
     * @param $doiValue
     *
     * @throws ClientException
     */
    public function deleteMetadataForDoi($doiValue)
    {
        $response = null;
        $url = $this->serviceUrl . '/metadata/' . $doiValue;
        try {
            $client = new Client([
                'base_uri' => $url,
                'auth' => [$this->username, $this->password],
                'verify' => false,
            ]);
            $response = $client->request('DELETE');
        } catch (\Exception $e) {
            $message = 'request to ' . $url . ' failed with ' . $e->getMessage();
            Log::error($message, 'err');
            throw new DoiClientException($message);
        }

        // $this->log('DataCite response status code (expected 200): ' . $response->getStatus());

        if ($response->getStatusCode() != 200) {
            $message = 'unexpected DataCite MDS response code ' . $response->getStatusCode();
            Log::error($message, 'err');
            throw new DoiClientException($message);
        }
    }
}
