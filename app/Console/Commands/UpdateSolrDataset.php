<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dataset;
use App\Library\Search\SolariumAdapter;
use \Exception;

class UpdateSolrDataset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:dataset {dataset : The ID of the dataset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update solr dataset with given ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $datasetId = $this->argument('dataset');
        $dataset = Dataset::find($datasetId);
        // $this->info($dataset->authors->implode('full_name', ', '));

        try {
            // Opus_Search_Service::selectIndexingService('onDocumentChange')
            $service = new SolariumAdapter("solr", config('solarium'));
            $service->addDatasetsToIndex($dataset);
        } catch (Exception $e) {
            $this->error(__METHOD__ . ': ' . 'Indexing document ' . $dataset->id . ' failed: ' . $e->getMessage());
        }
    }
}
