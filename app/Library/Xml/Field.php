<?php
namespace App\Library\Xml;

/**
 * Field short summary.
 *
 * Field description.
 *
 * @version 1.0
 * @author kaiarn
 */
class Field
{
    /**
     * Hold multiplicity constraint.
     *
     * @var Integer|String
     */
    protected $_multiplicity = 1;

    /**
     * Simple check for multiple values.
     *
     * @var bool
     */
    private $_hasMultipleValues = false;

    /**
     * Holds the classname for external fields.
     *
     * @var string
     */
    protected $_valueModelClass = null;

    /**
     * Holds the classname for link fields.
     *
     * @var string
     */
    protected $_linkModelClass = null;

    /**
     * Holds the classname of the model that the field belongs to.
     */
    protected $_owningModelClass = null;

    /**
     * Hold the fields value.
     *
     * @var mixed
     */
    protected $_value = null;

    /**
     * Create an new field instance and set the given name.
     *
     * @param string $name Internal name of the field.
     */
    public function __construct($name)
    {
        $this->_name = $name;
    }

    /**
     * Return the name of model class if the field holds model instances.
     *
     * @return string Class name or null if the value is not a model.
     */
    public function getValueModelClass()
    {
        return $this->_valueModelClass;
    }

    /**
     * Set the name of model class if the field holds model instances.
     *
     * @param  string $classname The name of the class that is used as model for this field or null.
     * @return Field Fluent interface.
     */
    public function setValueModelClass($classname)
    {
        $this->_valueModelClass = $classname;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set the name of the model class that owns the field.
     * @param string $classname The name of the class that owns the field.
     * @return Field Fluent interface.
     */
    public function setOwningModelClass($classname)
    {
        $this->_owningModelClass = $classname;
        return $this;
    }

    public function setValue($value)
    {
         // If the fields value is not going to change, leave.
        if (is_object($value) === true) {
            // weak comparison for objects
            // TODO: DateTimeZone == DateTimeZone always returns true in weak equal check!  Why?
            if ($value == $this->_value) {
                return $this;
            }
        } else {
            // strong comparison for other values
            if ($value === $this->_value) {
                return $this;
            }
        }

        // if (true === is_array($value) and 1 === count($value)) {
        //     //$value = array_pop($value);
        // }
        if (true === is_array($value) and 0 === count($value)) {
            $value = null;
        } elseif (is_bool($value)) {
            // make sure 'false' is not converted to '' (empty string), but 0 for database
            $value = (int)$value;
        }

         // if null is given, delete dependent objects
        if (null === $value) {
            //$this->_deleteDependentModels();
        } else {
            $multiValueCondition = $this->hasMultipleValues();
            $arrayCondition = is_array($value);

            // arrayfy value
            $values = $value;
            if (false === $arrayCondition) {
                $values = array($value);
            }
            // remove wrapper array if multivalue condition is not given
            if (false === $multiValueCondition) {
                $value = $values[0];
            }

            $this->_value = $value;
            return $this;
        }
    }

    public function getValue($index = null)
    {

        // wrap start value in array if multivalue option is set for this field
        $this->_value = $this->_wrapValueInArrayIfRequired($this->_value);

        // Caller requested a specific array index
        //if (!is_null($index)) {
        //    if (true === is_array($this->_value)) {
        //        if (true === isset($this->_value[$index])) {
        //            return $this->_value[$index];
        //        }
        //        else {
        //            throw new \Exception('Unvalid index: ' . $index);
        //        }
        //    }
        //    else {
        //        throw new \Exception('Invalid index (' . $index . '). Requested value is not an array.');
        //    }
        //}

        return $this->_value;
    }

    private function _wrapValueInArrayIfRequired($value)
    {
        if (is_array($value) or !$this->hasMultipleValues()) {
            return $value;
        }

        if (is_null($value)) {
            return array();
        }

        return array($value);
    }

    public function hasMultipleValues()
    {
        return $this->_hasMultipleValues;
    }

    public function setMultiplicity($max)
    {
        if ($max !== '*') {
            if ((is_int($max) === false) or ($max < 1)) {
                throw new \Exception('Only integer values > 1 or "*" allowed.');
            }
        }
        $this->_multiplicity = $max;
        $this->_hasMultipleValues = (($max > 1) or ($max === '*'));
        return $this;
    }

    /**
     * Return the name of model class if the field holds link model instances.
     *
     * @return string Class name or null if the value is not a model.
     */
    public function getLinkModelClass()
    {
        return $this->_linkModelClass;
    }
}
