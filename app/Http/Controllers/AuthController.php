<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function verifyPassword(Request $request)
    {
        $user = auth()->user();

        if ($request->password === $user->employee_code) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 401);
        }
    }

}
