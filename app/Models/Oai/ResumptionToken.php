<?php

namespace App\Models\Oai;

/**
 * Contains content and structure of a resumption token
 */
class ResumptionToken
{
     /**
     * Holds dcoument ids
     *
     * @var array
     */
    private $documentIds = array();

    /**
     * Holds metadata prefix information
     *
     * @var string
     */
    private $metadataPrefix = null;

    /**
     * Holds resumption id (only if token is stored)
     *
     * @var string
     */
    private $resumptionId = null;

    /**
     * Holds start postion
     *
     * @var integer
     */
    private $startPosition = 0;

    /**
     * Holds total amount of document ids
     *
     * @var integer
     */
    private $totalIds = 0;

    /**
     *  Returns current holded document ids.
     *
     * @return array
     */
    public function getDocumentIds()
    {
        return $this->documentIds;
    }

    /**
     * Returns metadata prefix information.
     *
     * @return string
     */
    public function getMetadataPrefix()
    {
        return $this->metadataPrefix;
    }

     /**
     * Return setted resumption id after successful storing of resumption token.
     *
     * @return string
     */
    public function getResumptionId()
    {
        return $this->resumptionId;
    }

    /**
     * Returns start position.
     *
     * @return in
     */
    public function getStartPosition()
    {
        return $this->startPosition;
    }

    /**
     * Returns total number of document ids for this request
     *
     * @return int
     */
    public function getTotalIds()
    {
        return $this->totalIds;
    }

    /**
     * Set document ids for this token.
     *
     * @param $idsToStore Set of document ids to store.
     * @return void
     */
    public function setDocumentIds($idsToStore)
    {
        if (false === is_array($idsToStore)) {
            $idsToStore = array($idsToStore);
        }

        $this->documentIds = $idsToStore;
    }

    /**
     * Set metadata prefix information.
     *
     * @param string $prefix
     * @return void
     */
    public function setMetadataPrefix($prefix)
    {
        $this->metadataPrefix = $prefix;
    }

    /**
     * Set resumption id
     *
     * @return void
     */
    public function setResumptionId($resumptionId)
    {
        $this->resumptionId = $resumptionId;
    }

    /**
     * Set postion where to start on next request.
     *
     * @param $startPostion Positon where to start on next request
     * @return void
     */
    public function setStartPosition($startPosition)
    {
        $this->startPosition = (int) $startPosition;
    }

    /**
     * Set count of document ids for this request.
     *
     * @return void
     */
    public function setTotalIds($totalIds)
    {
        $this->totalIds = (int) $totalIds;
    }
}
