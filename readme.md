![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/blumilksoftware/laravel-heatmap?style=for-the-badge) ![Packagist Version](https://img.shields.io/packagist/v/blumilksoftware/laravel-heatmap?style=for-the-badge) ![Packagist Downloads](https://img.shields.io/packagist/dt/blumilksoftware/laravel-heatmap?style=for-the-badge)

## About
blumilksoftware\laravel-heatmap is an extension of blumilksoftware\heatmap allowing you to use heatmap with Model data from Laravel Eloquent.

### Installation
The Laravel Heatmap is distributed as Composer library via Packagist. To add it to your project, just run:
```
composer require blumilksoftware/laravel-heatmap
```

### Usage
#### Creating a heatmap from Laravel Collection
You can build a heatmap directly from collection of Eloquent models:
```php
use Blumilk\LaravelHeatmap\LaravelHeatmapBuilder;
use App\Models\YourModel;

$data = YourModel::all(); // Assuming YourModel has a 'created_at' field

$heatmapBuilder = new LaravelHeatmapBuilder();
$heatmap = $heatmapBuilder->buildFromCollection($data, 'created_at');
```
You can also build heatmap from array:
```php
use Blumilk\LaravelHeatmap\LaravelHeatmapBuilder;

$data = [
    ["created_at" => "2022-11-01 00:00:00"],
    ["created_at" => "2022-11-03 00:00:00"],
    ["created_at" => "2022-11-16 00:00:00"],
    ["created_at" => "2022-11-16 00:00:00"],
    ["created_at" => "2022-11-18 00:00:00"],
    ["created_at" => "2022-11-19 00:00:00"],
];

$heatmapBuilder = new LaravelHeatmapBuilder();
$heatmap = $heatmapBuilder->buildFromArray($data, 'created_at');
```
