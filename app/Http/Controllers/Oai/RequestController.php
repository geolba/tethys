<?php
namespace App\Http\Controllers\Oai;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Support\Facades\Log;
use App\Exceptions\OaiModelException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Oai\OaiModelError;
use App\Models\Oai\ResumptionToken;
 
class RequestController extends Controller
{
    /**
     * Holds information about which dataset state aka server_state
     * are delivered out
     *
     * @var array
     */
    private $deliveringDocumentStates = array('published', 'deleted');  // maybe deleted documents too
    private $xMetaDissRestriction = array('doctoralthesis', 'habilitation');
    const SET_SPEC_PATTERN = '[A-Za-z0-9\-_\.!~\*\'\(\)]+';


    /**
     * Holds xml representation of document information to be processed.
     *
     * @var \DomDocument  Defaults to null.
     */
    protected $_xml = null;

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
    protected $_proc = null;

    /**
     * Load an xslt stylesheet.
     *
     * @return void
     */
    private function loadStyleSheet($stylesheet)
    {
        $this->xslt = new \DomDocument;
        $this->xslt->load($stylesheet);
        $this->_proc->importStyleSheet($this->xslt);
        if (isset($_SERVER['HTTP_HOST'])) {
            $this->_proc->setParameter('', 'host', $_SERVER['HTTP_HOST']);
        }
        //$this->_proc->setParameter('', 'server', $this->getRequest()->getBaseUrl());
    }

    public function __construct()
    {
        //$this->middleware('auth');
        // Initialize member variables.
        $this->_xml = new \DomDocument;
        $this->_proc = new \XSLTProcessor;
    }

    public function index(Request $request)
    {
        // to handle POST and GET Request, take any given parameter
        $oaiRequest = $request->all();
        $safeRemoveParameters = array('module', 'controller', 'action', 'role');
        foreach ($safeRemoveParameters as $parameter) {
            unset($oaiRequest[$parameter]);
        }
        try {
            $this->__handleRequest($oaiRequest);
        } catch (OaiModelException $e) {
            $errorCode = OaiModelError::mapCode($e->getCode());
            //$this->getLogger()->err($errorCode);
            $this->_proc->setParameter('', 'oai_error_code', $errorCode);
            //$this->getLogger()->err($e->getMessage());
            $this->_proc->setParameter('', 'oai_error_message', htmlentities($e->getMessage()));
        } catch (Exception $e) {
            //$this->getLogger()->err($e);
            $this->_proc->setParameter('', 'oai_error_code', 'unknown');
            $this->_proc->setParameter('', 'oai_error_message', 'An internal error occured.');
            //$this->getResponse()->setHttpResponseCode(500);
        }
        // $xml = $this->_xml->saveXML();
        $xml = $this->_proc->transformToXML($this->_xml);

         //$xml = $this->doc->asXML();
         return response($xml)//->view('rss', array('rss'=>$this->rss))
             ->header('Content-Type', 'application/xml')
             ->header('charset', 'utf-8');
    }



    private function __handleRequest(array $oaiRequest)
    {
         // Setup stylesheet
        $this->loadStyleSheet('datasetxml2oai-pmh.xslt');

        // Set response time
        $this->_proc->setParameter('', 'responseDate', date("Y-m-d\TH:i:s\Z"));

        // set OAI base url
        $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
        $this->_proc->setParameter('', 'baseURL', url('/') . $uri[0]);

        if (isset($oaiRequest['verb'])) {
            $this->_proc->setParameter('', 'oai_verb', $oaiRequest['verb']);
            if ($oaiRequest['verb'] == 'Identify') {
                $this->handleIdentify();
            } elseif ($oaiRequest['verb'] == 'ListMetadataFormats') {
                $this->handleListMetadataFormats();
            } elseif ($oaiRequest['verb'] == 'ListRecords') {
                $this->handleListRecords($oaiRequest);
            } elseif ($oaiRequest['verb'] == 'GetRecord') {
                $this->handleGetRecord($oaiRequest);
            } elseif ($oaiRequest['verb'] == 'ListIdentifiers') {
                $this->handleListIdentifiers($oaiRequest);
            } elseif ($oaiRequest['verb'] == 'ListSets') {
                $this->handleListSets($oaiRequest);
            } else {
                $this->handleIllegalVerb();
            }
        } else {
            $oaiRequest['verb'] = 'Identify';
            $this->_proc->setParameter('', 'oai_verb', $oaiRequest['verb']);
            $this->doc = $this->handleIdentify();
        }
    }

