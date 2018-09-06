<?php

namespace App\Events\Pages;

use Illuminate\Queue\SerializesModels;

/**
 * Class PageUpdated.
 */
class PageUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $page;

    /**
     * @param $page
     */
    public function __construct($page)
    {
        $this->page = $page;
    }
}
