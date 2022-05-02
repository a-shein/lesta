<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\FibonacciService;

use Predis\ClientInterface;
use ReflectionClass;
use Tests\TestCase;

class FibonacciServicesTest extends TestCase
{
    private ClientInterface $redis;
    private FibonacciService $fibonacciService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redis = $this->getMockBuilder(ClientInterface::class)
            ->onlyMethods([
                'getProfile',
                'getOptions',
                'connect',
                'disconnect',
                'getConnection',
                'createCommand',
                'executeCommand',
                '__call',
            ])
            ->addMethods(['get', 'set'])->getMock();
        $this->fibonacciService = new FibonacciService($this->redis);
    }

    public function testConstruct(): void
    {
        $reflectionTestedClass = new ReflectionClass($this->fibonacciService);
        $property = $reflectionTestedClass->getProperty('redis');
        $property->setAccessible(true);

        $this->assertEquals($this->redis, $property->getValue($this->fibonacciService));
    }

    /**
     * @dataProvider getFibonacciListDataProvider
     */
    public function testGetFibonacciList(
        int $from,
        int $to,
        ?string $redisData,
        array $fibonacciList,
        array $expectedResult
    ): void {
        $this->redis->expects($this->once())->method('get')->willReturn($redisData);
        $this->redis->expects($this->once())->method('set');
        $fibonacciServiceMock = $this->getMockBuilder(FibonacciService::class)
            ->setConstructorArgs([$this->redis])
            ->onlyMethods(['calculateFibonacciList', 'getFibonacciListInRange'])
            ->getMock();
        $fibonacciServiceMock->expects($this->once())
            ->method('calculateFibonacciList')
            ->willReturn($fibonacciList);
        $fibonacciServiceMock->expects($this->once())
            ->method('getFibonacciListInRange')
            ->willReturn($expectedResult);

        $actualResult = $fibonacciServiceMock->getFibonacciList($from, $to);

        $this->assertEquals($actualResult, $expectedResult);
    }

    public function testGetFibonacciListInRange():void
    {
        $reflectionTestedClass = new ReflectionClass($this->fibonacciService);
        $method = $reflectionTestedClass->getMethod('getFibonacciListInRange');
        $method->setAccessible(true);

        $list = [0, 1, 1, 2, 3, 5, 8, 13, 21];

        $actualResult = $method->invoke($this->fibonacciService, 3, 20, $list);

        $this->assertEquals([3, 5, 8, 13], array_values($actualResult));
    }

    /**
     * @dataProvider calculateFibonacciListDataProvider
     */
    public function testCalculateFibonacciList(
        int $to,
        ?array $data,
        array $expectedResult
    ): void {
        $reflectionTestedClass = new ReflectionClass($this->fibonacciService);
        $method = $reflectionTestedClass->getMethod('calculateFibonacciList');
        $method->setAccessible(true);

        $actualResult = $method->invoke($this->fibonacciService, $to, $data);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function getFibonacciListDataProvider(): array
    {
        return [
            [
                1,
                25,
                null,
                [0, 1, 1, 2, 3, 5, 8, 13, 21],
                [1, 1, 2, 3, 5, 8, 13, 21],
            ],
            [
                1,
                25,
                "[0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89]",
                [0, 1, 1, 2, 3, 5, 8, 13, 21],
                [1, 1, 2, 3, 5, 8, 13, 21],
            ]
        ];
    }

    public function calculateFibonacciListDataProvider(): array
    {
        return [
            [
                0,
                null,
                [0],
            ],
            [
                1,
                null,
                [0, 1],
            ],
            [
                2,
                null,
                [0, 1, 1, 2],
            ],
            [
                4,
                [0, 1, 1, 2],
                [0, 1, 1, 2, 3],
            ]
        ];
    }
}