    /**
     * Implements response for OAI-PMH verb 'Identify'.
     *
     * @return void
     */
    private function handleIdentify()
    {
        $email = "repository@geologie.ac.at";
        $repositoryName = "TETHYS RDR";
        $repIdentifier = "tethys.geologie.ac.at";
        $sampleIdentifier = "oai:" . $repIdentifier . ":27";//$this->_configuration->getSampleIdentifier();
        $earliestDateFromDb = optional(Dataset::earliestPublicationDate())->server_date_published;

        // set parameters for oai-pmh.xslt
        $this->_proc->setParameter('', 'email', $email);
        $this->_proc->setParameter('', 'repositoryName', $repositoryName);
        $this->_proc->setParameter('', 'repIdentifier', $repIdentifier);
        $this->_proc->setParameter('', 'sampleIdentifier', $sampleIdentifier);
        $this->_proc->setParameter('', 'earliestDatestamp', $earliestDateFromDb);
        $this->_xml->appendChild($this->_xml->createElement('Datasets'));
    }

    /**
     * Implements response for OAI-PMH verb 'GetRecord'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function handleGetRecord(array &$oaiRequest)
    {
        // Identifier references metadata Urn, not plain Id!
        // Currently implemented as 'oai:foo.bar.de:{docId}' or 'urn:nbn...-123'
        $dataId = $this->getDocumentIdByIdentifier($oaiRequest['identifier']);

        $dataset = null;
        try {
            //$dataset = new Opus_Document($docId);
            $dataset = Dataset::findOrFail($dataId);
        } catch (ModelNotFoundException  $ex) {
            throw new OaiModelException(
                'The value of the identifier argument is unknown or illegal in this repository.',
                OaiModelError::IDDOESNOTEXIST
            );
        }

        $metadataPrefix = null;
        if (true === array_key_exists('metadataPrefix', $oaiRequest)) {
            $metadataPrefix = $oaiRequest['metadataPrefix'];
        }
        $this->_proc->setParameter('', 'oai_metadataPrefix', $metadataPrefix);

        // do not deliver datasets which are restricted by document state
        if (is_null($dataset)
            //or (false === in_array($dataset->getServerState(), $this->_deliveringDocumentStates))
            or  (false === $dataset->whereIn('server_state', $this->deliveringDocumentStates))
            or (false === $dataset->hasEmbargoPassed())) {
            throw new OaiModelException('Document is not available for OAI export!', OaiModelError::NORECORDSMATCH);
        }

        $this->_xml->appendChild($this->_xml->createElement('Datasets'));
        $this->createXmlRecord($dataset);
    }

    /**
     * Retrieve a document id by an oai identifier.
     *
     * @param string $oaiIdentifier
     * @result int
     */
    private function getDocumentIdByIdentifier($oaiIdentifier)
    {
        $identifierParts = explode(":", $oaiIdentifier);

        $dataId = null;
        switch ($identifierParts[0]) {
            case 'oai':
                if (isset($identifierParts[2])) {
                    $dataId = $identifierParts[2];
                }
                break;
            default:
                throw new OaiModelException(
                    'The prefix of the identifier argument is unknown.',
                    OaiModelError::BADARGUMENT
                );
                break;
        }

        if (empty($dataId) or !preg_match('/^\d+$/', $dataId)) {
            throw new Oai_Model_Exception(
                'The value of the identifier argument is unknown or illegal in this repository.',
                Oai_Model_Error::IDDOESNOTEXIST
            );
        }

        return $dataId;
    }

    


