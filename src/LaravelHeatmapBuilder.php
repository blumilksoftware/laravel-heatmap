<?php

declare(strict_types=1);

namespace Blumilk\Heatmap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use blumilk\heatmapBuilder\HeatmapBuilder;

class LaravelHeatmapBuilder extends HeatmapBuilder
{
    /**
     * Build heatmap data using an Eloquent model class.
     *
     * @param string $modelClass
     * @param string $dateAttribute
     * @return array
     */
    public function buildFromModel(string $modelClass, string $dateAttribute): array
    {
        if (!is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException("The given class must be an instance of " . Model::class);
        }

        $data = $modelClass::all();

        $this->changeArrayAccessIndex($dateAttribute);

        return $this->build($data);
    }

    /**
     * Build heatmap data using an Eloquent collection.
     *
     * @param Collection $collection
     * @param string $dateAttribute
     * @return array
     */
    public function buildFromCollection(Collection $collection, string $dateAttribute): array
    {
        $this->changeArrayAccessIndex($dateAttribute);

        return $this->build($collection);
    }
}