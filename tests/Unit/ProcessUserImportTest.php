<?php

namespace Tests\Unit;

use App\Jobs\ProcessUserImport;
use App\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessUserImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_process_each_user_job_for_each_row()
    {
        Queue::fake();

        $import = Import::factory()->create([
            'file_name' => 'test.csv',
        ]);

        $job = (new ProcessUserImport($import))->withFakeQueueInteractions();
        $job->handle();
        $job->assertNotFailed();
    }
}
