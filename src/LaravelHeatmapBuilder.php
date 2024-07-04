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
    /**
     * @param string|Builder|Collection $data
     */
    public function build($data, string $arrayAccessIndex = self::DEFAULT_ARRAY_ACCESS_INDEX): array
    {
        $this->arrayAccessIndex = $arrayAccessIndex;

        if (is_string($data) && class_exists($data) && is_subclass_of($data, Model::class)) {
            $data = $data::all();
        } elseif ($data instanceof Builder) {
            $data = $data->get();
        }

        if (!$data instanceof Collection) {
            throw new InvalidArgumentException("Data must be a class name, query builder, or collection of Eloquent models.");
        }

        return parent::build($data);
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
