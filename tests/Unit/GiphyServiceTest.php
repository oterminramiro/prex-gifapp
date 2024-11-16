<?php

namespace Tests\Unit;

use App\Services\GiphyService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use TypeError;

class GiphyServiceTest extends TestCase
{
    protected GiphyService $giphyService;

    public function setUp(): void
    {
        parent::setUp();

        $this->giphyService = new GiphyService();
    }

    public function test_it_can_return_a_valid_response(): void
    {
        $response = file_get_contents(__DIR__ . '/giphyResponse.json');
        Http::fake([
            '*' => Http::response($response)
        ]);

        $result = $this->giphyService->search(['query' => 'messi']);

        $this->assertEquals(json_decode($response, true)['data'], $result);
    }

    public function test_it_throws_an_exception_if_response_is_not_valid(): void
    {
        Http::fake([
            '*' => Http::response()
        ]);

        $this->expectException(HttpException::class);

        $this->giphyService->search(['query' => 'messi']);
    }

    public function test_it_throws_an_exception_if_param_is_not_valid(): void
    {
        Http::fake([
            '*' => Http::response()
        ]);

        $this->expectException(TypeError::class);

        $this->giphyService->search('messi');
    }
}
