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

    /**
     * Test Empty array.
     */
    public function test_get_faults_empty()
    {
        $response = $this->getJson('/api/faults');

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);
    }

     /**
     * Test with data.
     */
    public function test_get_faults_with_data()
    {
        \App\Models\Fault::factory()->count(2)->create();

        $response = $this->getJson('/api/faults');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'fault_reference',
                        'incident_title',
                        'category_id',
                        'lat',
                        'long',
                        'incident_time',
                        'created_at',
                        'updated_at',
                        'persons'
                    ]
                ]
            ]);
    }

     /**
     * Test Post success.
     */
    public function test_create_fault_success()
    {
        $payload = [
            'location' => [
                'lat' => 1.1,
                'long' => 103.1
            ],
            'incident_title' => 'Test Incident',
            'category_id' => 1,
            'incident_time' => now()->toISOString(),
            'people_involved' => [
                [
                    'name' => 'John',
                    'type' => 'staff'
                ]
            ]
        ];

        $response = $this->postJson('/api/faults', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'fault_reference',
                'incident_title',
                'created_at',
                'updated_at'
            ]);
    }    

     /**
     * Test auto timestamps .
     */
    public function test_fault_auto_timestamps()
    {
        $payload = [
            'location' => ['lat' => 1.1, 'long' => 103.1],
            'incident_title' => 'Test',
            'category_id' => 1,
            'incident_time' => now()->toISOString()
        ];

        $response = $this->postJson('/api/faults', $payload);

        $data = $response->json();

        $this->assertNotNull($data['created_at']);
        $this->assertNotNull($data['updated_at']);
    }

     /**
     * Fault reference auto increment.
     */
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

     /**
     * Test Invalid category.
     */
    public function test_invalid_category()
    {
        $payload = [
            'location' => ['lat' => 1.1, 'long' => 103.1],
            'incident_title' => 'Test',
            'category_id' => 99,
            'incident_time' => now()->toISOString()
        ];

        $response = $this->postJson('/api/faults', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    }
    
    /**
     * Test Invalid location.
     */
    public function test_invalid_location()
    {
        $payload = [
            'incident_title' => 'Test',
            'category_id' => 1,
            'incident_time' => now()->toISOString()
        ];

        $response = $this->postJson('/api/faults', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['location.lat', 'location.long']);
    }

    /**
     * Test Invalid incident time.
     */
    public function test_invalid_incident_time()
    {
        $payload = [
            'location' => ['lat' => 1.1, 'long' => 103.1],
            'incident_title' => 'Test',
            'category_id' => 1,
            'incident_time' => 'invalid-date'
        ];

        $response = $this->postJson('/api/faults', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['incident_time']);
    }

    /**
     * Test invalid people type.
     */
    public function test_invalid_people_type()
    {
        $payload = [
            'location' => ['lat' => 1.1, 'long' => 103.1],
            'incident_title' => 'Test',
            'category_id' => 1,
            'incident_time' => now()->toISOString(),
            'people_involved' => [
                [
                    'name' => 'John',
                    'type' => 'invalid'
                ]
            ]
        ];

        $response = $this->postJson('/api/faults', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['people_involved.0.type']);
    }

    public function test_validation_error()
    {
        $this->postJson('/api/faults', [])
            ->assertStatus(422);
    }
}
