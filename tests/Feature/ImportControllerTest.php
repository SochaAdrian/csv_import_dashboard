<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Import;

class ImportControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_csv_file_uploads()
    {
        $this->actingAs(User::factory()->create());

        $file = UploadedFile::fake()->create('test.csv', 100, 'text/csv');

        $response = $this->post(route('import.file'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('imports', ['file_name' => "imports/{$file->hashName()}"]);
    }

    /** @test */
    public function it_rejects_non_csv_file_uploads()
    {
        $this->actingAs(User::factory()->create());

        $file = UploadedFile::fake()->create('test.txt', 100, 'text/plain');

        $response = $this->post(route('import.file'), [
            'file' => $file,
        ]);

        $response->assertStatus(302)->withException(new \Exception('The file must be a file of type: csv.'));
    }
}
