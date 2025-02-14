<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UnavailableScannedProduct;

class UnavailableScannedProductController extends Controller
{
    public function __construct(protected UnavailableScannedProduct $model)
    {
    }

    public function index(Request $request)
    {
        info($request->all());
        return $this->model
            ->where('received_goods_id', $request->received_goods_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        if($request['data']['scanned_barcode'] == ""){
            return;
        }
        DB::beginTransaction();
        try {
            $this->model->create([
                'received_goods_id' => $request['data']['received_goods_id'],
                'scanned_barcode' => $request['data']['scanned_barcode'],
                'ip_address' => $request->ip()
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
        }
    }

}
