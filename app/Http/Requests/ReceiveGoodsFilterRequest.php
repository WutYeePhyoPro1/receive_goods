<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiveGoodsFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'form_doc_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'branch_id' => [
                'nullable',
                'integer',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'back_date_from' => [
                'nullable',
                'date',
            ],

            'back_date_to' => [
                'nullable',
                'date',
                'after_or_equal:back_date_from',
            ],
        ];
    }
}
