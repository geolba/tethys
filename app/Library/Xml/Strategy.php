<?php
namespace App\Library\Xml;

use App\Dataset;
use DOMDocument;

/**
 * Strategy short summary.
 *
 * Strategy description.
 *
 * @version 1.0
 * @author kaiarn
 */
class Strategy
{
    /**
     * Holds current configuration.
     *
     * @var Conf
     */
    private $_config;

    /**
     * Holds current representation version.
     *
     * @var double
     */
    protected $_version = null;


    /**
     * Initiate class with a valid config object.
     */
    public function __construct()
    {
        $this->_version = 1.0;
        $this->_config = new Conf();
    }

    /**
     * (non-PHPdoc)
     * see library/Opus/Model/Xml/Opus_Model_Xml_Strategy#setDomDocument()
     */
    public function setup(Conf $conf)
    {
        $this->_config = $conf;
    }

    /**
     * If a model has been set this method generates and returnes
     * DOM representation of it.
     *
     * @throws \Exception Thrown if no Model is given.
     * @return \DOMDocument DOM representation of the current Model.
     */
    public function getDomDocument()
    {
        if (null === $this->_config->model) {
            throw new \Exception('No Model given for serialization.');
        }
        $this->_config->dom = new DOMDocument('1.0', 'UTF-8');
        $root = $this->_config->dom->createElement('Opus');
        $root->setAttribute('version', $this->getVersion());
        $this->_config->dom->appendChild($root);
        $root->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');

        $this->_mapModel($this->_config->model, $this->_config->dom, $root);

        return $this->_config->dom;
    }

    protected function _mapModel(Dataset $model, \DOMDocument $dom, \DOMNode $rootNode)
    {
        $fields = $model->describe();
        $excludeFields = $this->getConfig()->excludeFields;
        if (count($excludeFields) > 0) {
            $fieldsDiff = array_diff($fields, $excludeFields);
        } else {
            $fieldsDiff = $fields;
        }

        $childNode = $this->createModelNode($dom, $model);
        $rootNode->appendChild($childNode);

        foreach ($fieldsDiff as $fieldname) {
            $field = $model->getField($fieldname);
            $this->_mapField($field, $dom, $childNode);
        }
    }

    protected function _mapField(Field $field, DOMDocument $dom, \DOMNode $rootNode)
    {
        $modelClass = $field->getValueModelClass();
        $fieldValues = $field->getValue();

        if (true === $this->getConfig()->excludeEmpty) {
            if (true === is_null($fieldValues)
                or (is_string($fieldValues) && trim($fieldValues) == '')
                or (is_array($fieldValues) && empty($fieldValues))) {
                return;
            }
        }

        if (null === $modelClass) {
            $this->mapSimpleField($dom, $rootNode, $field);
        } else {
            $fieldName = $field->getName();

            if (!is_array($fieldValues)) {
                $fieldValues = array($fieldValues);
            }

            foreach ($fieldValues as $value) {
                $childNode = $this->createFieldElement($dom, $fieldName);
                //$childNode->setAttribute("Value",  $value);
                $rootNode->appendChild($childNode);

                
                // if a field has no value then is nothing more to do
                // TODO maybe must be there an other solution
                // FIXME remove code duplication (duplicates Opus_Model_Xml_Version*)
                if (is_null($value)) {
                    continue;
                }

                if ($value instanceof \Illuminate\Database\Eloquent\Model) {
                    $this->_mapModelAttributes($value, $dom, $childNode);
                } elseif ($value instanceof \Carbon\Carbon) {
                    $this->_mapDateAttributes($value, $dom, $childNode);
                } elseif (is_array($value)) {
                    $this->_mapArrayAttributes($value, $dom, $childNode);
                }
            }
        }
    }

    public function mapSimpleField(DOMDocument $dom, \DOMNode $rootNode, Field $field)
    {
        $fieldName = $field->getName();
        $fieldValues = $this->getFieldValues($field);

        // Replace invalid XML-1.0-Characters by UTF-8 replacement character.
        $fieldValues = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', "\xEF\xBF\xBD ", $fieldValues);
        $rootNode->setAttribute($fieldName, $fieldValues);
    }

    protected function createFieldElement(DOMDocument $dom, $fieldName)
    {
        return $dom->createElement($fieldName);
    }

    protected function _mapDateAttributes(\Carbon\Carbon $model, DOMDocument $dom, \DOMNode $rootNode)
    {
        $rootNode->setAttribute("Year", $model->year);
        $rootNode->setAttribute("Month", $model->month);
        $rootNode->setAttribute("Day", $model->day);
        $rootNode->setAttribute("Hour", $model->hour);
        $rootNode->setAttribute("Minute", $model->minute);
        $rootNode->setAttribute("Second", $model->second);
        $rootNode->setAttribute("UnixTimestamp", $model->timestamp);
        $rootNode->setAttribute("Timezone", $model->tzName);
    }

    protected function _mapArrayAttributes(array $attributes, DOMDocument $dom, \DOMNode $rootNode)
    {
        //$attributes = array_keys($model->getAttributes());
        foreach ($attributes as $property_name => $value) {
            $fieldName = $property_name;
            $field = new Field($fieldName);
            $fieldval = $value;
            $field->setValue($fieldval);
            $this->_mapField($field, $dom, $rootNode);
        }
    }

    protected function _mapModelAttributes(\Illuminate\Database\Eloquent\Model $model, DOMDocument $dom, \DOMNode $rootNode)
    {
        $attributes = array_keys($model->getAttributes());
        foreach ($attributes as $property_name) {
            $fieldName = self::convertColumnToFieldname($property_name);
            $field = new Field($fieldName);
            $fieldval = $model->{$property_name};
            $field->setValue($fieldval);
            $this->_mapField($field, $dom, $rootNode);
        }
    }

    //snakeToCamel
    public static function convertColumnToFieldname($columnname)
    {
        //return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $columnname))));
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $columnname)));
    }

    public function getFieldValues($field)
    {
        $fieldValues = $field->getValue();

        // workaround for simple fields with multiple values
        if (true === $field->hasMultipleValues()) {
            $fieldValues = implode(',', $fieldValues);
        }
        //if ($fieldValues instanceOf DateTimeZone) {
        //    $fieldValues = $fieldValues->getName();
        //}

        return trim($fieldValues);
    }


    protected function createModelNode(DOMDocument $dom, Dataset $model)
    {
        $classname = "Rdr_" . substr(strrchr(get_class($model), '\\'), 1);
        return $dom->createElement($classname);
    }

    /**
     * Return version value of current xml representation.
     *
     * @see library/Opus/Model/Xml/Opus_Model_Xml_Strategy#getVersion()
     */
    public function getVersion()
    {
        return floor($this->_version);
    }

    public function getConfig()
    {
        return $this->_config;
    }
}
