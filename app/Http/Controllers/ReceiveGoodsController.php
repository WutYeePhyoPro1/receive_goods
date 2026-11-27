<?php

namespace App\Http\Controllers;

use App\Exports\ReceiveGoods\ReceiveGoodsExport;
use App\Http\Requests\ReceiveGoodsFilterRequest;
use App\Services\Excel\ExcelExportService;
use App\Services\ReceiveGoods\ReceiveGoodsQuery;

class ReceiveGoodsController extends Controller
{
    public function index(
        ReceiveGoodsFilterRequest $request,
        ReceiveGoodsQuery $query
    ) {
        $receiveGoods = $query
            ->build($request->validated())
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('receive-goods.index', compact(
            'receiveGoods'
        ));
    }

    public function export(
        ReceiveGoodsFilterRequest $request,
        ExcelExportService $excel
    ) {
        $export = new ReceiveGoodsExport(
            $request->validated()
        );

        return $excel->download(
            $export,
            'receive-goods-' . now()->format('Ymd-His')
        );
    }
}
