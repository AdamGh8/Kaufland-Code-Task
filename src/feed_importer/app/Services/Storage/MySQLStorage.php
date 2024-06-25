<?php

namespace App\Services\Storage;

use App\Contracts\StorageInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Log;

class MySQLStorage implements StorageInterface
{
    public function __construct()
    {
        try {
            // Switch DB driver to MySQL
            $capsule = new Capsule;
            $capsule->addConnection([
                'driver' => config('database.connections.mysql.driver'),
                'database' => config('database.connections.mysql.database'),
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'username' => config('database.connections.mysql.username'),
                'password' => config('database.connections.mysql.password'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
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
            Log::error('Error initializing MySQL: ' . $e->getMessage());
            throw new \Exception('Error initializing MySQL.');
        }
    }

    // Insert an item into the database
    public function storeItem(array $item)
    {
        try {
            Capsule::table('products')->insert($item);
        } catch (\Exception $e) {
            Log::error('Error storing item in MySQL: ' . $e->getMessage(), ['item' => $item]);
            throw new \Exception('Error storing item in MySQL');
        }
    }
}
