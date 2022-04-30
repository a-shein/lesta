<?php

declare(strict_types=1);

namespace App\Services;

use Predis\Client;

class FibonacciService
{
    private const REDIS_KEY = 'fibonacci';

    public function __construct(private Client $redis)
    {
    }

    /**
     * Собираем ряд Фибоначчи, в зависимости от переданных параметров в запросе.
     *
     * @param int $from
     * @param int $to
     * @return array
     */
    public function getFibonacciList(int $from, int $to): array
    {
        $redisData = $this->redis->get(self::REDIS_KEY);

        if (!$redisData) {
            $result = $this->calculateFibonacciList($to);
        } else {
            $result = $this->calculateFibonacciList($to, json_decode($redisData));
        }

        $this->redis->set(self::REDIS_KEY, json_encode($result));

        return $this->getFibonacciListInRange($from, $to, $result);
    }

    /**
     * Фильтрация переданного массива "от" , "до".
     *
     * @param int $from
     * @param int $to
     * @param array $list
     * @return array
     */
    private function getFibonacciListInRange(int $from, int $to, array $list): array
    {
        return array_filter($list, fn (int $value) => $from <= $value && $value <= $to);
    }

    /**
     * Вычисление ряда Фибоначчи, не превышающее переданное значение.
     *
     * @param int $to
     * @param array|null $data
     * @return array<int>
     */
    private function calculateFibonacciList(int $to, ?array $data = null): array
    {
        if ($to === 0) {
            return [0];
        }

        $result = [0, 1];
        if ($to === 1) {
            return $result;
        }

        if ($data) {
            $result = $data;
        }

        while (true) {
            $lastKey = array_key_last($result);
            $lastElement = $result[$lastKey];
            $secondFromLastElement = $result[$lastKey - 1];
            $sumLatestElement = $lastElement + $secondFromLastElement;

            if ($lastElement > $to || $sumLatestElement > $to) {
                break;
            }

            $result[] = $sumLatestElement;
        }

        return $result;
    }
}
