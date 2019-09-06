<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dataset;
// use Illuminate\Support\Facades\Log;
// use App\Library\Search\SolariumAdapter;

class SolrIndexBuilder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:dataset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all datasets';

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
        $datasets = Dataset::where('server_state', 'published')->get();

        // update statistics table
        foreach ($datasets as $dataset) {
            $datasetId = $dataset->id;
            $time = new \Illuminate\Support\Carbon();
            $dataset->server_date_published = $time;
            $dataset->save();
            // try {
            //     // Opus_Search_Service::selectIndexingService('onDocumentChange')
            //     $service = new SolariumAdapter("solr", config('solarium'));
            //     $service->addDatasetsToIndex($dataset);
            // } catch (Exception $e) {
            //     Log::debug(__METHOD__ . ': ' . 'Indexing document ' . $datasetId . ' failed: ' . $e->getMessage());
            // }
        }
    }
}
