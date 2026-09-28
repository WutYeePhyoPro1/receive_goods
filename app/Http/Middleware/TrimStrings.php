<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    protected function clean($request)
    {
        $originalExcept = $this->except;

        // Preserve the exact scan so whitespace is rejected by barcode validation.
        if ($request->is('barcode_scan')) {
            $this->except[] = 'data';
        }

        try {
            parent::clean($request);
        } finally {
            $this->except = $originalExcept;
        }
    }

    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
