<?php
namespace App\Library\Xml;

use App\Models\Title;
use App\Models\Description;
use App\Models\License;
use App\Models\Person;
use App\Models\File;
use App\Models\Coverage;
use App\Models\DatasetIdentifier;
use App\Models\Subject;
use App\Models\DatasetReference;

/**
 * DatasetExtension short summary.
 *
 * DatasetExtension description.
 *
 * @version 1.0
 * @author kaiarn
 */
trait DatasetExtension
{
    protected $externalFields = array(
        'TitleMain' => array(
            'model' => Title::class,
            'options' => array('type' => ['Main']),
            'fetch' => 'eager'
        ),
        'TitleAdditional' => array(
            'model' => Title::class,
            'options' => array('type' => ['Alternative', 'Sub', 'Translated', 'Other']),
            'fetch' => 'eager'
        ),
        'TitleAbstract' => array(
            'model' => Description::class,
            'options' => array('type' => ['Abstract', 'Translated']),
            'fetch' => 'eager'
        ),
        'TitleAbstractAdditional' => array(
            'model' => Description::class,
            'options' => array('type' => ['Methods', 'Technical_info', 'Series_information', 'Other']),
            'fetch' => 'eager'
        ),
        'Licence' => array(
            'model' => License::class,
            'through' => 'link_documents_licences',
            'relation' => 'licenses',
            'fetch' => 'eager'
        ),
        'PersonAuthor' => array(
            'model' => Person::class,
            'through' => 'link_documents_persons',
            'pivot' => array('role' => 'author', 'sort_order' => 'sort_order'),
                            //'sort_order' => array('sort_order' => 'ASC'),   // <-- We need a sorted authors list.
            //'sort_field' => 'SortOrder',
            'relation' => 'persons',
            'fetch' => 'eager'
        ),
        'PersonContributor' => array(
            'model' => Person::class,
            'through' => 'link_documents_persons',
            'pivot' => array('role' => 'contributor', 'contributor_type' => 'contributor_type',  'sort_order' => 'sort_order'),
            //                'sort_order' => array('sort_order' => 'ASC'),   // <-- We need a sorted authors list.
            //'sort_field' => 'SortOrder',
            'relation' => 'persons',
            'fetch' => 'eager'
        ),
        'Reference' => array(
            'model' => DatasetReference::class,
            'relation' => 'references',
            'fetch' => 'eager'
        ),
        'Identifier' => array(
            'model' => DatasetIdentifier::class,
            'relation' => 'identifier',
            'fetch' => 'eager'
        ),
        'Subject' => array(
            'model' => Subject::class,
            'through' => 'link_dataset_subjects',
            'relation' => 'subjects',
            'fetch' => 'eager'
        ),
        'File' => array(
            'model' => File::class,
            'relation' => 'files',
            'fetch' => 'eager'
        ),
        // 'GeolocationBox' => array(
        //     'model' => GeolocationBox::class,
        //     'relation' => 'geolocation',
        //     'fetch' => 'eager'
        // ),
        'Coverage' => array(
            'model' => Coverage::class,
            'relation' => 'coverage',
            'fetch' => 'eager'
        ),
    );

    protected $internalFields = array();

    protected $fields = array();

    protected function initFields()
    {
        $fields = array(
            "Id",
            "PublisherName",
            "PublishId",
            "ContributingCorporation",
            "CreatingCorporation",
            "Language",
            "PublishedDate", "PublishedYear",
            "PublisherName", "PublisherPlace",
            "PublicationState",
            "EmbargoDate", "CreatedAt",
            "ServerDateModified",
            "ServerDatePublished",
            "ServerDateDeleted",
            "ServerState",
            "Type",
            "BelongsToBibliography",
            "EmbargoDate"
        );

        foreach ($fields as $fieldname) {
            $field = new Field($fieldname);
            $this->addField($field);
        }

        foreach (array_keys($this->externalFields) as $fieldname) {
            $field = new Field($fieldname);
            $field->setMultiplicity('*');
            $this->addField($field);
        }

             // Initialize available date fields and set up date validator
            // if the particular field is present
        $dateFields = array(
            'EmbargoDate', 'CreatedAt', 'PublishedDate',
            'ServerDatePublished', 'ServerDateDeleted', 'EmbargoDate'
        );
        foreach ($dateFields as $fieldName) {
            $this->getField($fieldName)
                ->setValueModelClass('Carbon');
        }

            // $this->_fetchValues();
    }

    /**
     * Get a list of all fields attached to the model. Filters all fieldnames
     * that are defined to be inetrnal in $internalFields.
     *
     * @see    Opus_Model_Abstract::internalFields
     * @return array    List of fields
     */
    public function describe()
    {
        return array_diff(array_keys($this->fields), $this->internalFields);
    }

    public function addField(Field $field)
    {
        $fieldname = $field->getName();
        if (isset($fieldname, $this->externalFields[$fieldname])) {
            $options = $this->externalFields[$fieldname];

            // set ValueModelClass if a through option is given
            if (isset($options['model'])) {
                $field->setValueModelClass($options['model']);
            }
            // set LinkModelClass if a through option is given
            //if (isset($options['through']))
            //{
            //    $field->setLinkModelClass($options['through']);
            //}
        }

        $this->fields[$field->getName()] = $field;
        $field->setOwningModelClass(get_class($this));
        return $this;
    }

