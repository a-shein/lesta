<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Http\Request;
use Tests\TestCase;

class FibonacciControllerTest extends TestCase
{
    public function testGetFibonacciWithoutRequestParams(): void
    {
        $response = $this->get('/fibonacci');
        $response->assertStatus(200);
    }

    public function testGetFibonacciValidateFailed(): void
    {
        $response = $this->json(
            Request::METHOD_GET,
            '/fibonacci',
            ['from' => 1],
        );

        $response->assertJsonValidationErrors([
            'to' => 'The to field is required when from is present.',
        ]);

        $response = $this->json(
            Request::METHOD_GET,
            '/fibonacci',
            ['from' => 3, 'to' => 2],
        );
        $response->assertJsonValidationErrors([
            'to' => 'The to must be greater than or equal to 3.',
        ]);
    }

    public function testGetFibonacci(): void
    {
        $response = $this->json(
            Request::METHOD_GET,
            '/fibonacci',
            ['from' => 1, 'to' => 4],
        );

        $response->assertOk();
        $response->assertSeeText('Числа Фибоначчи в выбранном диапазоне: 1, 1, 2, 3');
    }
}
