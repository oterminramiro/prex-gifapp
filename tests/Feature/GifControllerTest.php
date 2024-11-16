<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\GiphyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;

class GifControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }

    public function test_it_returns_a_successful_response_using_only_query_param(): void
    {
        $this->mockService();
        $response = $this->post('/api/gifs/search', [
            'query' => 'messi',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    public function test_it_returns_a_successful_response_using_search_and_limit_params(): void
    {
        $this->mockService();
        $response = $this->post('/api/gifs/search', [
            'query' => 'messi',
            'limit' => 5,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    public function test_it_returns_a_successful_response_using_query_and_offset_params(): void
    {
        $this->mockService();
        $response = $this->post('/api/gifs/search', [
            'query' => 'messi',
            'offset' => 1,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    public function test_it_returns_a_error_when_query_param_is_not_present(): void
    {
        $response = $this->post('/api/gifs/search', [
            'search' => 'messi',
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'query',
                ],
            ])
            ->assertJsonPath('message', 'Validation error');
    }

    public function test_it_returns_a_successful_response_finding_a_gif(): void
    {
        $this->mockService();
        $response = $this->post('/api/gifs/find', [
            'id' => 'messi',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    private function mockService()
    {
        $this->mock(
            GiphyService::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('search')->once();
            }
        );
    }
}
