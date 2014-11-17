<?php

namespace BCosta;

class Benchmark
{
    const ITERATION_COUNT = 10000;

    public static function run(Callable $func, $iterationCount = self::ITERATION_COUNT)
    {
        $start = microtime(true);
        for ($i = 0; $i < $iterationCount; $i++) {
            $func();
        }
        $end = microtime(true);
        return number_format($end - $start, 3) . ' second(s)';
    }
}