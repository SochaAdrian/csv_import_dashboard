<?php

namespace App\Jobs;

use App\Models\ImportLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProcessEachUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $import;
    private $row;

    private $rowNumber;

    public function __construct($import, $row, $rowNumber)
    {
        $this->import = $import;
        $this->row = $row;
        $this->rowNumber = $rowNumber;
    }

    public function handle()
    {
        try {
            $validatedData = $this->validateData($this->row);

            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'imported_with_import_id' => $this->import,
            ]);
        } catch (\Exception $e) {
            ImportLog::create([
                'import_id' => $this->import,
                'row_number' =>  $this->rowNumber,
                'error_message' => $e->getMessage(),
                'value' => json_encode($this->row),
            ]);
        }
    }

    private function validateData(array $data)
    {
        //associate data with keys for more readable validation
        $data = array_combine(['name', 'email', 'password'], $data);
        return validator($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ])->validate();
    }
}
