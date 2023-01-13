<?php

namespace App\Support\Controller\Dashboard;

use App\Document;

/**
 * Trait DashboardExpressPostHelper
 *
 * @package App\Support\Controller\Dashboard
 */
trait DashboardExpressPostHelper
{
    /**
     * @return array
     */
    private function getExpressPostStatuses()
    {
        return [
            'invoiced' => get_codebook_opts('document_status')->where('code', 'invoiced')->first()->name,
            'express_post' => get_codebook_opts('document_status')->where('code', 'express_post')->first()->name,
            'shipped' => get_codebook_opts('document_status')->where('code', 'shipped')->first()->name,
            'express_post_in_process' => get_codebook_opts('document_status')->where('code', 'express_post_in_process')->first()->name,
            'delivered' => get_codebook_opts('document_status')->where('code', 'delivered')->first()->name,
            'returned' => get_codebook_opts('document_status')->where('code', 'returned')->first()->name,
            'express_post_canceled' => get_codebook_opts('document_status')->where('code', 'express_post_canceled')->first()->name,
        ];
    }
}
