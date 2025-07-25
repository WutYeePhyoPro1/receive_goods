<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Customize\Common;
use App\Models\ScanTrack;
use App\Models\DriverInfo;
use App\Models\RemoveTrack;
use App\Models\UploadImage;
use App\Models\GoodsReceive;
use Illuminate\Http\Request;
use App\Models\AddProductTrack;
use App\Models\changeTruckProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\ActionRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\ActionRepositoryInterface;
use App\Models\printTrack;
use App\Models\UserBranch;

class ActionController extends Controller
{
    private ActionRepositoryInterface $repository;

    public function __construct(ActionRepository $repository)
    {
        $this->repository = $repository;
    }

    //search doc
    public function search_doc(Request $request)
    {

        $val = trim(strtoupper($request->data), ' ');
        $type = substr($val, 0, 2);
        $reg = get_branch_truck()[2];

        $docs = Document::where('document_no', $val)
            ->whereIn('received_goods_id', $reg)->first();
        if ($docs) {
            return response()->json(['message' => 'dublicate'], 400);
        }
        // dd($type);
        $conn = DB::connection('master_product');

        if ($type == "PO") {
            $brch_con = '';
            if (!dc_staff()) {
                $user_brch = getAuth()->branch->branch_code;
                $brch_con = "and brchcode = '$user_brch'";
            }

            $data = $conn->select("
                select purchaseno,vendorcode,vendorname,productcode,productname,unitcount as unit,goodqty
                from  purchaseorder.po_purchaseorderhd aa
                inner join  purchaseorder.po_purchaseorderdt bb on aa.purchaseid= bb.purchaseid
                left join master_data.master_branch br on aa.brchcode= br.branch_code
                where statusflag <> 'C'
                --and statusflag in ('P','Y')
                --$brch_con
                and purchaseno= '$val'
            ");
        } else {
            $data = $conn->select("
                select tohd.transferdocno as to_docno
                ,(select branch_name_eng from master_data.master_branch br where tohd.desbrchcode = br.branch_code) as to_branch
                ,todt.productcode as product_code,todt.productname as product_name,todt.unitcount as unit
                ,todt.transferoutqty as qty
                from inventory.trs_transferouthd tohd
                left join inventory.trs_transferoutdt todt on tohd.transferid= todt.transferid
                where tohd.transferdocno in ('$val')
                and tohd.statusid <> 'C'
            ");
        }
        $conn = null;
        if ($data) {
            $this->repository->add_doc($data, $request->id);
            return response()->json($data, 200);
        } else {
            return response()->json(['message', 'not found'], 404);
        }
    }

    //barcode scan
    public function barcode_scan(Request $request)
    {
        Session::forget('first_time_search_' . $request->id);
        $all    = $request->data;
        $id     = $request->id;
        $item   = preg_replace('/\D/', '', $all);
        $unit   = preg_replace("/[^A-Za-z].*/", '', $all);
        $unit   = $unit == '' ? 'S' : $unit;
        $poi    = false;
        if (strtoupper(substr($all, 0, 2)) == 'PO' || strtoupper(substr($all, 0, 2)) == 'IC' || strtoupper(substr($all, 0, 2)) == 'AT') {
            $reg = get_branch_truck()[2];
            $all = strtoupper($all);
            $docs = Document::where('document_no', $all)
                ->whereIn('received_goods_id', $reg)->first();
            if ($docs) {
                return response()->json(['message' => 'dublicate'], 409);
            }
            $conn = DB::connection('master_product');
            $brch_con = '';
            if (!dc_staff()) {
                $user_brch = getAuth()->branch->branch_code;
                $brch_con = "and brchcode = '.$user_brch.'";
            }
            if (strtoupper(substr($all, 0, 2)) == 'PO') {
                $data = $conn->select("
                select purchaseno,vendorcode,vendorname,productcode,productname,unitcount as unit,goodqty
                from  purchaseorder.po_purchaseorderhd aa
                inner join  purchaseorder.po_purchaseorderdt bb on aa.purchaseid= bb.purchaseid
                left join master_data.master_branch br on aa.brchcode= br.branch_code
                where statusflag <> 'C'
                and statusflag in ('P','Y')
                $brch_con
                and purchaseno= '$all'
            ");
            } else {
                $data = $conn->select("
                    select tohd.transferdocno as to_docno
                    ,(select branch_name_eng from master_data.master_branch br where tohd.desbrchcode = br.branch_code) as to_branch
                    ,todt.productcode as product_code,todt.productname as product_name,todt.unitcount as unit
                    ,todt.transferoutqty as qty
                    from inventory.trs_transferouthd tohd
                    left join inventory.trs_transferoutdt todt on tohd.transferid= todt.transferid
                    where tohd.transferdocno in ('$all')
                    and tohd.statusid <> 'C'
                ");
            }
            $conn = null;
            if ($data) {
                $this->repository->add_doc($data, $id);
                return response()->json(['message' => 'success'], 200);
            } else {
                return response()->json(['message' => 'doc not found'], 404);
            }
        }
        $doc_ids = Document::where('received_goods_id', $request->id)->pluck('id');

        $product = Product::whereIn('document_id', $doc_ids)
            ->where('bar_code', $item)
            ->first();
        if ($product) {

            $all_product = Product::whereIn('document_id', $doc_ids)
                ->where('bar_code', $item)
                ->orderBy('id', 'asc')
                ->get();
            $doc_no = $product->doc->document_no;
            $conn = DB::connection('master_product');
            try {
                if (in_array(getAuth()->branch_id, [17, 19, 20])) {
                    $data = $conn->select("
                    select * from
                    (
                    select	 product_code, qty
                    from	dblink('dbname=pro1_awms host = 43.231.64.170 port=5432 user=superadmin password=super123',
                    '
                    SELECT product_code,qty FROM (
                    SELECT product_code,product_code as barcode,product_unit_rate as qty FROM public.aw_master_product_rate UNION ALL
                    SELECT product_code,pack_barcode as barcode,product_unit_rate as qty FROM public.aw_master_product_rate UNION ALL
                    SELECT product_code,barcode_box as barcode,case when unit_rate_box=''0'' then product_unit_rate else unit_rate_box end as qty FROM public.aw_master_product_rate UNION ALL
                    SELECT product_code,barcode_pallet as barcode,case when unit_rate_box=''0'' then (product_unit_rate*unit_rate_pallet) else (unit_rate_pallet*unit_rate_box) end as qty FROM public.aw_master_product_rate
                    )rt
                    WHERE barcode=''$all''')
                    as temp(product_code varchar(50),qty varchar(50))
                    )as erpdb
                ");
                    $qty = (int)($data[0]->qty) == 0 ? 1 : (int)($data[0]->qty);
                } else {
                    $qty = 1;
                }

                $per        = $qty;
                $total_scan = $qty;
                $count = 0;
                if ($request->car == '') {
                    $driver_info = DriverInfo::where(['received_goods_id' => $id, 'user_id' => getAuth()->id])
                        ->whereNull('duration')
                        ->first();
                } else {
                    $driver_info = DriverInfo::where('id', $request->car)
                        ->first();
                }

                if (count($all_product) == 1) {

                    $scanned = $product->scanned_qty + $qty;
                    $product->update([
                        'scanned_qty' => $scanned
                    ]);
                } elseif (count($all_product) > 1) {
                    $full_pd = Product::whereIn('document_id', $doc_ids)
                        ->where('bar_code', $item)
                        ->where(DB::raw('qty'), '>', DB::raw('scanned_qty'))
                        ->get();
                    $count      = 0;
                    if (count($full_pd) > 0) {
                        if ($request->car == '') {
                            $driver_info = DriverInfo::where(['received_goods_id' => $request->id, 'user_id' => getAuth()->id])
                                ->whereNull('duration')
                                ->first();
                        } else {
                            $driver_info = DriverInfo::where('id', $request->car)
                                ->first();
                        }

                        foreach ($all_product as $index => $item) {

                            if ($total_scan < 1) {
                                break;
                            }
                            $sub = $item->qty - $item->scanned_qty;

                            if ($item->qty > $item->scanned_qty && $sub >= $total_scan) {
                                if ($count > 0) {
                                    $update_time = Carbon::now()->addSecond();
                                } else {
                                    $update_time = Carbon::now();
                                }

                                $scanned = $item->scanned_qty + $total_scan;
                                Product::where('id', $item->id)->update([
                                    'scanned_qty'   => $scanned,
                                    'updated_at'    => $update_time
                                ]);
                                $pd_code = $this->repository->add_track($driver_info->id, $item->id, $total_scan, $item->document_id, $update_time, $unit, $per);
                                $count++;
                                break;
                            } else if ($item->qty > $item->scanned_qty && $sub < $total_scan && $index != count($all_product) - 1) {
                                if ($sub < $total_scan) {
                                    $scanned    = $item->qty;
                                    $total_scan = $total_scan - $sub;
                                    $added      = $sub;
                                } else {
                                    $scanned    = $item->scanned_qty + $total_scan;
                                    $total_scan = 0;
                                    $added      = $total_scan;
                                }

                                if ($count > 0) {
                                    $update_time = Carbon::now()->addSecond();
                                } else {
                                    $update_time = Carbon::now();
                                }

                                Product::where('id', $item->id)->update([
                                    'scanned_qty'   => $scanned,
                                    'updated_at'    => $update_time
                                ]);

                                $pd_code = $this->repository->add_track($driver_info->id, $item->id, $added, $item->document_id, $update_time, $unit, $per);
                            } elseif ($index == count($all_product) - 1) {
                                $scanned = $item->scanned_qty + $total_scan;
                                if ($count > 0) {
                                    $update_time = Carbon::now()->addSecond();
                                } else {
                                    $update_time = Carbon::now();
                                }
                                Product::where('id', $item->id)->update([
                                    'scanned_qty'   => $scanned,
                                    'updated_at'    => $update_time
                                ]);
                                $pd_code = $this->repository->add_track($driver_info->id, $item->id, $total_scan, $item->document_id, $update_time, $unit, $per);
                            }
                            $count++;
                        }
                    } else {
                        $exceed_pd = Product::whereIn('document_id', $doc_ids)
                            ->where('bar_code', $item)
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($exceed_pd) {
                            $exceed_qty = $exceed_pd->scanned_qty + $total_scan;
                            Product::where('id', $exceed_pd->id)->update([
                                'scanned_qty'   => $exceed_qty
                            ]);
                        }
                    }
                }
                if (isset($driver_info) && $count == 0) {
                    $pd_code = $this->repository->add_track($driver_info->id, $product->id, $total_scan, $product->document_id, null, $unit, $per);
                }
                $receive_good = GoodsReceive::find($request->id);
                if ($receive_good->start_date == '') {
                    $receive_good->update([
                        'start_date' => Carbon::now()->format('Y-m-d'),
                        'start_time' => Carbon::now()->format('H:i:s')
                    ]);
                }
                $cur_car = DriverInfo::find($request->car);
                if (!isset($cur_car->start_date)) {
                    $cur_car->update([
                        'start_date' => Carbon::now()->format('Y-m-d'),
                        'start_time' => Carbon::now()->format('H:i:s')
                    ]);
                    Session::put('first_time_search_' . $request->id, $pd_code);
                }

                return response()->json(['doc_no' => $doc_no, 'bar_code' => $product->bar_code, 'data' => $product, 'scanned_qty' => $qty, 'pd_code' => $pd_code], 200);
            } catch (\Exception $e) {
                logger($e);
                return response()->json(['message' => 'Server Time Out Please Try Again'], 500);
            }
        } else {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    //edit scan
    public function edit_scan(Request $request)
    {
        $track  = Tracking::where(['product_id' => $request->product, 'driver_info_id' => $request->driver])->get();
        $qty    = $request->val;
        $old    = $request->old;
        $change = $old - $qty;
        // dd($track);
        foreach ($track as $item) {
            if ($change >= $item->scanned_qty) {
                $sub_qty    = $item->scanned_qty - 1;
                Tracking::where('id', $item->id)->update([
                    'scanned_qty'   => 1
                ]);
                $change = $change - $sub_qty;
            } else {
                $final      = $item->scanned_qty - $change;
                Tracking::where('id', $item->id)->update([
                    'scanned_qty'   => $final
                ]);
                break;
            }
        }
        $pd = Product::where('id', $request->product)->first();
        $product_scan = $pd->scanned_qty - ($old - $qty);
        Product::where('id', $request->product)->update([
            'scanned_qty'   => $product_scan
        ]);


        return response()->json(200);
    }

    //confirm/continue Button click
    public function confirm(Request $request)
    {
        $receive = GoodsReceive::where('id', $request->id)->first();
        $doc    = Document::where('received_goods_id', $request->id)->get();
        $driver =  DriverInfo::where('received_goods_id', $request->id)
            ->where('user_id', getAuth()->id)
            ->whereNull('duration')->first();

        $finish_driver = DriverInfo::where('received_goods_id', $request->id)
            ->whereNotNull('duration')->get();
        if ($driver) {
            $start = strtotime($driver->start_date . ' ' . $driver->start_time);
            $now    = Carbon::now()->timestamp;
            $diff = $now - $start;

            $data =  $this->repository->get_remain($request->id);

            $hour   = (int)($diff / 3600);
            $min    = (int)(($diff % 3600) / 60);
            $sec    = (int)(($diff % 3600) % 60);
            $pass   = sprintf('%02d:%02d:%02d', $hour, $min, $sec);
            $this_scanned = get_scanned_qty($driver->id);
            // dd(get_all_duration($request->id));
            if (cur_truck_sec($driver->id) < 86401) {
                $receive->update([
                    'total_duration'        => get_all_duration($request->id),
                    'remaining_qty'         => $data['remaining'],
                    'exceed_qty'            => $data['exceed'],
                    'status'                => 'incomplete'
                ]);

                $driver->update([
                    'scanned_goods' => $this_scanned,
                    'duration'      => $pass
                ]);
            } else {
                return response()->json(500);
            }
        } else {
            $receive->update([
                'total_duration' => '00:00:00',
                'status'         => 'incomplete'
            ]);
        }
        return response()->json(200);
    }

    //click complete btn
    public function finish_goods($id)
    {

        $receive = GoodsReceive::where('id', $id)->first();
        $driver = DriverInfo::where('received_goods_id', $id)
            ->where('user_id', getAuth()->id)
            ->whereNull('duration')
            ->first();

        $finish_driver = DriverInfo::where('received_goods_id', $id)
            ->whereNotNull('duration')->get();

        $start_time = strtotime($driver->start_date . ' ' . $driver->start_time);
        $now        = strtotime(Carbon::now()->format('Y-m-d H:i:s'));

        $data =  $this->repository->get_remain($id);

        $diff = $now - $start_time;
        $hour   = (int)($diff / 3600);
        $min    = (int)(($diff % 3600) / 60);
        $sec    = (int)(($diff % 3600) % 60);
        $time   = sprintf('%02d:%02d:%02d', $hour, $min, $sec);

        $this_scanned = get_scanned_qty($id);
        if ($driver) {
            $receive->update([
                'total_duration'        => get_all_duration($id),
                'remaining_qty'         => $data['remaining'],
                'exceed_qty'            => $data['exceed'],
                'status'                => 'complete'
            ]);

            // if(cur_truck_sec($driver->id) < 86401)
            // {
            //     $receive->update([
            //         'total_duration'        => get_all_duration($id),
            //         'remaining_qty'         => $data['remaining'],
            //         'exceed_qty'            => $data['exceed'],
            //         'status'                => 'complete'
            //     ]);

            //     $driver->update([
            //         'scanned_goods' => $this_scanned,
            //         'duration'      => $time
            //     ]);

            //     return response()->json(200);
            // }
            return response()->json(500);
        }
    }

    //click delete excess btn
    public function del_exceed(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        $remove_qty = $product->scanned_qty - $product->qty;
        // dd($product,$remove_qty);    
        $product->update([
            'scanned_qty' => $product->qty
        ]);

        $del                        = new RemoveTrack();
        $del->received_goods_id      = $product->doc->received_goods_id;
        $del->user_id               = getAuth()->id;
        $del->product_id            = $request->id;
        $del->remove_qty            = $remove_qty;
        $del->save();

        return response()->json(200);
    }

    public function pass_vali(Request $request)
    {
        $emplyee    = explode('&', $request->data)[0];
        $emplyee    = explode('=', $emplyee)[1];
        $password    = explode('&', $request->data)[1];
        $password    = explode('=', $password)[1];
        $user = User::where('employee_code', $emplyee)->first();
        $user_branch    = getAuth()->branch_id;
        $same_br        = false;
        if (isset($user)) {
            $branch_exst = UserBranch::where(['user_id' => $user->id, 'branch_id' => $user_branch])->first();
            if ($branch_exst || $user_branch == $user->branch_id) {
                $same_br = true;
            }
            if ($same_br && Hash::check($password, $user->password) && ($user->role == 4 || $user->role == 3)) {
                return response()->json($user, 200);
            } else {
                return response()->json(['message' => 'Credential Does Not Match'], 404);
            }
        } else {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public  function add_product(Request $request)
    {
        Common::Log(route('add_product'), "manually add product qty");

        $product = Product::find($request->product);
        $track   = Tracking::where(['driver_info_id' => $request->car_id, 'product_id' => $request->product, 'user_id' => getAuth()->id])->first();
        $scan_track = ScanTrack::where(['driver_info_id' => $request->car_id, 'product_id' => $request->product, 'user_id' => getAuth()->id, 'unit' => 'S'])->first();
        $product->update([
            'scanned_qty'   => $product->scanned_qty + $request->data,
            'updated_at'    => Carbon::now()
        ]);

        Document::where('id', $product->document_id)->update([
            'updated_at'    => Carbon::now()
        ]);

        if ($track) {
            $track->update([
                'scanned_qty'   => $track->scanned_qty + $request->data
            ]);
        } else {
            $track                  = new Tracking();
            $track->driver_info_id  = $request->car_id;
            $track->product_id      = $request->product;
            $track->scanned_qty     = $request->data;
            $track->user_id         = getAuth()->id;
            $track->save();
        }

        if ($scan_track) {
            $scan_track->update([
                'count' => $scan_track->count + $request->data
            ]);
        } else {
            $scan_track             = new ScanTrack();
            $scan_track->driver_info_id  = $request->car_id;
            $scan_track->product_id      = $request->product;
            $scan_track->user_id         = getAuth()->id;
            $scan_track->unit            = 'S';
            $scan_track->per            = 1;
            $scan_track->count            = $request->data;
            $scan_track->save();
        }

        $product_track = new AddProductTrack();
        $product_track->authorize_user  =   $request->auth;
        $product_track->by_user         =   getAuth()->id;
        $product_track->truck_id        =   $request->car_id;
        $product_track->product_id      =   $request->product;
        $product_track->added_qty       =   $request->data;
        $product_track->save();

        return response()->json(200);
    }

    public function  show_remark($id)
    {
        $pd = Product::find($id);
        $remark = $pd->remark == null ? '' : $pd->remark;
        return response()->json($remark, 200);
    }

    public function store_remark(Request $request)
    {
        // $request->validate([
        // ]);
        if ($request->type == 'all') {
            $receive = GoodsReceive::find($request->id);
            if ($receive) {
                $receive->update([
                    'remark' => $request->data
                ]);
                return response(200);
            }
        } else {
            $product = Product::find($request->id);
            if (!isset($product->remark)) {
                $product->update([
                    'remark' => $request->data
                ]);
                return response(200);
            }
        }
    }

    public function show_image(Request $request)
    {
        $image = UploadImage::where('received_goods_id', $request->id)->get();
        $truck = DriverInfo::where('received_goods_id', $request->id)->get();

        if ($image) {
            foreach ($image as $item) {
                if (!Storage::exists('public/' . $item->file)) {
                    $ftp_file = Storage::disk('ftp')->get($item->file);

                    Storage::disk('public')->put($item->file, $ftp_file);
                    $item->update([
                        'public' => 1
                    ]);
                }
            }
            return response()->json(['image' => $image, 'truck' => $truck], 200);
        }
    }

    public function start_count($id)
    {
        $main = GoodsReceive::find($id);
        $driver = DriverInfo::where('received_goods_id', $id)
            ->whereNull('start_time')->first();

        if ($driver) {
            if ($main->start_data == null) {
                $main->update([
                    'start_date' => Carbon::now()->format('Y-m-d'),
                    'start_time' => Carbon::now()->format('H:i:s')
                ]);
                $driver->update([
                    'start_date' => Carbon::now()->format('Y-m-d'),
                    'start_time' => Carbon::now()->format('H:i:s')
                ]);
            }
        }
        return response(200);
    }

    public function print_track(Request $request)
    {
        $dub_pr     = printTrack::where(['product_id' => $request->id, 'bar_type' => $request->type, 'reason' => $request->reason,])->whereDate('created_at', Carbon::today())->first();
        if ($dub_pr) {

            $dub_pr->update([
                'quantity'  => $dub_pr->quantity + $request->qty
            ]);
        } else {

            $track_pr       = new printTrack();
            $track_pr->product_id   = $request->id;
            $track_pr->by_user      = getAuth()->id;
            $track_pr->quantity     = $request->qty;
            $track_pr->bar_type     = $request->type;
            $track_pr->reason       = $request->reason;
            $track_pr->save();
        }

        return response(200);
    }

    public function change_branch($id)
    {
        $user = User::find(getAuth()->id);
        $user->branch_id = $id;
        $user->save();

        return back();
    }
}
