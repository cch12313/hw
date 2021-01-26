<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;

class CsvImportRepository
{
    public $pdo;
    private $table = 'import_csv';
    public function __construct()
    {
        $this->pdo = new PDO('mysql:dbname=' . env('DB_DATABASE') . ';host=' . env('DB_HOST') . ';charset=utf8mb4', env('DB_USERNAME'), env('DB_PASSWORD'));
    }


    public function GetUnblendedCost($filter = [])
    {
        $sql = 'SELECT `product/ProductName` AS productName
                    , SUM(`lineItem/UnblendedCost`) AS total
                FROM ' . $this->table . '
                WHERE 1 = 1';

        if (isset($filter['lineitem/usageaccountid'])) {
            $sql .= ' AND `lineItem/UsageAccountId` = ' . $this->pdo->quote($filter['lineitem/usageaccountid']);
        }

        $sql .= ' GROUP BY productName
                ORDER BY productName';

        $results = DB::select($sql);
        return $results;
    }

    public function GetUsageAmount($filter = [])
    {
        $sql = 'SELECT SUBSTRING(`lineItem/UsageStartDate`,1,10) AS usageDate
                    , SUM(`lineItem/UsageAmount`) AS amountTotal
                FROM ' . $this->table . '
                WHERE 1 = 1';

        if (isset($filter['product/productName'])) {
            $sql .= ' AND `product/productName` = ' . $this->pdo->quote($filter['product/productName']);
        }

        $sql .= ' GROUP BY usageDate
                ORDER BY usageDate';

        $results = DB::select($sql);
        return $results;
    }
}
