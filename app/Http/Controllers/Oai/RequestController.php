<?php
namespace App\Http\Controllers\Oai;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dataset;
use Illuminate\Support\Facades\Log;
use App\Book;

class RequestController extends Controller
{
    /**
     * Holds information about which dataset state aka server_state
     * are delivered out
     *
     * @var array
     */
    private $_deliveringDocumentStates = array('published', 'deleted');  // maybe deleted documents too
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
    protected $_xslt = null;

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
        $this->_xslt = new \DomDocument;
        $this->_xslt->load($stylesheet);
        $this->_proc->importStyleSheet($this->_xslt);
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
        $oaiRequest = $request->all();
        $safeRemoveParameters = array('module', 'controller', 'action', 'role');
        foreach ($safeRemoveParameters as $parameter) {
            unset($oaiRequest[$parameter]);
        }
        return $this->__handleRequest($oaiRequest);
    }



    private function __handleRequest(array $oaiRequest)
    {
         // Setup stylesheet
        $this->loadStyleSheet('oai-pmh.xslt');

        // Set response time
        $this->_proc->setParameter('', 'responseDate', date("Y-m-d\TH:i:s\Z"));

        // set OAI base url
        $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
        $this->_proc->setParameter('', 'baseURL', url('/') . $uri[0]);

        if (isset($oaiRequest['verb'])) {
            $this->_proc->setParameter('', 'oai_verb', $oaiRequest['verb']);
            if ($oaiRequest['verb'] == 'Identify') {
                $this->_handleIdentify();
            } elseif ($oaiRequest['verb'] == 'ListMetadataFormats') {
                $this->_handleListMetadataFormats();
            } elseif ($oaiRequest['verb'] == 'ListRecords') {
                $this->_handleListRecords($oaiRequest);
            } elseif ($oaiRequest['verb'] == 'ListIdentifiers') {
                $this->_handleListIdentifiers($oaiRequest);
            } elseif ($oaiRequest['verb'] == 'ListSets') {
                $this->_handleListSets($oaiRequest);
            } else {
                $this->_handleIllegalVerb();
            }
        } else {
            $oaiRequest['verb'] = 'Identify';
            $this->_proc->setParameter('', 'oai_verb', $oaiRequest['verb']);
            $this->doc = $this->_handleIdentify();
        }
        
        //$xml = $this->_xml->saveXML();
        $xml = $this->_proc->transformToXML($this->_xml);

        //$xml = $this->doc->asXML();
        return response($xml)//->view('rss', array('rss'=>$this->rss))
            ->header('Content-Type', 'application/xml')
            ->header('charset', 'utf-8');
    }

    /**
     * Implements response for OAI-PMH verb 'Identify'.
     *
     * @return void
     */
    private function _handleIdentify()
    {
        $email = "repository@geologie.ac.at";
        $repositoryName = "Data Research Repository";
        $repIdentifier = "rdr.gba.ac.at";
        //$sampleIdentifier = $this->_configuration->getSampleIdentifier();
        $earliestDateFromDb = Dataset::earliestPublicationDate();

        // set parameters for oai-pmh.xslt
        $this->_proc->setParameter('', 'email', $email);
        $this->_proc->setParameter('', 'repositoryName', $repositoryName);
        $this->_proc->setParameter('', 'repIdentifier', $repIdentifier);
        //$this->_proc->setParameter('', 'sampleIdentifier', $sampleIdentifier);
        $this->_proc->setParameter('', 'earliestDatestamp', $earliestDateFromDb);
        $this->_xml->appendChild($this->_xml->createElement('Documents'));
    }

    


    /**
     * Implements response for OAI-PMH verb 'ListMetadataFormats'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function _handleListMetadataFormats()
    {
        $this->_xml->appendChild($this->_xml->createElement('Documents'));
    }

    /**
     * Implements response for OAI-PMH verb 'ListRecords'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function _handleListRecords($oaiRequest)
    {
        $maxRecords = 20;//$this->_configuration->getMaxListRecords();
        $this->_handlingOfLists($oaiRequest, $maxRecords);
    }

    /**
     * Implements response for OAI-PMH verb 'ListIdentifiers'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function _handleListIdentifiers(array &$oaiRequest)
    {
        $maxIdentifier = 20;//$this->_configuration->getMaxListIdentifiers();
        $this->_handlingOfLists($oaiRequest, $maxIdentifier);
    }

    /**
     * Implements response for OAI-PMH verb 'ListSets'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function _handleListSets()
    {
        $repIdentifier = "rdr.gba.ac.at";
        $this->_proc->setParameter('', 'repIdentifier', $repIdentifier);
        $this->_xml->appendChild($this->_xml->createElement('Documents'));

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


    private function _handleIllegalVerb()
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
    private function _handlingOfLists(array &$oaiRequest, $maxRecords)
    {
        if (true === empty($maxRecords)) {
            $maxRecords = 100;
        }
        $repIdentifier = "rdr.gba.ac.at";
        $this->_proc->setParameter('', 'repIdentifier', $repIdentifier);
        $this->_xml->appendChild($this->_xml->createElement('Documents'));
        
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

        // no resumptionToken is given
        $finder = Dataset::query();
        // add server state restrictions
        $finder->whereIn('server_state', $this->_deliveringDocumentStates);
        if (array_key_exists('set', $oaiRequest)) {
            $setarray = explode(':', $oaiRequest['set']);
            if ($setarray[0] == 'doc-type') {
                if (count($setarray) === 2 and !empty($setarray[1])) {
                    $finder->where('type', $setarray[1]);
                }
            }
        }

        $totalIds = $finder->count();
        $reldocIds = $finder->pluck('id')->toArray();
       
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
        $this->_addLandingPageAttribute($domNode, $dataset->id);

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
        $this->_addSpecInformation($node, 'doc-type:' . $dataset->type);
        //$this->_addSpecInformation($node, 'bibliography:' . 'false');

        $this->_xml->documentElement->appendChild($node);
    }

    /**
     * Add the landingpage attribute to Rdr_Dataset XML output.
     *
     * @param \DOMNode $document Rdr_Dataset XML serialisation
     * @param string  $docid    Id of the dataset
     * @return void
     */
    private function _addLandingPageAttribute(\DOMNode $document, $dataid)
    {
        $url = route('document.show', $dataid);

        $owner = $document->ownerDocument;
        $attr = $owner->createAttribute('landingpage');
        $attr->appendChild($owner->createTextNode($url));
        $document->appendChild($attr);
    }

    private function _addSpecInformation(\DOMNode $document, $information)
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
        if (!in_array($dataset->server_state, $this->_deliveringDocumentStates)) {
            $message = 'Trying to get a document in server state "' . $dataset->server_state . '"';
            //Zend_Registry::get('Zend_Log')->err($message);
            Log::error("server state: $message");
            throw new \Exception($message);
        }

        $dataset->fetchValues();
        $xmlModel = new \App\Library\Xml\XmlModel();
        $xmlModel->setModel($dataset);
        $xmlModel->excludeEmptyFields();
        $xmlModel->setXmlCache(new \App\XmlCache());
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

        $finder = new \App\DatasetFinder();
        $finder->setServerState('published');
        foreach ($finder->groupedTypesPlusCount() as $doctype => $row) {
            if (0 == preg_match("/^$setSpecPattern$/", $doctype)) {
                $msg = "Invalid SetSpec (doctype='" . $doctype . "')."
                    . " Allowed characters are [$setSpecPattern].";
                Log::error("OAI-PMH: $msg");
                continue;
            }

            $setSpec = 'doc-type:' . $doctype;
            // $count = $row['count'];
            $sets[$setSpec] = "Set for document type '$doctype'";
        }

        return $sets;
    }

    private function handleIdentifyOld()
    {
        //$earliestDateFromDb = Opus_Document::getEarliestPublicationDate();
        //$earliestDateFromDb = Dataset::select('server_date_created')->orderBy('server_date_created', 'desc')->first()->toDateTimeString();
        $earliestDateFromDb = Dataset::earliestPublicationDate();

        $sxe = new \SimpleXMLElement('<?xml version="1.0"?><?xml-stylesheet type="text/xsl" href="xsl/oai2.xslt"?><OAI-PMH/>');
        $sxe->addAttribute('xmlns', 'http://www.openarchives.org/OAI/2.0/');
        $sxe->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $sxe->addAttribute('xmlns:mml', 'http://www.w3.org/1998/Math/MathML');
        $sxe->addAttribute('xsi:schemaLocation', 'http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd');
        $sxe->addChild('responseDate', date("Y-m-d\TH:i:s\Z"));

        $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
        $requestChild = $sxe->addChild('request', url('/') . $uri[0]);
        $requestChild->addAttribute('verb', 'Identify');

        $identify = $sxe->addChild('Identify');
        $identify->addChild('repositoryName', "Data Research Repository");
        $identify->addChild('baseURL', "http://rdr.gba.geolba.ac.at/");
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
