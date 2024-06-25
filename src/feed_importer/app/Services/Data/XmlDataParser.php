<?php
namespace App\Services\Data;

use App\Contracts\DataParserInterface;

class XmlDataParser implements DataParserInterface
{
    public function parse(string $data): array
    {
        if (!file_exists($data)) {
            throw new \Exception("File not found: $data");
        }

        $xml = simplexml_load_file($data);
        if (!$xml) {
            throw new \Exception("Error loading XML file");
        }

        $items = [];
        foreach ($xml->item as $item) {
            $items[] = [
                'entity_id' => isset($item->entity_id) ? (int) $item->entity_id : null,
                'category_name' => isset($item->CategoryName) ? (string) $item->CategoryName : null,
                'sku' => isset($item->sku) ? (int) $item->sku : null,
                'name' => isset($item->name) ? (string) $item->name : null,
                'description' => isset($item->description) ? (string) $item->description : null,
                'shortdesc' => isset($item->shortdesc) ? (string) $item->shortdesc : null,
                'price' => isset($item->price) ? (int) $item->price * pow(10, config('product.floating_point_multiplier')) : null,
                'link' => isset($item->link) ? (string) str_replace(["\r", "\n", " "], '', $item->link) : null,
                'image' => isset($item->image) ? (string) str_replace(["\r", "\n", " "], '', $item->image) : null,
                'brand' => isset($item->Brand) ? (string) $item->Brand : null,
                'rating' => isset($item->Rating) ? (int) $item->Rating : null,
                'caffeine_type' => isset($item->CaffeineType) ? (string) $item->CaffeineType : null,
                'count' => isset($item->Count) ? (int) $item->Count : null,
                'flavored' => isset($item->Flavored) ? (bool) filter_var($item->Flavored, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null,
                'seasonal' => isset($item->Seasonal) ? (bool) filter_var($item->Seasonal, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null,
                'instock' => isset($item->Instock) ? (bool) filter_var($item->Instock, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null,
                'facebook' => isset($item->Facebook) ? (bool) filter_var($item->Facebook, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null,
                'is_kcup' => isset($item->IsKCup) ? (bool) filter_var($item->IsKCup, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ];
        }
        return $items;
    }
}
