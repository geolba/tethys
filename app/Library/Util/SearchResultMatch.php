<?php
namespace App\Library\Util;

use Illuminate\Support\Carbon;

/**
 * Describes local document as a match in context of a related search query.
 */
class SearchResultMatch
{

    /**
     * @var mixed
     */
    protected $id = null;

    /**
     * @var Opus_Document
     */
    protected $doc = null;

    /**
     * @var float
     */
    protected $score = null;

    /**
     * @var Opus_Date
     */
    protected $serverDateModified = null;

    /**
     * @var
     */
    protected $fulltextIdSuccess = null;

    /**
     * @var
     */
    protected $fulltextIdFailure = null;

    /**
     * Caches current document's mapping of containing serieses into document's
     * number in either series.
     *
     * @var string[]
     */
    protected $seriesNumbers = null;

    /**
     * Collects all additional information related to current match.
     *
     * @var array
     */
    protected $data = array();

    public function __construct($matchId)
    {
        $this->id = $matchId;
    }

    public static function create($matchId)
    {
        return new static($matchId);
    }

    /**
     * Retrieves ID of document matching related search query.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retrieves instance of Opus_Document related to current match.
     *
     * @throws Opus_Model_NotFoundException
     * @return Opus_Document
     */
    public function getDocument()
    {
        if (is_null($this->doc)) {
            $this->doc = new Opus_Document($this->id);
        }
        return $this->doc;
    }

    /**
     * Assigns score of match in context of related search.
     *
     * @param $score
     * @return $this
     */
    public function setScore($score)
    {
        if (!is_null($this->score)) {
            throw new RuntimeException('score has been set before');
        }
        $this->score = floatval($score);
        return $this;
    }

    /**
     * Retrieves score of match in context of related search.
     *
     * @return float|null null if score was not set
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Retrieves matching document's number in series selected by its ID.
     *
     * This method is provided for downward compatibility. You are advised to
     * inspect document's model for this locally available information rather
     * than relying on search engine returning it.
     *
     * @deprecated
     * @return string
     */
    public function getSeriesNumber($seriesId)
    {
        if (!$seriesId) {
            return null;
        }

        if (!is_array($this->seriesNumbers)) {
            $this->seriesNumbers = array();

            foreach ($this->getDocument()->getSeries() as $linkedSeries) {
                $id = $linkedSeries->getModel()->getId();
                $number = $linkedSeries->getNumber();

                $this->seriesNumbers[$id] = $number;
            }
        }

        return array_key_exists($seriesId, $this->seriesNumbers) ? $this->seriesNumbers[$seriesId] : null;
    }

    /**
     * Assigns timestamp of last modification to document as tracked in search
     * index.
     *
     * @note This information is temporarily overloading related timestamp in
     *       local document.
     *
     * @param {int} $timestamp Unix timestamp of last modification tracked in search index
     * @return $this fluent interface
     */
    public function setServerDateModified($timestamp)
    {
        if (!is_null($this->serverDateModified)) {
            throw new RuntimeException('timestamp of modification has been set before');
        }

        //$this->serverDateModified = new Opus_Date();
        //$this->serverDateModified = Carbon::createFromTimestamp($timestamp);
        $this->serverDateModified = Carbon::createFromTimestamp($timestamp)->toDateTimeString();

        // if ( ctype_digit( $timestamp = trim( $timestamp ) ) ) {
        // $this->serverDateModified->setUnixTimestamp( intval( $timestamp ) );
        // } else {
        // $this->serverDateModified->setFromString( $timestamp );
        // }

        return $this;
    }

    /**
     * Provides timestamp of last modification preferring value provided by
     * search engine over value stored locally in document.
     *
     * @note This method is used by Opus to detect outdated records in search
     *       index.
     *
     * @return string //old Opusdate
     */
    public function getServerDateModified()
    {
        if (!is_null($this->serverDateModified)) {
            return $this->serverDateModified;
        }

        return $this->getDocument()->getServerDateModified();
    }

    public function setFulltextIDsSuccess($value)
    {
        if (!is_null($this->fulltextIdSuccess)) {
            throw new RuntimeException('successful fulltext IDs have been set before');
        }
        $this->fulltextIdSuccess = $value;

        return $this;
    }

    public function getFulltextIDsSuccess()
    {
        if (!is_null($this->fulltextIdSuccess)) {
            return $this->fulltextIdSuccess;
        }
        return null;
    }

    public function setFulltextIDsFailure($value)
    {
        if (!is_null($this->fulltextIdFailure)) {
            throw new RuntimeException('failed fulltext IDs have been set before');
        }
        $this->fulltextIdFailure = $value;
        return $this;
    }

    public function getFulltextIDsFailure()
    {
        if (!is_null($this->fulltextIdFailure)) {
            return $this->fulltextIdFailure;
        }
        return null;
    }

    /**
     * Passes all unknown method invocations to related instance of
     * Opus_Document.
     *
     * @param string $method name of locally missing/protected method
     * @param mixed[] $args arguments used on invoking that method
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->getDocument(), $method), $args);
    }

    /**
     * Passes access on locally missing/protected property to related instance
     * of Opus_Document.
     *
     * @param string $name name of locally missing/protected property
     * @return mixed value of property
     */
    public function __get($name)
    {
        return $this->getDocument()->{$name};
    }

    /**
     * Attaches named asset to current match.
     *
     * Assets are additional information on match provided by search engine.
     *
     * @param string $name
     * @param mixed $value
     * @return $this fluent interface
     */
    public function setAsset($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * Retrieves selected asset attached to current match or null if asset was
     * not assigned to match.
     *
     * @param string $name
     * @return mixed|null
     */
    public function getAsset($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Tests if selected asset has been attached to current match.
     *
     * @param string $name name of asset to test
     * @return bool true if asset was assigned to current match
     */
    public function hasAsset($name) : bool
    {
        return array_key_exists($name, $this->data);
    }
}
