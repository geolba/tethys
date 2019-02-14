<?php

namespace App\Events\Dataset;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class DatasetUpdated.
 */
class DatasetUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $dataset;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($dataset)
    {
        $this->dataset = $dataset;
    }
}
