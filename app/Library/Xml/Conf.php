<?php
namespace App\Library\Xml;

use App\Models\Dataset;

/**
 * Conf short summary.
 *
 * Conf description.
 *
 * @version 1.0
 * @author kaiarn
 */
class Conf
{
    /**
     * Holds the current model either directly set or deserialized from XML.
     *
     * @var Dataset
     */
    public $model = null;

    /**
     * Holds the current DOM representation.
     *
     * @var \DOMDocument
     */
    public $dom = null;

    /**
     * List of fields to skip on serialization.
     *
     * @var array
     */
    public $excludeFields = array();

    /**
     * True, if empty fields get excluded from serialization.
     *
     * @var bool
     */
    public $excludeEmpty = false;

    /**
     * Base URI for xlink:ref elements
     *
     * @var string
     */
    public $baseUri = '';

    /**
     * Map of model class names to resource names for URI generation.
     *
     * @var array
     */
    public $resourceNameMap = array();
}
