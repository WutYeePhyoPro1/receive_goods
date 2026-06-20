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


}
