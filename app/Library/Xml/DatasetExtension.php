<?php
namespace App\Library\Xml;

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
    protected $_externalFields = array(
        'TitleMain' => array(
            'model' => 'App\Title',
            'options' => array('type' => 'main'),
            'fetch' => 'eager'
        ),
        'TitleAbstract' => array(
            'model' => 'App\Title',
            'options' => array('type' => 'abstract'),
            'fetch' => 'eager'
        ),
        'Licence' => array(
            'model' => 'App\License',
            'through' => 'link_documents_licences',
            'relation' => 'licenses',
            'fetch' => 'eager'
        ),
        'PersonAuthor' => array(
            'model' => 'App\Person',
            'through' => 'link_documents_persons',
            'pivot' => array('role' => 'author'),
                            //'sort_order' => array('sort_order' => 'ASC'),   // <-- We need a sorted authors list.
            //'sort_field' => 'SortOrder',
            'relation' => 'authors',
            'fetch' => 'eager'
        ),
        'PersonContributor' => array(
            'model' => 'App\Person',
            'through' => 'link_documents_persons',
            'pivot' => array('role' => 'contributor'),
            //                'sort_order' => array('sort_order' => 'ASC'),   // <-- We need a sorted authors list.
            //'sort_field' => 'SortOrder',
            'relation' => 'contributors',
            'fetch' => 'eager'
        ),
        'File' => array(
            'model' => 'App\File',
            'relation' => 'files',
            'fetch' => 'eager'
        ),
    );


    protected $_internalFields = array();

    protected $_fields = array();

    protected function _initFields()
    {
        $fields = array(
            "Id",
            "CompletedDate", "CompletedYear",
            "ContributingCorporation",
            "CreatingCorporation",
            "ThesisDateAccepted", "ThesisYearAccepted",
            "Edition",
            "Issue",
            "Language",
            "PageFirst", "PageLast", "PageNumber",
            "PublishedDate", "PublishedYear",
            "PublisherName", "PublisherPlace",
            "PublicationState",
            "ServerDateCreated",
            "ServerDateModified",
            "ServerDatePublished",
            "ServerDateDeleted",
            "ServerState",
            "Type",
            "Volume",
            "BelongsToBibliography",
            "EmbargoDate"
        );

        foreach ($fields as $fieldname) {
            $field = new Field($fieldname);
            $this->addField($field);
        }

        foreach (array_keys($this->_externalFields) as $fieldname) {
            $field = new Field($fieldname);
            $field->setMultiplicity('*');
            $this->addField($field);
        }

             // Initialize available date fields and set up date validator
            // if the particular field is present
        $dateFields = array(
            'ServerDateCreated', 'CompletedDate', 'PublishedDate',
            'ServerDateModified', 'ServerDatePublished', 'ServerDateDeleted', 'EmbargoDate'
        );
        foreach ($dateFields as $fieldName) {
            $this->getField($fieldName)
                ->setValueModelClass('Carbon');
        }

            // $this->_fetchValues();
    }

    /**
     * Get a list of all fields attached to the model. Filters all fieldnames
     * that are defined to be inetrnal in $_internalFields.
     *
     * @see    Opus_Model_Abstract::_internalFields
     * @return array    List of fields
     */
    public function describe()
    {
        return array_diff(array_keys($this->_fields), $this->_internalFields);
    }

    public function addField(Field $field)
    {
        $fieldname = $field->getName();
        if (isset($fieldname, $this->_externalFields[$fieldname])) {
            $options = $this->_externalFields[$fieldname];

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

        $this->_fields[$field->getName()] = $field;
        $field->setOwningModelClass(get_class($this));
        return $this;
    }

    public function getField($name)
    {
        return $this->_getField($name);
    }

    /**
     * Return a reference to an actual field.
     *
     * @param string $name Name of the requested field.
     * @return Field The requested field instance. If no such instance can be found, null is returned.
     */
    protected function _getField($name)
    {
        if (isset($this->_fields[$name])) {
            return $this->_fields[$name];
        } else {
            return null;
        }
    }

    public function fetchValues()
    {
        $this->_initFields();
        foreach ($this->_fields as $fieldname => $field) {
            if (isset($this->_externalFields[$fieldname]) === true) {
                $fetchmode = 'lazy';
                if (isset($this->_externalFields[$fieldname]['fetch']) === true) {
                    $fetchmode = $this->_externalFields[$fieldname]['fetch'];
                }

                if ($fetchmode === 'lazy') {
                    // Remember the field to be fetched later.
                    $this->_pending[] = $fieldname;
                    // Go to next field
                    continue;
                } else {
                    // Immediately load external field if fetching mode is set to 'eager'
                    $this->_loadExternal($fieldname);
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
                        $fieldval = new \Carbon\Carbon($fieldval);
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

    protected function _loadExternal($fieldname)
    {
        $field = $this->_fields[$fieldname];

        $modelclass = $field->getLinkModelClass();
        if (!isset($modelclass)) {
            // For handling a value model, see 'model' option.
            $modelclass = $field->getValueModelClass();
        }

        $tableclass = new $modelclass();//::getTableGatewayClass();
         //   $table = Opus_Db_TableGateway::getInstance($tableclass);
        $select = $tableclass->query();//->where("document_id", $this->id);;

        // If any declared constraints, add them to query
        if (isset($this->_externalFields[$fieldname]['options'])) {
            $options = $this->_externalFields[$fieldname]['options'];
            foreach ($options as $column => $value) {
                $select = $select->where($column, $value);
            }
        }


        // Get dependent rows
        $result = array();
        $datasetId = $this->id;

        $rows = array();
        if (isset($this->_externalFields[$fieldname]['through'])) {
            $relation = $this->_externalFields[$fieldname]['relation'];
            //$rows = $select->datasets
            ////->orderBy('name')
            //->get();
            //$licenses = $select->with('datasets')->get();
            //$rows =  $supplier->datasets;
            $rows = $this->{$relation};
            //if (isset($this->_externalFields[$fieldname]['pivot']))
            //{
            //  $pivArray = $this->_externalFields[$fieldname]['pivot'];
            //  $rows = $rows->wherePivot('role', $pivArray['role']);
            //}
        } else {
            $rows = $select->whereHas('dataset', function ($q) use ($datasetId) {
                $q->where('id', $datasetId);
            })->get();
        }

        foreach ($rows as $row) {
            // //$newModel = new $modelclass($row);
            // $result[] = $row;//->value;

            $attributes = array_keys($row->getAttributes());
            $objArray = [];
            foreach ($attributes as $property_name) {
                $fieldName = self::convertColumnToFieldname($property_name);
                // $field =new Field($fieldName);
                $fieldval = $row->{$property_name};
                // $field->setValue($fieldval);
                // $this->_mapField($field, $dom, $rootNode);
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
