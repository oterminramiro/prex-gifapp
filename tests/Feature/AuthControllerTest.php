<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_it_returns_a_successful_response_on_login(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'token',
                ],
            ]);
    }

    public function test_it_returns_a_validation_error_when_email_does_not_match_the_format(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ],
            ])
            ->assertJsonPath('message', 'Validation error');
    }

    public function test_it_returns_a_bad_request_when_user_is_not_found(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'missing@example.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'data',
            ])
            ->assertJsonPath('status', false)
            ->assertJsonPath('data', 'User does not exists');
    }

    public function test_it_returns_a_bad_request_when_password_does_not_match(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'passworddd',
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'data',
            ])
            ->assertJsonPath('status', false)
            ->assertJsonPath('data', 'Invalid password');
    }
}
