<?php

namespace App\Services;

use App\Contracts\StorageInterface;
use App\Contracts\DataParserInterface;
use App\Services\Data\JsonDataParser;
use App\Services\Data\XmlDataParser;
use App\Services\Storage\MySQLStorage;
use App\Services\Storage\SQLiteStorage;

class DataImportService
{

    protected $storage;
    public function setStorage(string $dataStorage): void
    {
        switch ($dataStorage) {
            case 'mysql':
                app()->bind(StorageInterface::class, MySQLStorage::class);
                break;
            case 'sqlite':
                app()->bind(StorageInterface::class, SQLiteStorage::class);
                break;
            default:
                throw new \InvalidArgumentException("Unsupported data storage: $dataStorage - Supported storages: mysql or sqlite");
        }
   $this->storage = app(StorageInterface::class);
}

public function getStorage(): StorageInterface
{
    return $this->storage;
}

    public function setParser(string $type): DataParserInterface
    {
        switch ($type) {
            case 'json':
                return new JsonDataParser();
            case 'xml':
                return new XmlDataParser();
            default:
                throw new \InvalidArgumentException("Unsupported data source type: $type - Supported types: json or xml");
        }
    }
}
