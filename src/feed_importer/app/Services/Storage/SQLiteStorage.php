<?php

namespace App\Services\Storage;

use App\Contracts\StorageInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Log;

class SQLiteStorage implements StorageInterface
{
    public function __construct()
    {
        try {
            // Switch DB driver to SQLite
            $capsule = new Capsule;
            $capsule->addConnection([
                'driver' => config('database.connections.sqlite.driver'),
                'database' => config('database.connections.sqlite.database'),
                'prefix' => '',
            ]);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            // Truncate the current products table
            if (!Capsule::schema()->hasTable('products')) {
                Log::warning('Table `products` does not exist. Please run php artisan migrate and try again');
            } else {
                Capsule::table('products')->truncate();
            }

        } catch (\Exception $e) {
            Log::error('Error initializing SQLite: ' . $e->getMessage());
            throw new \Exception('Error initializing SQLite');
        }
    }

    // Insert an item into the database
    public function storeItem(array $item)
    {
        try {
            Capsule::table('products')->insert($item);
        } catch (\Exception $e) {
            Log::error('Error storing item in SQLite: ' . $e->getMessage(), ['item' => $item]);
            throw new \Exception('Error storing item in SQLite');
        }
    }
}