    /**
     * Implements response for OAI-PMH verb 'ListMetadataFormats'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function handleListMetadataFormats()
    {
        $this->_xml->appendChild($this->_xml->createElement('Datasets'));
    }

    /**
     * Implements response for OAI-PMH verb 'ListRecords'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function handleListRecords($oaiRequest)
    {
        $maxRecords = 30;//$this->_configuration->getMaxListRecords();
        $this->handlingOfLists($oaiRequest, $maxRecords);
    }

    /**
     * Implements response for OAI-PMH verb 'ListIdentifiers'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function handleListIdentifiers(array &$oaiRequest)
    {
        $maxIdentifier = 5;//$this->_configuration->getMaxListIdentifiers();
        $this->handlingOfLists($oaiRequest, $maxIdentifier);
    }

    /**
     * Implements response for OAI-PMH verb 'ListSets'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function handleListSets()
    {
        $repIdentifier = "tethys.geologie.ac.at";
        $this->_proc->setParameter('', 'repIdentifier', $repIdentifier);
        $this->_xml->appendChild($this->_xml->createElement('Datasets'));

        //$oaiSets = new Oai_Model_Sets();
        $sets = array(
            'bibliography:true' => 'Set for bibliographic entries',
            'bibliography:false' => 'Set for non-bibliographic entries',
        );
        $sets = array_merge(
            $sets,
            $this->getSetsForDocumentTypes()
        );
        //$sets = $this->getSetsForDocumentTypes();

        foreach ($sets as $type => $name) {
            $opusDoc = $this->_xml->createElement('Rdr_Sets');
            $typeAttr = $this->_xml->createAttribute('Type');
            $typeValue = $this->_xml->createTextNode($type);
            $typeAttr->appendChild($typeValue);
            $opusDoc->appendChild($typeAttr);
            $nameAttr = $this->_xml->createAttribute('TypeName');
            $nameValue = $this->_xml->createTextNode($name);
            $nameAttr->appendChild($nameValue);
            $opusDoc->appendChild($nameAttr);
            $this->_xml->documentElement->appendChild($opusDoc);
        }
    }


    private function handleIllegalVerb()
    {
        $this->_proc->setParameter('', 'oai_error_code', 'badVerb');
        $this->_proc->setParameter('', 'oai_error_message', 'The verb provided in the request is illegal.');
    }


    /**
     * Helper method for handling lists.
     *
     * @param array $oaiRequest query parameter
     * @param mixed $maxRecords max count of records
     *
     * @return void
     */
    private function handlingOfLists(array &$oaiRequest, $maxRecords)
    {
        if (true === empty($maxRecords)) {
            $maxRecords = 100;
        }

        $repIdentifier = "tethys.geologie.ac.at";
        $tokenTempPath = storage_path('app/resumption'); //$this->_configuration->getResumptionTokenPath();

        $this->_proc->setParameter('', 'repIdentifier', $repIdentifier);
        $this->_xml->appendChild($this->_xml->createElement('Datasets'));
        
        // do some initialisation
        $cursor = 0;
        //$totalIds = 0;
        $start = $maxRecords + 1;
        $reldocIds = array();

        $metadataPrefix = null;
        if (true === array_key_exists('metadataPrefix', $oaiRequest)) {
            $metadataPrefix = $oaiRequest['metadataPrefix'];
        }
        $this->_proc->setParameter('', 'oai_metadataPrefix', $metadataPrefix);

        // parameter resumptionToken is given
        if (false === empty($oaiRequest['resumptionToken'])) {
            $tokenWorker = new ResumptionToken();
            $resParam = $oaiRequest['resumptionToken'];
        } else {
            // no resumptionToken is given
            $finder = Dataset::query();
            // add server state restrictions
            $finder->whereIn('server_state', $this->deliveringDocumentStates);
            if (array_key_exists('set', $oaiRequest)) {
                $setarray = explode(':', $oaiRequest['set']);
                if ($setarray[0] == 'data-type') {
                    if (count($setarray) === 2 and !empty($setarray[1])) {
                        $finder->where('type', $setarray[1]);
                    }
                }
            }
            $totalIds = $finder->count();
            $reldocIds = $finder->pluck('id')->toArray();
        }

       
          // handling of document ids
        $restIds = $reldocIds;
        $workIds = array_splice($restIds, 0, $maxRecords);
        //foreach ($datasets as $dataset)
        foreach ($workIds as $dataId) {
            $dataset = Dataset::findOrFail($dataId);
            $this->createXmlRecord($dataset);
        }
    }

    private function createXmlRecord(Dataset $dataset)
    {
        //$node = $this->_xml->createElement('Rdr_Dataset');
        $domNode = $this->getDatasetXmlDomNode($dataset);
          // add frontdoor url
        $this->addLandingPageAttribute($domNode, $dataset->id);

        // add access rights to element
        //$this->_addAccessRights($domNode, $dataset);

        $node = $this->_xml->importNode($domNode, true);

        //$node->setAttribute("Id",  $dataset->id);
        //$node->setAttribute("ServerState",  $dataset->server_state);

        ////$child =  new \DOMElement("ServerDateModified");
        //$child =   $this->_xml->createElement('ServerDateModified');
        //$child->setAttribute("Year",  $dataset->server_date_modified->format('Y'));
        //$child->setAttribute("Month",  $dataset->server_date_modified->month);
        //$child->setAttribute("Day",  $dataset->server_date_modified->day);
        //$node->appendChild($child);

        //$type = $dataset->type;
        $this->addSpecInformation($node, 'data-type:' . $dataset->type);
        //$this->addSpecInformation($node, 'bibliography:' . 'false');

        $this->_xml->documentElement->appendChild($node);
    }

