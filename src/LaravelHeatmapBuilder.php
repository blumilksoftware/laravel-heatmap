<?php

declare(strict_types=1);

namespace Blumilk\LaravelHeatmap;

use ArrayAccess;
use Blumilk\Heatmap\Contracts\TimeGroupable;
use Blumilk\Heatmap\HeatmapBuilder;
use Blumilk\Heatmap\PeriodInterval;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class LaravelHeatmapBuilder extends HeatmapBuilder
{
    public function buildFromCollection(Collection $data, string $arrayAccessIndex = self::DEFAULT_ARRAY_ACCESS_INDEX): array
    {
        $this->arrayAccessIndex = $arrayAccessIndex;

        return parent::build($data);
    }

    public function buildFromArray(array $data, string $arrayAccessIndex = self::DEFAULT_ARRAY_ACCESS_INDEX): array
    {
        $this->arrayAccessIndex = $arrayAccessIndex;
        $collection = collect($data);

        return parent::build($collection);
    }

    public function buildFromQuery(Builder $query, string $arrayAccessIndex = self::DEFAULT_ARRAY_ACCESS_INDEX): array
    {
        if (!is_a($query, Builder::class)) {
            throw new InvalidArgumentException("The provided query is not a valid Eloquent Builder instance.");
        }

        $this->arrayAccessIndex = $arrayAccessIndex;

        $results = $query->get();

        $collection = $results instanceof Collection ? $results : collect($results);

        return $this->buildFromCollection($collection, $arrayAccessIndex);
    }

    /**
     * @param iterable<array|ArrayAccess|TimeGroupable|Model> $data
     */
    protected function mapDataToCarbonElements(iterable $data): array
    {
        return array_map(
            callback: fn(array|ArrayAccess|TimeGroupable|Model $item): Carbon => $this->mapToCarbon($item),
            array: [...$data],
        );
    }

    protected function mapToCarbon(array|ArrayAccess|TimeGroupable|Model $item): Carbon
    {
        if ($item instanceof TimeGroupable) {
            $date = Carbon::parse($item->getTimeGroupableIndicator());
        } elseif ($item instanceof Model) {
            if (!isset($item->{$this->arrayAccessIndex})) {
                throw new InvalidArgumentException("The property '{$this->arrayAccessIndex}' does not exist on the given model.");
            }
            $date = Carbon::parse($item->{$this->arrayAccessIndex});
        } else {
            if (!isset($item[$this->arrayAccessIndex])) {
                throw new InvalidArgumentException("The key '{$this->arrayAccessIndex}' does not exist in the given array.");
            }
            $date = Carbon::parse($item[$this->arrayAccessIndex]);
        }

        $date->setTimezone($this->timezone);

        return match ($this->periodInterval) {
            PeriodInterval::Monthly => $date->startOfMonth(),
            PeriodInterval::Weekly => $date->startOfWeek(),
            PeriodInterval::Daily => $date->startOfDay(),
            PeriodInterval::Hourly => $date->startOfHour(),
        };
    }
}
