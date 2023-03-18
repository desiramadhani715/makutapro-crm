<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Sales;
use App\Models\Prospect;
use App\Models\Fu;
use App\Models\HistoryChangeStatus;

class AuthController extends Controller
{
    public function login(Request $request){

        $this->validate($request, [
            'hp' => 'required',
            'password' => 'required',
        ]);

        $user = User::where(['hp' => $request->hp, 'role_id' => 6, 'active' => 1])->first();

        try {
            if($user){
                if (Hash::check($request->password, $user->password)) {
                    $user->generateToken();
                    return ResponseFormatter::success([
                        'token' => $user->api_token,
                        'token_type' => 'Bearer'
                    ],'Authenticated');
                    
                }
                else{
                    return ResponseFormatter::error([
                        'message' => 'Unauthorized'
                    ],`Hp & Password doesn't match`, 200);
                }
            }
            else{
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ],`Hp & Password doesn't match`, 200);
            }

        } catch (\Throwable $th) {

            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $exception,
            ],'Authentication Failed', 500);

        }
        
    }

    public function logout(Request $request){

        $user = Auth::user();
        if ($user) {
            DB::table('token_fcm')->where('user_id',Auth::user()->id)->delete();
            $user->api_token = null;
            $user->save();
            $status = 'success';
            $message = 'Logout Successfully';
            $code = 200;
        }else{
            $status = 'error';
            $message = "Logout Failed !";
            $code = 401;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => null
        ], $code);
    }

    public function changePassword(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();
        
        $rules = array(
            'recent_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        );

        $validator = Validator::make($input, $rules);
        
        if (!$validator->fails()) {

            try {
                if ((Hash::check(request('recent_password'), Auth::user()->password)) == false) {

                    return ResponseFormatter::error(null, 'Check your recent password',200);

                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {

                    return ResponseFormatter::error(null, 'Please enter a password which is not similar then current password',200);

                } else {

                    $user->password = Hash::make($request->new_password);
                    $user->api_token = null;
                    $user->save();

                    return ResponseFormatter::success(null, 'Password updated successfully');
                }
            } catch (\Exception $ex) {

                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                return ResponseFormatter::error(null, $msg, 400);
            }
            
        } 

        return ResponseFormatter::error(null, $validator->errors()->first(), 400);
    }

    // coming soon
    public function register(){

    }

}