    /**
     * Add the landingpage attribute to Rdr_Dataset XML output.
     *
     * @param \DOMNode $document Rdr_Dataset XML serialisation
     * @param string  $docid    Id of the dataset
     * @return void
     */
    private function addLandingPageAttribute(\DOMNode $document, $dataid)
    {
        $url = route('frontend.dataset.show', $dataid);

        $owner = $document->ownerDocument;
        $attr = $owner->createAttribute('landingpage');
        $attr->appendChild($owner->createTextNode($url));
        $document->appendChild($attr);
    }

    private function addSpecInformation(\DOMNode $document, $information)
    {
        $setSpecAttribute = $this->_xml->createAttribute('Value');
        $setSpecAttributeValue = $this->_xml->createTextNode($information);
        $setSpecAttribute->appendChild($setSpecAttributeValue);

        $setSpecElement = $this->_xml->createElement('SetSpec');
         //$setSpecElement =new \DOMElement("SetSpec");
        $setSpecElement->appendChild($setSpecAttribute);
        $document->appendChild($setSpecElement);
    }

    private function getDatasetXmlDomNode($dataset)
    {
        if (!in_array($dataset->server_state, $this->deliveringDocumentStates)) {
            $message = 'Trying to get a document in server state "' . $dataset->server_state . '"';
            //Zend_Registry::get('Zend_Log')->err($message);
            Log::error("server state: $message");
            throw new \Exception($message);
        }

        $dataset->fetchValues();
        $xmlModel = new \App\Library\Xml\XmlModel();
        $xmlModel->setModel($dataset);
        $xmlModel->excludeEmptyFields();
        $cache = ($dataset->xmlCache) ?  $dataset->xmlCache : new \App\Models\XmlCache();
        $xmlModel->setXmlCache($cache);
        return $xmlModel->getDomDocument()->getElementsByTagName('Rdr_Dataset')->item(0);
    }

    /**
     * Returns oai sets for document types.
     * @return array
     */
    private function getSetsForDocumentTypes()
    {
        $setSpecPattern = self::SET_SPEC_PATTERN;
        $sets = array();

        $finder = new \App\Models\DatasetFinder();
        $finder->setServerState('published');
        foreach ($finder->groupedTypesPlusCount() as $doctype => $row) {
            if (0 == preg_match("/^$setSpecPattern$/", $doctype)) {
                $msg = "Invalid SetSpec (doctype='" . $doctype . "')."
                    . " Allowed characters are [$setSpecPattern].";
                Log::error("OAI-PMH: $msg");
                continue;
            }

            $setSpec = 'data-type:' . $doctype;
            // $count = $row['count'];
            $sets[$setSpec] = "Set for document type '$doctype'";
        }

        return $sets;
    }

    private function handleIdentifyOld()
    {
        //$earliestDateFromDb = Opus_Document::getEarliestPublicationDate();
        // $earliestDateFromDb = Dataset::select('server_date_created')
        // ->orderBy('server_date_created', 'desc')
        // ->first()->toDateTimeString();
        $earliestDateFromDb = Dataset::earliestPublicationDate();

        $sxe = new \SimpleXMLElement(
            '<?xml version="1.0"?><?xml-stylesheet type="text/xsl" href="xsl/oai2.xslt"?><OAI-PMH/>'
        );

        $sxe->addAttribute('xmlns', 'http://www.openarchives.org/OAI/2.0/');
        $sxe->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $sxe->addAttribute('xmlns:mml', 'http://www.w3.org/1998/Math/MathML');
        $sxe->addAttribute(
            'xsi:schemaLocation',
            'http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd'
        );
        $sxe->addChild('responseDate', date("Y-m-d\TH:i:s\Z"));

        $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
        $requestChild = $sxe->addChild('request', url('/') . $uri[0]);
        $requestChild->addAttribute('verb', 'Identify');

        $identify = $sxe->addChild('Identify');
        $identify->addChild('repositoryName', "Data Research Repository");
        $identify->addChild('baseURL', "http://tethys.geologie.ac.at/");
        $identify->addChild('protocolVersion', '2.0');
        $identify->addChild('adminEmail', 'repository@geologie.ac.at');
        //$identify->addChild('earliestDatestamp', '2017-04-07');
        $identify->addChild('earliestDatestamp', $earliestDateFromDb);
        $identify->addChild('deletedRecord', 'persistent');

        //$description = $identify->addChild('description');
        //$oaiIdentifier =  $description->addChild('oai-identifier');
        //$oaiIdentifier->addAttribute('xmlns', 'http://www.openarchives.org/OAI/2.0/oai-identifier');
        //$oaiIdentifier->addAttribute('xsi:schemaLocation', 'http://www.openarchives.org/OAI/2.0/oai-identifier');
        //$oaiIdentifier->addChild('scheme', 'oai');

        return $sxe;
    }
}
