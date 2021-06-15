<?php
/**
 * Get an associative array with localeCodes as keys and translated URLs of current page as value
 */
function getLocalizedURLArray()
{
    $localesOrdered = LaravelLocalization::getLocalesOrder();
    $localizedURLs = array();
    foreach ($localesOrdered as $localeCode => $properties) {
        $localizedURLs[$localeCode] = LaravelLocalization::getLocalizedURL($localeCode, null, [], true);
    }
    return $localizedURLs;
}

function get_domain($host)
{
    $myhost = strtolower(trim($host));
    $count = substr_count($myhost, '.');
    if ($count === 2) {
        if (strlen(explode('.', $myhost)[1]) > 3) {
            $myhost = explode('.', $myhost, 2)[1];
        }

    } else if ($count > 2) {
        $myhost = get_domain(explode('.', $myhost, 2)[1]);
    }
    $myhost = preg_replace( "#^[^:/.]*[:/]+#i", "", $myhost);
    return $myhost;
}
