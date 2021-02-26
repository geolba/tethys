<?php
/**
 * Created by Visual Studio Code.
 * User: kaiarn
 * Date: 19.02.2021
 */
namespace App\Interfaces;

interface DoiInterface
{
    public function registerDoi($doiValue, $xmlMeta, $landingPageUrl);
    public function getMetadataForDoi($identifier);
    public function updateMetadataForDoi($identifier, $new_meta);
    public function deleteMetadataForDoi($identifier);
    // public function deleteDoiByCurlRequest($doi);
}
