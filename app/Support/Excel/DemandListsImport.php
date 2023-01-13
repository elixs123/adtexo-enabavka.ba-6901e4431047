<?php

namespace App\Support\Excel;

use Maatwebsite\Excel\Files\ExcelFile;

/**
 * Class DemandListsImport
 *
 * @package App\Support\Excel
 */
class DemandListsImport extends ExcelFile
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
        return storage_path('app/demands_20211111.xlsx');
    }
    
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            'chunk'
        ];
    }
}
