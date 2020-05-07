<?php

namespace App\Models\Oai;

use App\Models\Oai\ResumptionToken;
use App\Models\Oai\ResumptionTokenException;
use Illuminate\Support\Facades\Cache;

/**
 * Handling (read, write) of resumption tokens
 */
class ResumptionTokens
{
    /**
     * Holds resumption path without trailing slash.
     *
     * @var string
     */
    private $resumptionPath = null;

    private $resumptionId = null;
    protected $filePrefix = 'rs_';
    protected $fileExtension = 'txt';

    /**
     * Constructor of class
     *
     * @param $resPath (Optional) Initialise resumption path on create.
     *
     */
    public function __construct($resPath = null)
    {
        if (false === empty($resPath)) {
            $this->setResumptionPath($resPath);
        }
    }

    /**
     * Generate a unique file name and resumption id for storing resumption token.
     * Double action because file name 8without prefix and file extension)
     * and resumption id should be equal.
     *
     * @return filename Generated filename including path and file extension.
     */
    private function generateResumptionName()
    {
        $fc = 0;

        // generate a unique partial name
        // return value of time() should be enough
        $uniqueId = time();

        $fileExtension = $this->fileExtension;
        if (false === empty($fileExtension)) {
            $fileExtension = '.' . $fileExtension;
        }

        do {
            $uniqueName = sprintf('%s%05d', $uniqueId, $fc++);
            $file = $this->resumptionPath . DIRECTORY_SEPARATOR . $this->filePrefix . $uniqueName . $fileExtension;
        } while (true === file_exists($file));

        $this->resumptionId = $uniqueName;
        return $uniqueName;
        //return $file;
    }

    public function getResumptionToken($resId)
    {

        $token = null;

        // $fileName = $this->resumptionPath . DIRECTORY_SEPARATOR . $this->filePrefix . $resId;
        // if (false === empty($this->fileExtension)) {
        //     $fileName .= '.' . $this->fileExtension;
        // }

        // if (true === file_exists($fileName)) {
        if (Cache::has($resId)) {
            //$fileContents = file_get_contents($fileName);
            $fileContents = Cache::get($resId);

            // if data is not unserializueabke an E_NOTICE will be triggerd and false returned
            // avoid this E_NOTICE
            $token = @unserialize($fileContents);
            if (false === ($token instanceof ResumptionToken)) {
                $token = null;
            }
        }
        return $token;
    }

    /**
     * Set resumption path where the resumption token files are stored.
     *
     * @throws Oai_Model_ResumptionTokenException Thrown if directory operations failed.
     * @return void
     */
    public function setResumptionPath($resPath)
    {
        // expanding all symbolic links and resolving references
        $realPath = realpath($resPath);

        // if (empty($realPath) or false === is_dir($realPath)) {
        //     throw new Oai_Model_ResumptionTokenException(
        //         'Given resumption path "' . $resPath . '" (real path: "' . $realPath . '") is not a directory.'
        //     );
        // }

        // if (false === is_writable($realPath)) {
        //     throw new Oai_Model_ResumptionTokenException(
        //         'Given resumption path "' . $resPath . '" (real path: "' . $realPath . '") is not writeable.'
        //     );
        // }
        $this->resumptionPath = $realPath;
    }

     /**
     * Store a resumption token
     *
     * @param Oai_Model_Resumptiontoken $token Token to store.
     * @throws Oai_Model_ResumptionTokenException Thrown on file operation error.
     * @return void
     */
    public function storeResumptionToken(ResumptionToken $token)
    {

        // $fileName = $this->generateResumptionName();
        $uniqueName = $this->generateResumptionName();
       

        // $file = fopen($fileName, 'w+');
        // if (false === $file) {
        //     throw new ResumptionTokenException('Could not open file "' . $fileName . '" for writing!');
        // }

        $serialToken = serialize($token);
        // if (false === fwrite($file, $serialToken)) {
        //     throw new ResumptionTokenException('Could not write file "' . $fileName . '"!');
        // }
        // if (false === fclose($file)) {
        //     throw new ResumptionTokenException('Could not close file "' . $fileName . '"!');
        // }
        Cache::put($uniqueName, $serialToken, now()->addMinutes(60));

        $token->setResumptionId($this->resumptionId);
    }
}
