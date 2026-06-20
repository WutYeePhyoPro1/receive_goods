<?php

namespace App\Http\Controllers;

use App\Models\ReceiveGoodDocument;
use App\Models\ReceiveGoodReject;
use Illuminate\Http\Request;

class ReceiveGoodRejectsController extends Controller
{
    
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'remark' => ['required', 'string', 'max:1000'],
        ]);

        $receive_good_document_id = $request->receive_good_document_id;
        $receive_good_document = ReceiveGoodDocument::findOrFail($receive_good_document_id);
        $user = auth()->user();

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
        $request->validate([
            'status' => ['required', 'in:Approve,Reject'],
        ]);

        $receive_good_reject = ReceiveGoodReject::findOrFail($id);

        if ($request->status === 'Reject') {
            $receive_good_reject->delete();

            return back()->with('success', 'RG cancel request rejected successfully.');
        }

        $receive_good_reject->update([
            'approved_user_id' => auth()->id(),
            'approved_datetime' => now(),
        ]);

        return back()->with('success', 'RG cancel request approved successfully.');
    }

}
