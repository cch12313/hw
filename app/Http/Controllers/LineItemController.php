<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CsvImportRepository;
use \stdClass;

class LineItemController extends Controller
{
    public function __construct()
    {
        $this->CsvImportRepository = new CsvImportRepository;
    }

    public function unblendedCost(Request $request)
    {
        $this->validate($request, [
            'lineitem/usageaccountid' => 'required',
        ]);

        $usageaccountid = $request->input('lineitem/usageaccountid');
        $results = $this->CsvImportRepository->GetUnblendedCost([
            'lineitem/usageaccountid' => $usageaccountid,
        ]);

        $result = new stdClass();
        foreach ($results as $row) {
            $result->{$row->productName} = $row->total;
        }

        return response()->json($result, 201);
    }

    public function usageAmount(Request $request)
    {
        $this->validate($request, [
            'lineitem/usageaccountid' => 'required',
        ]);

        $usageaccountid = $request->input('lineitem/usageaccountid');
        $objectsOfProductName =  $this->CsvImportRepository->GetUnblendedCost([
            'lineitem/usageaccountid' => $usageaccountid,
        ]);

        $result = new stdClass();
        foreach($objectsOfProductName as $row){
            $objectsOfUsageAmount = $this->CsvImportRepository->GetUsageAmount([
                'product/productName' => $row->productName,
            ]);
            $result->{$row->productName} = new stdClass();

            foreach($objectsOfUsageAmount as $daily){
                $result->{$row->productName}->{$daily->usageDate} = $daily->amountTotal;
            }
        }


        return response()->json($result, 201);
    }
}
