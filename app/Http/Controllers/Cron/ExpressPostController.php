<?php

namespace App\Http\Controllers\Cron;

use App\Document;
use App\Http\Controllers\Controller;
use App\Jobs\ExpressPostTrackStatusJob;

/**
 * Class ExpressPostController
 *
 * @package App\Http\Controllers\Cron
 */
class ExpressPostController extends Controller
{
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * ExpressPostController constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
    
    /**
     * @return string
     */
    public function status()
    {
        $this->document->typeId = 'order';
        $this->document->statusId = ['shipped', 'express_post_in_process'];
        $this->document->limit = null;
        $documents = $this->document->relation(['rExpressPost.rExpressPostEvents', 'rExpressPost.rStock'])->getAll();
        
        foreach ($documents->chunk(5) as $_documents) {
            ExpressPostTrackStatusJob::dispatch($_documents);
        }
        
        return 'OK';
    }
}
