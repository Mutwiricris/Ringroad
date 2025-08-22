<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductVariantsExport;
use App\Exports\ProductVariantsSimpleExport;

class ExportController extends Controller
{
    public function exportProductVariantsDetailed(Request $request)
    {
        $filters = $request->all();
        return Excel::download(
            new ProductVariantsExport($filters),
            'product-variants-detailed-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportProductVariantsSimple(Request $request)
    {
        $filters = $request->all();
        return Excel::download(
            new ProductVariantsSimpleExport($filters),
            'product-variants-sales-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
