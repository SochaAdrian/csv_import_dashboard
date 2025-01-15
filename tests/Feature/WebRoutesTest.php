<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class WebRoutesTest extends TestCase
{
    public function test_if_has_import_routes()
    {
        $this->actingAs(User::factory()->create());
        $this->get(route('import.index'))->assertStatus(200);
        $this->get(route('import.dashboard'))->assertStatus(200);
        $this->get(route('import.logs'))->assertStatus(200);
        $this->post(route('import.file'))->assertStatus(302); // Expecting a redirect after submission
    }
}
