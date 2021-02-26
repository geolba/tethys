<?php

namespace App\Http\Controllers;

use App\Interfaces\DOIInterface;
use App\Models\Dataset;
use App\Models\DatasetIdentifier;
use Illuminate\Http\Request;
use App\Models\Oai\OaiModelError;
use App\Exceptions\OaiModelException;
use Illuminate\Support\Facades\Log;

class DoiController extends Controller
{
    protected $doiService;
    protected $LaudatioUtils;

     /**
     * Holds xml representation of document information to be processed.
     *
     * @var \DomDocument  Defaults to null.
     */
    protected $xml = null;
     /**
     * Holds the stylesheet for the transformation.
     *
     * @var \DomDocument  Defaults to null.
     */
    protected $xslt = null;

    /**
     * Holds the xslt processor.
     *
     * @var \XSLTProcessor  Defaults to null.
     */
    protected $proc = null;

    /**
     * DOIController constructor.
     * @param DoiInterface $DOIService
     */
    public function __construct(DoiInterface $DoiClient)
    {
        $this->doiClient = $DoiClient;
       
        $this->xml = new \DomDocument();
        $this->proc = new \XSLTProcessor();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataId = $request->input('publish_id');
        
        // Setup stylesheet
        $this->loadStyleSheet(public_path() .'\prefixes\doi_datacite.xslt');

       // set timestamp
        $date = new \DateTime();
        $unixTimestamp = $date->getTimestamp();
        $this->proc->setParameter('', 'unixTimestamp', $unixTimestamp);

        $prefix = config('tethys.datacite_prefix');
        $this->proc->setParameter('', 'prefix', $prefix);

        $repIdentifier = "tethys";
        $this->proc->setParameter('', 'repIdentifier', $repIdentifier);

        $this->xml->appendChild($this->xml->createElement('Datasets'));
        $dataset = Dataset::where('publish_id', '=', $dataId)->firstOrFail();
        if (is_null($dataset)) {
            throw new OaiModelException('Dataset is not available for registering DOI!', OaiModelError::NORECORDSMATCH);
        }
        $dataset->fetchValues();
        $xmlModel = new \App\Library\Xml\XmlModel();
        $xmlModel->setModel($dataset);
        $xmlModel->excludeEmptyFields();
        $cache = ($dataset->xmlCache) ? $dataset->xmlCache : new \App\Models\XmlCache();
        $xmlModel->setXmlCache($cache);
        $domNode = $xmlModel->getDomDocument()->getElementsByTagName('Rdr_Dataset')->item(0);
        $node = $this->xml->importNode($domNode, true);
        $this->addSpecInformation($node, 'data-type:' . $dataset->type);

        $this->xml->documentElement->appendChild($node);
        $xmlMeta = $this->proc->transformToXML($this->xml);
        Log::alert($xmlMeta);
       //create doiValue and correspunfing landingpage of tehtys
        $doiValue = $prefix . '/tethys.' . $dataset->publish_id;
        $appUrl = config('app.url');
        $landingPageUrl = $appUrl . "/dataset/" . $dataset->publish_id;
        $response = $this->doiClient->registerDoi($doiValue, $xmlMeta, $landingPageUrl);
        if ($response->getStatusCode() == 201) {
            $doi = new DatasetIdentifier();
            $doi['value'] = $doiValue;
            $doi['dataset_id'] = $dataset->id;
            $doi['type'] = "doi";
            $doi['status'] = "registered";
            $doi['registration_ts'] = now();
            // $doi->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DatasetIdentifier  $doi
     * @return \Illuminate\Http\Response
     */
    public function show(DatasetIdentifier $doi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DatasetIdentifier  $doi
     * @return \Illuminate\Http\Response
     */
    public function edit(DatasetIdentifier $doi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DatasetIdentifier  $doi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DatasetIdentifier $doi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DatasetIdentifier  $doi
     * @return \Illuminate\Http\Response
     */
    public function destroy(DatasetIdentifier $doi)
    {
        //
    }

   

    
    /**
     * Load an xslt stylesheet.
     *
     * @return void
     */
    private function loadStyleSheet($stylesheet)
    {
        $this->xslt = new \DomDocument;
        $this->xslt->load($stylesheet);
        $this->proc->importStyleSheet($this->xslt);
        if (isset($_SERVER['HTTP_HOST'])) {
            $this->proc->setParameter('', 'host', $_SERVER['HTTP_HOST']);
        }
        //$this->proc->setParameter('', 'server', $this->getRequest()->getBaseUrl());
    }

    private function addSpecInformation(\DOMNode $document, $information)
    {
        $setSpecAttribute = $this->xml->createAttribute('Value');
        $setSpecAttributeValue = $this->xml->createTextNode($information);
        $setSpecAttribute->appendChild($setSpecAttributeValue);

        $setSpecElement = $this->xml->createElement('SetSpec');
        //$setSpecElement =new \DOMElement("SetSpec");
        $setSpecElement->appendChild($setSpecAttribute);
        $document->appendChild($setSpecElement);
    }
}
