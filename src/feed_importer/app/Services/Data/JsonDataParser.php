<?php

namespace App\Services\Data;

use App\Contracts\DataParserInterface;

class JsonDataParser implements DataParserInterface
{
    public function parse(string $dataSource): array
    {
        if (!file_exists($dataSource)) {
            throw new \Exception("File not found: $dataSource");
        }

        $jsonContent = file_get_contents($dataSource);
        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error loading JSON file: " . json_last_error_msg());
        }

        // process the data
        return array_map(function ($item) {
            return [
                'entity_id' => isset($item['entity_id']) && !$this->isNullItem($item['entity_id']) ? (int) $item['entity_id'] : null,
                'category_name' => isset($item['CategoryName']) && !$this->isNullItem($item['CategoryName']) ? (string) $item['CategoryName'] : null,
                'sku' => isset($item['sku']) && !$this->isNullItem($item['sku']) ? (int) $item['sku'] : null,
                'name' => isset($item['name']) && !$this->isNullItem($item['name']) ? (string) $item['name'] : null,
                'description' => isset($item['description']) && !$this->isNullItem($item['description']) ? (string) $item['description'] : null,
                'shortdesc' => isset($item['shortdesc']) && !$this->isNullItem($item['shortdesc']) ? (string) $item['shortdesc'] : null,
                'price' => isset($item['price']) && !$this->isNullItem($item['price']) ? (int) $item['price'] * pow(10,config('product.floating_point_multiplier')) : null, // price is multiplied by a floating point multiplier to store it as an integer
                'link' => isset($item['link']) && !$this->isNullItem($item['link']) ? (string) str_replace(array("\r", "\n", " "), '', $item['link']) : null, // remove extra spaces and line breaks
                'image' => isset($item['image']) && !$this->isNullItem($item['image']) ? (string) str_replace(array("\r", "\n", " "), '', $item['image']) : null, // remove extra spaces and line breaks
                'brand' => isset($item['Brand']) && !$this->isNullItem($item['Brand']) ? (string) $item['Brand'] : null,
                'rating' => isset($item['Rating']) && !$this->isNullItem($item['Rating'])  ? (int) $item['Rating'] : null,
                'caffeine_type' => isset($item['CaffeineType']) && !$this->isNullItem($item['CaffeineType'])  ? (string) $item['CaffeineType'] : null,
                'count' => isset($item['Count']) && !$this->isNullItem($item['Count']) ? (int) $item['Count'] : null,
                'flavored' => isset($item['Flavored']) && !$this->isNullItem($item['Flavored']) ? filter_var($item['Flavored'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null, // process entry to a valid boolean value
                'seasonal' => isset($item['Seasonal']) && !$this->isNullItem($item['Seasonal']) ? filter_var($item['Seasonal'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null, // process entry to a valid boolean value
                'instock' => isset($item['Instock']) && !$this->isNullItem($item['Instock']) ? filter_var($item['Instock'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null, // process entry to a valid boolean value
                'facebook' => isset($item['Facebook']) && !$this->isNullItem($item['Facebook']) ? filter_var($item['Facebook'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null, // process entry to a valid boolean value
                'is_kcup' => isset($item['IsKCup']) && !$this->isNullItem($item['IsKCup']) ? filter_var($item['IsKCup'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ];
        }, $data);
    }

    protected function isNullItem(mixed $item){
        return is_array($item) && empty($item) ? true : false;
    }
}
