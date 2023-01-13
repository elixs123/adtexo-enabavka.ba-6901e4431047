<?php

namespace App\Jobs;

use App\Support\Controller\ExpressPostHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ExpressPostTrackStatusJob
 *
 * @package App\Jobs
 */
class ExpressPostTrackStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ExpressPostHelper;
    
    /**
     * @var array|\Illuminate\Support\Collection|mixed
     */
    private $documents;
    
    /**
     * ExpressPostTrackStatusJob constructor.
     *
     * @param array|\Illuminate\Support\Collection|mixed $documents
     */
    public function __construct($documents)
    {
        $this->documents = $documents;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        foreach ($this->documents as $document) {
            $express_post = $document->rExpressPost;
            
            if ($express_post) {
                $this->shipmentTraces($document, $express_post);
            }
        }
        
        Log::info('ExpressPostTrackStatusJob: ' . implode(', ', $this->documents->pluck('id')->toArray()));
    }
}
