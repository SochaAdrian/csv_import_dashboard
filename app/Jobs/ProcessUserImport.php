<?php

namespace App\Jobs;

use App\Imports\EachRowUserImport;
use App\Models\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\ImportCompletedMail;

class ProcessUserImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $import;

    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    private function dispatchEachRowJob(\Illuminate\Support\Collection $collection): void
    {
        $rowNumber = 1;
        foreach($collection as $row){
            foreach($row as $user){
                //skip header
                if($rowNumber === 1){
                    $rowNumber++;
                    continue;
                }
                ProcessEachUserJob::dispatch($this->import->id, $user->toArray(), $rowNumber);
                $rowNumber++;
            }
        }
    }

    public function handle()
    {
        $this->import->update(['status' => 'processing']);

        try {
            //In normal circumstances, we would use the following line to import the file:
            //Excel::import(new UsersImport($this->import->id), $this->import->file_name, null, \Maatwebsite\Excel\Excel::CSV);

            //However, for the sake of this example and that we need to process every row individually, we will use the following code:
            $collection = Excel::toCollection(new EachRowUserImport($this->import->id), $this->import->file_name, null, \Maatwebsite\Excel\Excel::CSV);
            $this->dispatchEachRowJob($collection);
            $this->import->update(['status' => 'completed']);

            try {
                Mail::to(config('app.admin_mail', 'test@test.pl'))->send(new ImportCompletedMail($this->import));
            } catch (\Exception $e) {
                Log::error('Email sending failed: '.$e->getMessage());
            }

        } catch (\Exception $e) {
            $this->import->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
        }
    }
}
