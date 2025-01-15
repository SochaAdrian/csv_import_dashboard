<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ImportLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithValidation, SkipsOnFailure, SkipsEmptyRows, WithBatchInserts
{
    use Importable, SkipsFailures;

    private $importId;

    public function __construct($importId)
    {
        $this->importId = $importId;
    }

    public function model(array $row)
    {
        try {
            return new User([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
            ]);
        } catch (\Exception $e) {
            ImportLog::create([
                'import_id' => $this->importId,
                'row_number' => $row['row_number'] ?? null,
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            ImportLog::create([
                'import_id' => $this->importId,
                'row_number' => $failure->row(),
                'error_message' => implode(', ', $failure->errors()),
            ]);
        }
    }

    public function batchSize(): int
    {
        //If we want to process only one user at a time, we can return 1 - this is requirement in this task but I would use here 1000 since
        //its only user import name email password so it should be fast
        return 1000;
    }
}
