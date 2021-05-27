<?php

namespace App\Observers;

//php artisan make:observer DatasetObserver --model=Models\Dataset
use App\Models\Dataset;
use Illuminate\Support\Facades\Log;
use App\Library\Search\SolariumAdapter;
use \Exception;

class DatasetObserver
{
    /**
     * Handle the dataset "created" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function created(Dataset $dataset)
    {
        //
    }

    /**
     * Handle the dataset "updated" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function updated(Dataset $dataset)
    {
        

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
     * Handle the dataset "deleted" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function deleted(Dataset $dataset)
    {
        //
    }

    /**
     * Handle the dataset "restored" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function restored(Dataset $dataset)
    {
        //
    }

    /**
     * Handle the dataset "force deleted" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function forceDeleted(Dataset $dataset)
    {
        //
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
            Log::error(__METHOD__ . ': ' . 'Indexing document ' . $dataset->id . ' failed: ' . $e->getMessage());
        }
    }
}
