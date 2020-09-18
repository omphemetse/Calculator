<?php

namespace Tests\Feature\app\Http\Controllers\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalculatorControllerTest extends TestCase
{
    public function test_one()
    {
        $response = $this->post(route('calculates.store'), [
            'expression' => '2+(3-1)*3',
        ]);

        $response->assertStatus(200)->assertJson(['results' => '8']);
    }

    public function test_two()
    {
        $payload = [
            'expression' => '(2-0)(6/2)'
        ];

        $this->json('POST', 'api/calculates', $payload)
        ->assertJson(['results' => '6'])
        ->assertStatus(200);
    }

    public function test_three()
    {
        $response = $this->post(route('calculates.store'), [
            'expression' => '6*(4/2)+3*1',
        ]);

        $response->assertStatus(200)->assertJson(['results' => '15']);
    }

    public function test_four()
    {
        $payload = [
            'expression' => '6/3-1'
        ];

        $this->json('POST', 'api/calculates', $payload)
        ->assertJson(['results' => '1'])
        ->assertStatus(200);
    }
}
