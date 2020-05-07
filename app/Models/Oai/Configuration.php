<?php

namespace App\Models\Oai;

class Configuration
{
    /**
     * Hold path where to store temporary resumption token files.
     *
     * @var string
     */
    private $pathTokens = '';

    /**
     * Holds maximum number of identifiers to list per request.
     *
     * @var int
     */
    private $maxListIds = 15;

    /**
     * Holds maximum number of records to list per request.
     *
     * @var int
     */
    private $maxListRecs = 15;

    public function __construct()
    {
        $this->maxListIds = config('oai.max.listidentifiers');

        $this->maxListRecs = config('oai.max.listrecords');

        $this->pathTokens = config('app.workspacePath')
        . DIRECTORY_SEPARATOR .'tmp'
        . DIRECTORY_SEPARATOR . 'resumption';
    }

    /**
     * Return temporary path for resumption tokens.
     *
     * @return string Path.
     */
    public function getResumptionTokenPath()
    {
        return $this->pathTokens;
    }

    /**
     * Return maximum number of listable identifiers per request.
     *
     * @return int Maximum number of listable identifiers per request.
     */
    public function getMaxListIdentifiers()
    {
        return $this->maxListIds;
    }

    /**
     * Return maximum number of listable records per request.
     *
     * @return int Maximum number of listable records per request.
     */
    public function getMaxListRecords()
    {
        return $this->maxListRecs;
    }
}
