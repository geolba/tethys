<?php
//php artisan make:command DatasetState
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatasetState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'state:dataset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check dataset state';

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
        // calculate new statistics
        // $datasets = DB::table('documents')
        // ->select('account_id', DB::raw('count(*) as total_posts'))
        // ->groupBy('account_id')
        // ->get();
        
        $datasets = DB::table('documents')
        ->select('id', 'account_id')
        ->where('server_state', 'approved')
        ->whereRaw("server_date_modified <  CURRENT_DATE + INTERVAL '14 day'")
        ->get();

        // update statistics table
        foreach ($datasets as $dataset) {
            // DB::table('users_statistics')
            // ->where('user_id', $dataset->user_id)
            // ->update(['total_datasets' => $dataset->total_posts]);
            DB::table('documents')
            ->where('id', $dataset->id)
            ->update([
                'reject_reviewer_note' => 'Dataset was automatically rejected because of the time limit',
                'server_state' => 'rejected_reviewer',
                'server_date_modified' => DB::raw('now()')
            ]);
        }
    }
}
