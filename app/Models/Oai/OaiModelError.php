<?php

namespace App\Models\Oai;

use App\Exceptions\OaiModelException;

class OaiModelError
{
/**
 * Define all valid error codes.
 */
    const BADVERB = 1010;
    const BADARGUMENT = 1011;
    const CANNOTDISSEMINATEFORMAT = 1012;
    const BADRESUMPTIONTOKEN = 1013;
    const NORECORDSMATCH = 1014;
    const IDDOESNOTEXIST = 1015;
    /**
     * Holds OAI error codes for internal error numbers.
     *
     * @var array  Valid OAI parameters.
     */
    protected static $oaiErrorCodes = array(
        self::BADVERB => 'badVerb',
        self::BADARGUMENT => 'badArgument',
        self::NORECORDSMATCH => 'noRecordsMatch',
        self::CANNOTDISSEMINATEFORMAT => 'cannotDisseminateFormat',
        self::BADRESUMPTIONTOKEN => 'badResumptionToken',
        self::IDDOESNOTEXIST => 'idDoesNotExist',
    );
    /**
     * Map internal error codes to OAI error codes.
     *
     * @param int $code Internal error code.
     * @return string OAI error code.
     */
    public static function mapCode($code)
    {
        if (false === array_key_exists($code, self::$oaiErrorCodes)) {
            throw new OaiModelException("Unknown oai error code $code");
        }
        return self::$oaiErrorCodes[$code];
    }
}
