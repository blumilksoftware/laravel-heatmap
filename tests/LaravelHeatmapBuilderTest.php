<?php

declare(strict_types=1);

namespace Blumilk\LaravelHeatmap\Tests;

use Blumilk\Heatmap\PeriodInterval;
use Blumilk\LaravelHeatmap\LaravelHeatmapBuilder;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Orchestra\Testbench\TestCase;
use Blumilk\Heatmap\Tile;

class LaravelHeatmapBuilderTest extends TestCase
{
    public function testBuildFromArray(): void
    {
        $builder = new LaravelHeatmapBuilder(
            now: Carbon::parse("2022-11-18"),
            periodInterval: PeriodInterval::Daily,
            timezone: new CarbonTimeZone("1")
        );

        $result = $builder->buildFromArray($this->getData());

        $this->assertSame(
            expected: [0, 0, 0, 0, 0, 2, 0, 1],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    public function testBuildFromArrayWithIndexChanged(): void
    {
        $builder = new LaravelHeatmapBuilder(
            now: Carbon::parse("2022-11-18"),
            periodInterval: PeriodInterval::Daily,
            timezone: new CarbonTimeZone("1")
        );

        $result = $builder->buildFromArray($this->getData(), 'updated_at');

        $this->assertSame(
            expected: [0, 0, 0, 0, 0, 2, 0, 0],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    public function testBuildFromCollection(): void
    {
        $builder = new LaravelHeatmapBuilder(
            now: Carbon::parse("2022-11-18"),
            periodInterval: PeriodInterval::Daily,
            timezone: new CarbonTimeZone("1")
        );

        $result = $builder->buildFromCollection(collect($this->getData()));

        $this->assertSame(
            expected: [0, 0, 0, 0, 0, 2, 0, 1],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    public function testBuildFromCollectionWithIndexChanged(): void
    {
        $builder = new LaravelHeatmapBuilder(
            now: Carbon::parse("2022-11-18"),
            periodInterval: PeriodInterval::Daily,
            timezone: new CarbonTimeZone("1")
        );

        $result = $builder->buildFromCollection(collect($this->getData()), 'updated_at');

        $this->assertSame(
            expected: [0, 0, 0, 0, 0, 2, 0, 0],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    protected function getData(): array
    {
        return [
            ["created_at" => "2022-11-01 00:00:00", "updated_at" => "2022-11-01 00:00:00"],
            ["created_at" => "2022-11-03 00:00:00", "updated_at" => "2022-11-03 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00", "updated_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00", "updated_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-18 00:00:00", "updated_at" => "2022-11-19 00:00:00"],
            ["created_at" => "2022-11-19 00:00:00", "updated_at" => "2022-11-19 00:00:00"],
        ];
    }
}