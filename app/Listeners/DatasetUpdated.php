<?php

namespace App\Listeners;

use App\Events\Dataset\DatasetUpdated as DatasetUpdatedEvent;
use App\Models\Dataset;
use Illuminate\Support\Facades\Log;
use App\Library\Search\SolariumAdapter;
use \Exception;

class DatasetUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DatasetUpdatedEvent  $event
     * @return void
     */
    public function handle(DatasetUpdatedEvent $event)
    {
        $dataset = $event->dataset;
        // only index Opus_Document instances
        if (false === ($dataset instanceof Dataset)) {
            return;
        }
        if ($dataset->server_state !== 'published') {
            // if ($dataset->getServerState() !== 'temporary') {
            //     $this->removeDocumentFromIndexById($model->getId());
            // }
            return;
        }

        $this->addDatasetToIndex($dataset);
    }

    /**
     * Helper method to add dataset to index.
     *
     * @param Opus_Document $document
     * @return void
     */
    private function addDatasetToIndex(Dataset $dataset)
    {
        $datasetId = $dataset->id;
        Log::debug(__METHOD__ . ': ' . 'Adding index job for dataset ' . $datasetId . '.');

        try {
            // Opus_Search_Service::selectIndexingService('onDocumentChange')
            $service = new SolariumAdapter("solr", config('solarium'));
            $service->addDatasetsToIndex($dataset);
        } catch (Exception $e) {
            Log::warning(__METHOD__ . ': ' . 'Indexing document ' . $dataset->id . ' failed: ' . $e->getMessage());
        }
    }
}
