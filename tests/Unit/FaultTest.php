<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Fault;


class FaultTest extends TestCase
{
    /**
     * A basic test example.
     */
    use RefreshDatabase;

    public function test_get_faults_empty()
    {
        $this->getJson('/api/faults')
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_create_fault_success()
    {
        $payload = [
            'location' => ['lat' => 1.1, 'long' => 103.1],
            'incident_title' => 'Test Incident',
            'category_id' => 1,
            'incident_time' => now()->toISOString(),
        ];

        $this->postJson('/api/faults', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'fault_reference',
                'incident_title'
        ]);
    }

    public function test_validation_error()
    {
        $this->postJson('/api/faults', [])
            ->assertStatus(422);
    }

    public function test_invalid_category()
    {
        $payload = [
            'location' => ['lat' => 1.1, 'long' => 103.1],
            'incident_title' => 'Test',
            'category_id' => 99,
            'incident_time' => now()->toISOString(),
        ];

        $this->postJson('/api/faults', $payload)
            ->assertStatus(422);
    }

    public function test_reference_increment()
    {
        $payload = [
            'location' => ['lat' => 1.1, 'long' => 103.1],
            'incident_title' => 'Test',
            'category_id' => 1,
            'incident_time' => now()->toISOString(),
            'name' => 'John',        
            'type' => 'staff',       
            'description' => 'Test', 
        ];

        $first = $this->postJson('/api/faults', $payload)->json();
        $second = $this->postJson('/api/faults', $payload)->json();

        $this->assertNotEquals(
            $first['fault_reference'],
            $second['fault_reference']
        );
    }
}
