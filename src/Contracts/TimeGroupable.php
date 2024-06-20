<?php

declare(strict_types=1);

namespace Blumilk\LaravelHeatmap\Contracts;

interface TimeGroupable
{
    public function getTimeGroupableIndicator(): string;
}
