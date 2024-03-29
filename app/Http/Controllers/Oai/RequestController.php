<?php
namespace App\Http\Controllers\Oai;

use App\Exceptions\OaiModelException;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\Oai\Configuration as OaiModelConfiguration;
use App\Models\Oai\OaiModelError;
use App\Models\Oai\ResumptionTokens;
use App\Models\Oai\ResumptionToken;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \Exception;
// use Illuminate\Support\Carbon;
use \Illuminate\Support\Carbon;

class RequestController extends Controller
{
    /**
     * Holds information about which dataset state aka server_state
     * are delivered out
     *
     * @var array
     */
    private $deliveringDocumentStates = array('published', 'deleted'); // maybe deleted documents too
    //private $xMetaDissRestriction = array('doctoralthesis', 'habilitation');
    const SET_SPEC_PATTERN = '[A-Za-zäöüÄÖÜß0-9\-_\.!~\*\'\(\)]+';

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

    public function __construct()
    {
        //$this->middleware('auth');
        // Initialize member variables.
        $this->xml = new \DomDocument();
        $this->proc = new \XSLTProcessor();

        $this->configuration = new OaiModelConfiguration();
    }

    public function index(Request $request)
    {
        // to handle POST and GET Request, take any given parameter
        $oaiRequest = $request->all();
        if ($oaiRequest == null) {
            return view('oai.index');
        }
        $safeRemoveParameters = array('module', 'controller', 'action', 'role');
        foreach ($safeRemoveParameters as $parameter) {
            unset($oaiRequest[$parameter]);
        }
        try {
            $this->__handleRequest($oaiRequest);
        } catch (OaiModelException $e) {
            $errorCode = OaiModelError::mapCode($e->getCode());
            //$this->getLogger()->err($errorCode);
            $this->proc->setParameter('', 'oai_error_code', $errorCode);
            //$this->getLogger()->err($e->getMessage());
            $this->proc->setParameter('', 'oai_error_message', htmlentities($e->getMessage()));
        } catch (Exception $e) {
            //$this->getLogger()->err($e);
            $this->proc->setParameter('', 'oai_error_code', 'unknown');
            $this->proc->setParameter('', 'oai_error_message', 'An internal error occured.');
            //$this->getResponse()->setHttpResponseCode(500);
        }
        // $xml = $this->xml->saveXML();
        $xml = $this->proc->transformToXML($this->xml);

        //$xml = $this->doc->asXML();
        return response($xml) //->view('rss', array('rss'=>$this->rss))
        ->header('Content-Type', 'application/xml')
            ->header('charset', 'utf-8')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET');
    }

