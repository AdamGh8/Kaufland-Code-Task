## Coding Task – Data Feed

This code is my Laravel implementation of the Data Importer Coding Task, the program is easily extendible to use different data storage to read data from (data sources) or to push data to (data storages). The current implementation supports xml and json as data sources, and mysql and sqlite as data storages. All Errors are logged into Laravel logger (storage/logs/laravel.log)

## Setup Instructions:
 - Clone repo
 - Launch Docker - open Terminal and cd to project path (where docker-compose and Dockerfile are located)
 - Run docker-compose build
 - Run docker-compose up -d

 - SETUP .env FILE:
 - copy .env.example to .env
 - modify MySQL DB variables to the following:
 - DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=mydatabase
   DB_USERNAME=root
   DB_PASSWORD=toor

  - Add SQLITE variables:
  - SQLITE_CONNECTION=sqlite
    SQLITE_DATABASE = "/var/www/html/feed_importer/database/database.sqlite"

 - Open shell inside docker php container (all remaining steps will be executed inside the php container shell)
  
 - CREATE SQLITE DATABASE:
 - cd feed_importer/database
 - touch database.sqlite
 - cd ..

 - INSTALL COMPOSER DEPENDENCIES:
 - composer install

 - MIGRATE DATABASES:
 - Migrate default sqlite Database: php artisan migrate
 - Change default database to mysql:  Open config/database.php and change line 18 'default' from SQLITE_CONNECTION to DB_CONNECTION
 - Migrate again: php artisan migrate
   
 - IMPORT DATA:
 - php artisan app:import-data [datasource] [datastorage]
 - Example, import data from feed.xml into SQLite storage: php artisan app:import-data feed.xml sqlite
 - Datasources could be modified/added in the datasource folder

 ## Project Structure:
  - app\Console\Commands\ImportData.php: The main Class that runs when executing the console command; handles fetching the arguments and sending them to DataImportService to initiate the chosen data storage and data parser, parses the data, then stores the processed array of items into the chosen Data Storage

  - app\Services\DataImportService.php: Handles two main tasks: binding the storage interface to the chosen data storage and returning the correct DataParser for the chosen data source's type

  - app\Contracts\StorageInterface: The main interface for Data Storages
  - app\Contracts\DataParserInterface: The main interface for Data Parsers

  - app\Services\Data\XMLDataParser: Handles processing XML data feed (implements DataParserInterface)
  - app\Services\Data\JsonDataParser: Handles processing JSON data feed (implements DataParserInterface)

  - app\Services\Storage\MySQLStorage: Handles switching the data storage to MySQL (implements StorageInterface)
  - app\Services\Storage\SQLiteStorage: Handles switching the data storage to SQLite (implements StorageInterface)

 ## Data Processing:
    - Links are cleaned from extra spaces and line breaks
    - Prices are multiplied by a floating point multiplier of power 2 and stored as Integers, this helps avoiding floating point rounding errors and represents prices more accurately. The multiplier can be configured in config/product.php
    - Inconsistent boolean values (Yes, no) are processed and stored as boolean
    - to avoid breaking import due to inconcistent data, all fields are nullable.
    - it was not provided in the description if skus have a fixed legnth or a variable legnth, therefore I did not process them with left zero padding.
