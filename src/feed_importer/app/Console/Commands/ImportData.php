<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataImportService;
use Illuminate\Support\Facades\Log;

class ImportData extends Command
{
    protected $signature = 'app:import-data {dataSource : The data source file (xml or json)} {dataStorage : The preferable Data Storage (sqlite or mysql)}';
    protected $description = 'Import Product Data into the chosen database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $dataStorage = $this->argument('dataStorage');

        $dataImportService = app(DataImportService::class);

        try {
            $dataImportService->setStorage($dataStorage);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->error($e->getMessage());
            return;
        }

        $storage = $dataImportService->getStorage();

        $dataSource = $this->argument('dataSource');
        $type = explode('.', $dataSource)[1];
        $datapath = base_path('dataSource/' . $dataSource);

        try {
            $dataParser = $dataImportService->setParser($type);
            $items = $dataParser->parse($datapath);
            foreach ($items as $item) {
                $storage->storeItem($item);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->error($e->getMessage());
        }
    }
}
