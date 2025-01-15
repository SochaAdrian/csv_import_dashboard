<?php

namespace Tests\Unit;

use App\Jobs\ProcessEachUserJob;
use App\Models\Import;
use App\Models\ImportLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProcessEachUserJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_processes_valid_user_rows()
    {
        $import = Import::factory()->create();
        $importId = $import->id;
        $row = ['John Doe', 'john.doe@example.com', 'password123'];
        $rowNumber = 1;

        $job = new ProcessEachUserJob($importId, $row, $rowNumber);
        $job->handle();

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
    }

    /** @test */
    public function it_logs_errors_for_invalid_rows()
    {
        $importId = 1;
        $row = ['', 'invalid-email', 'short'];
        $rowNumber = 1;

        $job = new ProcessEachUserJob($importId, $row, $rowNumber);
        $job->handle();

        $this->assertDatabaseHas('import_logs', [
            'import_id' => $importId,
            'row_number' => $rowNumber,
        ], 'sqlite_import_logs');
    }
}
