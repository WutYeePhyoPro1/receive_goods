<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class R008DocumentFilterRequest extends FormRequest
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
        ];
    }
}
