<?php

namespace App\Http\Controllers;

use App\Models\ReceiveGoodDocument;
use App\Models\ReceiveGoodReject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ReceiveGoodRejectsController extends Controller
{
    
    public function index(Request $request)
    {
        $docuno =  $request->form_doc_no;
        $branch = $request->branch_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;

        $user = auth()->user();
        $user_id = $user->id;


        $results = ReceiveGoodReject::query();

        if ($docuno) {
            $results = $results->where(function ($query) use ($docuno) {
                $query->orWhereHas('receive_good_document', function ($q) use ($docuno) {
                    $q->orWhereHas('receive_good_files', function ($q) use ($docuno) {
                        $q->where('file', 'like', '%' . $docuno . '%');
                    });
                });
                // ->orWhere('remark', 'like', '%' . $docuno . '%');
            });
        }
        
        if ($branch) {
            $results = $results->where(function ($q) use ($branch) {
                $q->where('from_branch', $branch);
            });
        } 

        if($status){
            $results = $results->where('status',$status);
        }

        $role = Role::where('name','admin')->first();
        $role_id = $role->id;
        if($user->role != $role_id){
            $user_branches = $user->user_branches;
            $branch_ids = $user_branches->pluck('branch_id');
            $branch_ids[] = $user->branch_id;
            // dd($branch_ids);

            $results = $results->whereIn('branch_id',$branch_ids);
        }


        if($start_date || $end_date){
            $start_date = $request->start_date
                        ? Carbon::parse($request->start_date)->startOfDay()
                        : Carbon::createFromTimestamp(0)->startOfDay();
            $end_date = $request->end_date
                        ? Carbon::parse($request->end_date)->endOfDay()
                        : Carbon::today()->endOfDay();
            $results = $results->whereBetween('created_at', [$start_date , $end_date]);
        }else{
            $results->whereDate('created_at','>=',now()->subMonth());
        }

        $data = $results->orderBy('created_at','desc')->paginate(15);
        // dd($data);

        return view('receive_good_rejects.index',compact("data"));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'remark' => ['required', 'string', 'max:1000'],
        ]);

        $receive_good_document_id = $request->receive_good_document_id;
        $receive_good_document = ReceiveGoodDocument::findOrFail($receive_good_document_id);
        $user = auth()->user();

        $r008_file = $receive_good_document->receive_good_files->where('name','R008')->first()?->file;
        if($r008_file){
            return back()->with('fails', "This RG have R008 '$r008_file'. Please cancel R008 first.");
        }

        $receive_good_reject = ReceiveGoodReject::updateOrCreate(
            [
                'receive_good_document_id' => $receive_good_document->id,
            ],
            [
                'branch_id' => $receive_good_document->branch_id,
                'remark' => $request->remark,
                'user_id' => $user->id,
            ]
        );

        return back()->with('success', 'RG cancel request submitted successfully.');
    }

    public function approve_form($id, Request $request)
    {
        // dd($request);
        // $request->validate([
        //     'status' => ['required', 'in:Accepted,Rejected'],
        // ]);

        $receive_good_reject = ReceiveGoodReject::findOrFail($id);
        $status = $request->status;

        if ($request->status === 'Accepted') {
            // continue to run rg_approve_form 
            $receive_good_document = $receive_good_reject->receive_good_document;
            $cancelRequest = new Request([
                'status' => 'Cancel',
            ]);
            $response = app(\App\Http\Controllers\userController::class)
                ->approve_form($receive_good_document->id, $cancelRequest);

            if ($response->getSession()?->has('fails')) {
                return $response;
            }

            // $receive_good_reject->update([
            //     'approved_user_id' => auth()->id(),
            //     'approved_datetime' => now(),
            //     'status' => $request->status
            // ]);

            // return $response->with('success', "RG cancel request $status successfully.");
        }

        $receive_good_reject->update([
            'approved_user_id' => auth()->id(),
            'approved_datetime' => now(),
            'status' => $request->status
        ]);

        return back()->with('success', "RG cancel request $status successfully.");
    }

}
