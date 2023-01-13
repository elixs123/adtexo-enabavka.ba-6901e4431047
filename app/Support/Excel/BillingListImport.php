<?php

namespace App\Support\Excel;

use Maatwebsite\Excel\Files\ExcelFile;

/**
 * Class BillingListImport
 *
 * @package App\Support\Excel
 */
class BillingListImport extends ExcelFile
{
    /**
     * @var null
     */
    protected $encoding = 'UTF-8';
    
    /**
     * @return string
     */
    public function getFile()
    {
        return storage_path('app/billings_20211111.xlsx');
    }
}
