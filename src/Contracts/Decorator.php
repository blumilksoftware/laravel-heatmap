<?php

declare(strict_types=1);

namespace Blumilk\LaravelHeatmap\Contracts;

use Blumilk\LaravelHeatmap\Tile;

interface Decorator
{
    /**
     * @param array<Tile> $bucket
     * @return array<Tile>
     */
    public function decorate(array $bucket): array;
}
