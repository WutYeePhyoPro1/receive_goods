<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request['emp_id'] = $request['employeeId'];
        $data = $request->validate([
            'emp_id' => 'required',
            'password' => 'required',
        ]);


        $user = User::where(["employee_code" => $data['emp_id']])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return api_response_error(401, 'Invalid credentials', 401, []);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        $data = [
            'token'     => $token,
            'user'      => $user
        ];
        return api_response_success('Login success!!', $data);

    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
     public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
