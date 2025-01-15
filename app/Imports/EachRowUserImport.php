<?php

namespace App\Imports;

use App\Jobs\ProcessEachUserJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class EachRowUserImport implements ToCollection
{

    private $importId;

    public function __construct($importId)
    {
        $this->importId = $importId;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {

    }
}