    private function __handleRequest(array $oaiRequest)
    {
        // Setup stylesheet
        $this->loadStyleSheet('datasetxml2oai-pmh.xslt');

        // Set response time
        $this->proc->setParameter('', 'responseDate', date("Y-m-d\TH:i:s\Z"));
        // set timestamp
        $date = new \DateTime();
        $unixTimestamp = $date->getTimestamp();
        $this->proc->setParameter('', 'unixTimestamp', $unixTimestamp);

        // set OAI base url
        $frontend = config('tethys.frontend');
        $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
        // $this->proc->setParameter('', 'baseURL', url('/') . $uri[0]);
        $this->proc->setParameter('', 'baseURL', $frontend. '/oai');// . $uri[0]);
        $this->proc->setParameter('', 'repURL', url('/'));
        $this->proc->setParameter('', 'downloadLink', url('/') . '/file/download/');
        $this->proc->setParameter('', 'doiLink', 'https://doi.org/');
        $this->proc->setParameter('', 'doiPrefix', 'info:eu-repo/semantics/altIdentifier/doi/');

        // $resumptionPath = $this->configuration->getResumptionTokenPath();

        if (isset($oaiRequest['verb'])) {
            $this->proc->setParameter('', 'oai_verb', $oaiRequest['verb']);
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
            // $oaiRequest['verb'] = 'Identify';
            // $this->proc->setParameter('', 'oai_verb', $oaiRequest['verb']);
            // $this->doc = $this->handleIdentify();
            throw new OaiModelException('The verb provided in the request is illegal.', OaiModelError::BADVERB);
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
        $repositoryName = "Tethys RDR";
        $repIdentifier = "tethys.at";
        $sampleIdentifier = "oai:" . $repIdentifier . ":1"; //$this->_configuration->getSampleIdentifier();
        $earliestDateFromDb = Dataset::earliestPublicationDate() != null ?
        Dataset::earliestPublicationDate()->server_date_published->format('Y-m-d\TH:i:s\Z') : null;

        // set parameters for oai-pmh.xslt
        $this->proc->setParameter('', 'email', $email);
        $this->proc->setParameter('', 'repositoryName', $repositoryName);
        $this->proc->setParameter('', 'repIdentifier', $repIdentifier);
        $this->proc->setParameter('', 'sampleIdentifier', $sampleIdentifier);
        $this->proc->setParameter('', 'earliestDatestamp', $earliestDateFromDb);
        $this->xml->appendChild($this->xml->createElement('Datasets'));
    }

    /**
     * Implements response for OAI-PMH verb 'GetRecord'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function handleGetRecord(array &$oaiRequest)
    {
        $repIdentifier = "tethys.at";
        $this->proc->setParameter('', 'repIdentifier', $repIdentifier);

        // Identifier references metadata Urn, not plain Id!
        // Currently implemented as 'oai:foo.bar.de:{docId}' or 'urn:nbn...-123'
        if (!array_key_exists('identifier', $oaiRequest)) {
            throw new OaiModelException(
                'The prefix of the identifier argument is unknown.',
                OaiModelError::BADARGUMENT
            );
        }
        $dataId = $this->getDocumentIdByIdentifier($oaiRequest['identifier']);

        $dataset = null;
        try {
            //$dataset = new Opus_Document($docId);
            //$dataset = Dataset::findOrFail($publishId);
            $dataset = Dataset::where('publish_id', '=', $dataId)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new OaiModelException(
                'The value of the identifier argument is unknown or illegal in this repository.',
                OaiModelError::IDDOESNOTEXIST
            );
        }

        $metadataPrefix = null;
        if (true === array_key_exists('metadataPrefix', $oaiRequest)) {
            $metadataPrefix = $oaiRequest['metadataPrefix'];
        } else {
            throw new OaiModelException(
                'The prefix of the metadata argument is unknown.',
                OaiModelError::BADARGUMENT
            );
        }
        $this->proc->setParameter('', 'oai_metadataPrefix', $metadataPrefix);

        // do not deliver datasets which are restricted by document state
        if (is_null($dataset)
            //or (false === in_array($dataset->getServerState(), $this->_deliveringDocumentStates))
             or (false === $dataset->whereIn('server_state', $this->deliveringDocumentStates))
            //or (false === $dataset->hasEmbargoPassed())
        ) {
            throw new OaiModelException('Document is not available for OAI export!', OaiModelError::NORECORDSMATCH);
        }

        $this->xml->appendChild($this->xml->createElement('Datasets'));
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
            throw new OaiModelException(
                'The value of the identifier argument is unknown or illegal in this repository.',
                OaiModelError::IDDOESNOTEXIST
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
        $this->xml->appendChild($this->xml->createElement('Datasets'));
    }

    /**
     * Implements response for OAI-PMH verb 'ListRecords'.
     *
     * @param  array &$oaiRequest Contains full request information
     * @return void
     */
    private function handleListRecords(array &$oaiRequest)
    {
        //$maxRecords = 30; //$this->_configuration->getMaxListRecords();
        $maxRecords = $this->configuration->getMaxListRecords();
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
        //$maxIdentifier = 5; //$this->_configuration->getMaxListIdentifiers();
        $maxIdentifier = $this->configuration->getMaxListIdentifiers();
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
        $repIdentifier = "tethys.at";
        $this->proc->setParameter('', 'repIdentifier', $repIdentifier);
        $this->xml->appendChild($this->xml->createElement('Datasets'));

        //$oaiSets = new Oai_Model_Sets();
        $sets = array(
            'open_access' => 'Set for open access licenses',
            // 'bibliography:true' => 'Set for bibliographic entries',
            // 'bibliography:false' => 'Set for non-bibliographic entries',
        );
        $sets = array_merge(
            $sets,
            $this->getSetsForDocumentTypes(),
            $this->getSetsForProjects(),
        );
        //$sets = $this->getSetsForDocumentTypes();

        foreach ($sets as $type => $name) {
            $opusDoc = $this->xml->createElement('Rdr_Sets');
            $typeAttr = $this->xml->createAttribute('Type');
            $typeValue = $this->xml->createTextNode($type);
            $typeAttr->appendChild($typeValue);
            $opusDoc->appendChild($typeAttr);
            $nameAttr = $this->xml->createAttribute('TypeName');
            $nameValue = $this->xml->createTextNode($name);
            $nameAttr->appendChild($nameValue);
            $opusDoc->appendChild($nameAttr);
            $this->xml->documentElement->appendChild($opusDoc);
        }
    }

    private function handleIllegalVerb()
    {
        $this->proc->setParameter('', 'oai_error_code', 'badVerb');
        $this->proc->setParameter('', 'oai_error_message', 'The verb provided in the request is illegal.');
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

        $repIdentifier = "tethys.at";
        //$this->_configuration->getResumptionTokenPath();
        $tokenTempPath = storage_path('app' . DIRECTORY_SEPARATOR . 'resumption');

        $this->proc->setParameter('', 'repIdentifier', $repIdentifier);
        $this->xml->appendChild($this->xml->createElement('Datasets'));

        // do some initialisation
        $cursor = 0;
        //$totalIds = 0;
        $start = $maxRecords + 1;
        $reldocIds = array();

        $metadataPrefix = null;
        // if (true === array_key_exists('metadataPrefix', $oaiRequest)) {
        //     $metadataPrefix = $oaiRequest['metadataPrefix'];
        // }
        // $this->proc->setParameter('', 'oai_metadataPrefix', $metadataPrefix);

        $tokenWorker = new ResumptionTokens();
        $tokenWorker->setResumptionPath($tokenTempPath);

        // parameter resumptionToken is given
        if (false === empty($oaiRequest['resumptionToken'])) {
            $resParam = $oaiRequest['resumptionToken']; //e.g. "158886496600000"
            $token = $tokenWorker->getResumptionToken($resParam);

            if (true === is_null($token)) {
                throw new OaiModelException("cache is outdated.", OaiModelError::BADRESUMPTIONTOKEN);
            }
            $cursor = $token->getStartPosition() - 1;//startet dann bei Index 10
            $start = $token->getStartPosition() + $maxRecords;
            $totalIds = $token->getTotalIds();
            $reldocIds = $token->getDocumentIds();
            $metadataPrefix = $token->getMetadataPrefix();

            $this->proc->setParameter('', 'oai_metadataPrefix', $metadataPrefix);

            // else no resumptionToken is given
        } else {
            // no resumptionToken is given
            if (true === array_key_exists('metadataPrefix', $oaiRequest)) {
                $metadataPrefix = $oaiRequest['metadataPrefix'];
            } else {
                throw new OaiModelException(
                    'The prefix of the metadata argument is unknown.',
                    OaiModelError::BADARGUMENT
                );
            }
            $this->proc->setParameter('', 'oai_metadataPrefix', $metadataPrefix);

            $finder = Dataset::query();
            // add server state restrictions
            $finder->whereIn('server_state', $this->deliveringDocumentStates);
            if (array_key_exists('set', $oaiRequest)) {
                $setarray = explode(':', $oaiRequest['set']);
                
                if ($setarray[0] == 'data-type') {
                    if (count($setarray) === 2 and !empty($setarray[1])) {
                        $finder->where('type', $setarray[1]);
                    }
                } elseif ($setarray[0] == 'project') {
                    if (count($setarray) === 2 and !empty($setarray[1])) {
                        // $finder->where('type', $setarray[1]);
                        $finder->whereHas('project', function ($q) use ($setarray) {
                            $q->where('label', $setarray[1]);
                        });
                    }
                } elseif (!empty($setarray[0]) && $setarray[0] == 'open_access') {
                    $openAccessLicences = ["CC-BY-4.0", "CC-BY-SA-4.0"];
                    $finder->whereHas('licenses', function ($q) use ($openAccessLicences) {
                        $q->whereIn('name', $openAccessLicences);
                        // $q->where('name', '=', "CC-BY-4.0")->orWhere('name', '=',"CC-BY-SA-4.0");
                    });
                    //$test = $finder->toSql();
                }
            }

            if (array_key_exists('from', $oaiRequest) && array_key_exists('until', $oaiRequest)) {
                $from = $oaiRequest['from'];
                $fromDate = Carbon::parse($from);
                $until = $oaiRequest['until'];
                $untilDate = \Illuminate\Support\Carbon::parse($until);
                if (strlen($from) != strlen($until)) {
                    throw new OaiModelException(
                        'The request has different granularities for the from and until parameters.',
                        OaiModelError::BADARGUMENT
                    );
                }
                if ($fromDate->hour == 0) {
                    $fromDate = $fromDate->startOfDay();
                    $untilDate = $untilDate->endOfDay();
                }
                $finder->where('server_date_published', '>=', $fromDate)
                    ->where('server_date_published', '<=', $untilDate);
                // $test = $finder->toSql();
            } elseif (array_key_exists('until', $oaiRequest) && !array_key_exists('from', $oaiRequest)) {
                $until = $oaiRequest['until'];
                try {
                    $untilDate = \Illuminate\Support\Carbon::parse($until);
                    if ($untilDate->hour == 0) {
                        $untilDate = $untilDate->endOfDay();
                    }
                    // if (strtotime($untilDate) > 0) {
                    $earliestPublicationDate = Dataset::earliestPublicationDate()->server_date_published;
                    if ($earliestPublicationDate->gt($untilDate)) {
                        throw new OaiModelException(
                            "earliestDatestamp is greater than given until date.
                            The given values results in an empty list.",
                            OaiModelError::NORECORDSMATCH
                        );
                    } else {
                        $finder->where('server_date_published', '<=', $untilDate);
                        $test = $finder->toSql();
                    }
                } catch (OaiModelException $e) {
                    throw new OaiModelException(
                        "earliestDatestamp is greater than given until date.
                        The given values results in an empty list.",
                        OaiModelError::NORECORDSMATCH
                    );
                } catch (\Exception $e) {
                    throw new OaiModelException(
                        'The until date argument is not valid.',
                        OaiModelError::BADARGUMENT
                    );
                }
            } elseif (array_key_exists('from', $oaiRequest) && !array_key_exists('until', $oaiRequest)) {
                $from = $oaiRequest['from'];
                try {
                    $fromDate = \Illuminate\Support\Carbon::parse($from);
                    if ($fromDate->hour == 0) {
                        $fromDate = $fromDate->startOfDay();
                    }
                    // if (strtotime($fromDate) > 0) {
                    $now = new Carbon();
                    if ($fromDate->gt($now)) {
                        throw new OaiModelException(
                            "Given from date is greater than now. The given values results in an empty list.",
                            OaiModelError::NORECORDSMATCH
                        );
                    } else {
                        $finder->where('server_date_published', '>=', $fromDate);
                    }
                } catch (OaiModelException $e) {
                    throw new OaiModelException(
                        "Given from date is greater than now. The given values results in an empty list.",
                        OaiModelError::NORECORDSMATCH
                    );
                } catch (\Exception $e) {
                    throw new OaiModelException(
                        'The from date argument is not valid.',
                        OaiModelError::BADARGUMENT
                    );
                }
            }
            
            $totalIds = $finder->count();
            $reldocIds = $finder->orderBy('publish_id')->pluck('publish_id')->toArray();
        }

        // handling of document ids
        $restIds = $reldocIds;
        $workIds = array_splice($restIds, 0, $maxRecords);
        //foreach ($datasets as $dataset)
        foreach ($workIds as $dataId) {
            //$dataset = Dataset::findOrFail($dataId);
            $dataset = Dataset::where('publish_id', '=', $dataId)->firstOrFail();
            $this->createXmlRecord($dataset);
        }

        // no records returned
        if (true === empty($workIds)) {
            throw new OaiModelException(
                "The combination of the given values results in an empty list.",
                OaiModelError::NORECORDSMATCH
            );
        }

        // store the further Ids in a resumption-file
        $countRestIds = count($restIds);
        if ($countRestIds > 0) {
            $token = new ResumptionToken();
            $token->setStartPosition($start);
            $token->setTotalIds($totalIds);
            $token->setDocumentIds($restIds);
            $token->setMetadataPrefix($metadataPrefix);

            $tokenWorker->storeResumptionToken($token);

            // set parameters for the resumptionToken-node
            $res = $token->getResumptionId();
            $this->setParamResumption($res, $cursor, $totalIds);
        }
    }

    /**
     * Set parameters for resumptionToken-line.
     *
     * @param  string  $res value of the resumptionToken
     * @param  int     $cursor value of the cursor
     * @param  int     $totalIds value of the total Ids
     */
    private function setParamResumption($res, $cursor, $totalIds)
    {
        // $tomorrow = str_replace('+00:00', 'Z', Carbon::now()->addHour(1)->setTimeZone('UTC'));
        $tomorrow = Carbon::now()->addDay(1)->toIso8601ZuluString();
        $this->proc->setParameter('', 'dateDelete', $tomorrow);
        $this->proc->setParameter('', 'res', $res);
        $this->proc->setParameter('', 'cursor', $cursor);
        $this->proc->setParameter('', 'totalIds', $totalIds);
    }

    private function createXmlRecord(Dataset $dataset)
    {
        //$node = $this->xml->createElement('Rdr_Dataset');
        $domNode = $this->getDatasetXmlDomNode($dataset);
        // add frontdoor url
        $this->addLandingPageAttribute($domNode, $dataset->publish_id);

        // add access rights to element
        //$this->_addAccessRights($domNode, $dataset);

        $node = $this->xml->importNode($domNode, true);

        //$node->setAttribute("Id",  $dataset->id);
        //$node->setAttribute("ServerState",  $dataset->server_state);

        ////$child =  new \DOMElement("ServerDateModified");
        //$child =   $this->xml->createElement('ServerDateModified');
        //$child->setAttribute("Year",  $dataset->server_date_modified->format('Y'));
        //$child->setAttribute("Month",  $dataset->server_date_modified->month);
        //$child->setAttribute("Day",  $dataset->server_date_modified->day);
        //$node->appendChild($child);

        //$type = $dataset->type;
        $this->addSpecInformation($node, 'data-type:' . $dataset->type);
        //$this->addSpecInformation($node, 'bibliography:' . 'false');

        $this->xml->documentElement->appendChild($node);
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
        // $url = route('frontend.dataset.show', $dataid);
        $base_domain = config('tethys.base_domain');
        $url ='https://' . get_domain($base_domain) . "/dataset/" . $dataid;

        $owner = $document->ownerDocument;
        $attr = $owner->createAttribute('landingpage');
        $attr->appendChild($owner->createTextNode($url));
        $document->appendChild($attr);
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
        $cache = ($dataset->xmlCache) ? $dataset->xmlCache : new \App\Models\XmlCache();
        $xmlModel->setXmlCache($cache);
        return $xmlModel->getDomDocument()->getElementsByTagName('Rdr_Dataset')->item(0);
    }

     /**
     * Returns oai sets for projects.
     * @return array
     */
    private function getSetsForProjects()
    {
        $setSpecPattern = self::SET_SPEC_PATTERN;
        $sets = array();

        $projects = Project::pluck('name', 'label')->toArray();
        foreach ($projects as $doctype => $row) {
            if (0 == preg_match("/^$setSpecPattern$/", $doctype)) {
                $msg = "Invalid SetSpec (doctype='" . $doctype . "')."
                    . " Allowed characters are [$setSpecPattern].";
                Log::error("OAI-PMH: $msg");
                continue;
            }

            $setSpec = 'project:' . $doctype;
            // $count = $row['count'];
            $sets[$setSpec] = "Set for project '$doctype'";
        }
        return $sets;
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
        $identify->addChild('baseURL', "https://tethys.at/");
        $identify->addChild('protocolVersion', '2.0');
        $identify->addChild('adminEmail', 'repository@geologie.ac.at');
        //$identify->addChild('earliestDatestamp', '2017-04-07');
        $identify->addChild('earliestDatestamp', $earliestDateFromDb->toDateString());
        $identify->addChild('deletedRecord', 'persistent');

        //$description = $identify->addChild('description');
        //$oaiIdentifier =  $description->addChild('oai-identifier');
        //$oaiIdentifier->addAttribute('xmlns', 'http://www.openarchives.org/OAI/2.0/oai-identifier');
        //$oaiIdentifier->addAttribute('xsi:schemaLocation', 'http://www.openarchives.org/OAI/2.0/oai-identifier');
        //$oaiIdentifier->addChild('scheme', 'oai');

        return $sxe;
    }
}