    // public function getField($name)
    // {
    //     return $this->_getField($name);
    // }

    /**
     * Return a reference to an actual field.
     *
     * @param string $name Name of the requested field.
     * @return Field The requested field instance. If no such instance can be found, null is returned.
     */
    public function getField($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        } else {
            return null;
        }
    }

    public function fetchValues()
    {
        $this->initFields();
        foreach ($this->fields as $fieldname => $field) {
            if (isset($this->externalFields[$fieldname]) === true) {
                $fetchmode = 'lazy';
                if (isset($this->externalFields[$fieldname]['fetch']) === true) {
                    $fetchmode = $this->externalFields[$fieldname]['fetch'];
                }

                if ($fetchmode === 'lazy') {
                    // Remember the field to be fetched later.
                    $this->_pending[] = $fieldname;
                    // Go to next field
                    continue;
                } else {
                    // Immediately load external field if fetching mode is set to 'eager'
                    $this->loadExternal($fieldname);
                }
            } else {
                // Field is not external an gets handled by simply reading
                $property_name = self::convertFieldnameToColumn($fieldname);
                //$test = $this->server_date_created;
                $fieldval = $this->{$property_name};

                // explicitly set null if the field represents a model except for dates
                if (null !== $field->getValueModelClass()) {
                    if (true === empty($fieldval)) {
                        $fieldval = null;
                    } else {
                        $fieldval = new \Illuminate\Support\Carbon($fieldval);
                    }
                }

                $field->setValue($fieldval);
            }
        }
    }

    public static function convertFieldnameToColumn($fieldname)
    {
        return strtolower(preg_replace('/(?!^)[[:upper:]]/', '_\0', $fieldname));
    }

    protected function loadExternal($fieldname)
    {
        $field = $this->fields[$fieldname];

        $modelclass = $field->getLinkModelClass();
        if (!isset($modelclass)) {
            // For handling a value model, see 'model' option.
            $modelclass = $field->getValueModelClass();
        }

        $tableclass = new $modelclass();//::getTableGatewayClass();
         //   $table = Opus_Db_TableGateway::getInstance($tableclass);
        $select = $tableclass->query();//->where("document_id", $this->id);;

        // If any declared constraints, add them to query
        if (isset($this->externalFields[$fieldname]['options'])) {
            $options = $this->externalFields[$fieldname]['options'];
            foreach ($options as $column => $value) {
                // $searchString = ',';
                // if (strpos($value, $searchString) !== false) {
                // $arr = explode(",", $value);
                if (is_array($value)) {
                    $arr = $value;
                    $select->whereIn($column, $arr);
                } else {
                    $select = $select->where($column, $value);
                }
            }
        }


        // Get dependent rows
        $result = array();
        $datasetId = $this->id;

        $rows = array();
        if (isset($this->externalFields[$fieldname]['through'])) {
            $relation = $this->externalFields[$fieldname]['relation'];
            //$rows = $select->datasets
            ////->orderBy('name')
            //->get();
            //$licenses = $select->with('datasets')->get();
            //$rows =  $supplier->datasets;
            $rows = $this->{$relation};
            if (isset($this->externalFields[$fieldname]['pivot'])) {
                $pivArray = $this->externalFields[$fieldname]['pivot'];
                $pivotValue = $pivArray['role'];
                //$through = $this->externalFields[$fieldname]['through'];
                $rows = $this->{$relation}()->wherePivot('role', $pivotValue)->get();
                //$rows = $this->{$relation}()->get();
                //$rows = $this->belongsToMany($modelclass, $through, 'document_id')
                //->wherePivot('role', $pivotValue)->get();
            }
        } else {
            $rows = $select->whereHas('dataset', function ($q) use ($datasetId) {
                $q->where('id', $datasetId);
            })->orderBy('id')->get();
        }

        foreach ($rows as $row) {
            // //$newModel = new $modelclass($row);
            // $result[] = $row;//->value;

            $attributes = array_keys($row->getAttributes());
            if (isset($this->externalFields[$fieldname]['pivot'])) {
                $pivotArray = $this->externalFields[$fieldname]['pivot'];
                $arrayKeys = array_keys($pivotArray);
                $extendedArrayKeys = array_map(function ($pivotAttribute) {
                    return "pivot_" . $pivotAttribute;
                }, $arrayKeys);
                $attributes = array_merge($attributes, $extendedArrayKeys);
            }
            $objArray = [];
            foreach ($attributes as $property_name) {
                $fieldName = self::convertColumnToFieldname($property_name);
                $fieldval = "";
                if (substr($property_name, 0, 6) === "pivot_") {
                    $str = ltrim($property_name, 'pivot_');
                    $fieldName = self::convertColumnToFieldname($str);
                    $fieldval = $row->pivot->{$str};
                } elseif ($fieldName == "Type") {
                    $fieldval = ucfirst($row->{$property_name});
                } else {
                    // $field =new Field($fieldName);
                    $fieldval = $row->{$property_name};
                    // $field->setValue($fieldval);
                    // $this->_mapField($field, $dom, $rootNode);
                }
                $objArray[$fieldName] = $fieldval;
            }
            $result[] = $objArray;
        }

         // Set the field value
        $field->setValue($result);
    }

     //snakeToCamel
    public static function convertColumnToFieldname($columnname)
    {
         //return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $columnname))));
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $columnname)));
    }
}
