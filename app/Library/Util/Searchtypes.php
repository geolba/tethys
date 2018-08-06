<?php
namespace App\Library\Util;

class Searchtypes
{

    const SIMPLE_SEARCH = 'simple';
    const ADVANCED_SEARCH = 'advanced';
    const AUTHOR_SEARCH = 'authorsearch';
    const COLLECTION_SEARCH = 'collection';
    const LATEST_SEARCH = 'latest';
    const ALL_SEARCH = 'all';
    const SERIES_SEARCH = 'series';
    const ID_SEARCH = 'id';

    public static function isSupported($searchtype)
    {
        $supportedTypes = array (
            self::SIMPLE_SEARCH,
            self::ADVANCED_SEARCH,
            self::AUTHOR_SEARCH,
            self::COLLECTION_SEARCH,
            self::LATEST_SEARCH,
            self::ALL_SEARCH,
            self::SERIES_SEARCH,
            self::ID_SEARCH
        );
        return in_array($searchtype, $supportedTypes);
    }
}
