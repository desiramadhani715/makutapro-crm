<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Sales;
use App\Models\Prospect;
use App\Models\Fu;
use App\Models\TokenFcm;
use App\Models\HistoryChangeStatus;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Mail;

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
                        'token_type' => 'Bearer',
                        'user' => $user
                    ],'Authenticated');

                }else{
                    return ResponseFormatter::error(null,'Password tidak sesuai.');
                }
            }
            else{
                return ResponseFormatter::error(null,'No. Hp tidak terdaftar');
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
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->fails()) {

            try {
                if ((Hash::check(request('recent_password'), Auth::user()->password)) == false) {

                    return ResponseFormatter::error(null, 'Check your recent password',200);

                }
                if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {

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

    public function updateProfile(Request $request){

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            $photo =time() . rand(1, 100) . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('public/user', $photo);
            $user->photo = $photo;
        }else {
            $user->name = $request->name;
            $user->nick_name = $request->nick_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->hp = $request->hp;
            $user->gender = $request->gender;
            $user->birthday = $request->birthday;
        }

        $user->save();

        $user = User::where('id',$user->id)->get()->map(function ($item) {
            $item->photo = Config::get('app.url').'/public/storage/user/'.$item->photo;
            return $item;
        });


        return ResponseFormatter::success($user,'Data User berhasil di ubah');
    }

    public function getDataUser(){
        $user = User::where('id',Auth::user()->id)->get()->map(function ($item) {
            $item->photo = Config::get('app.url').'/public/storage/user/'.$item->photo;
            return $item;
        });
        return ResponseFormatter::success($user);
    }

    public function storeTokenFcm(Request $request){
        try {
            $token = new TokenFcm();
            DB::transaction(function () use ($request, $token){
                $token->user_id = Auth::user()->id;
                $token->device_id = $request->device_id;
                $token->token_fcm = $request->token_fcm;
                $token->save();
            });

            return ResponseFormatter::success($token);

        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error($th->getMessage());
        }
    }

    //Forget Password
    public function sendEmailOtpCode(Request $request){

        $user = User::where(['email' => $request->email,'role_id' => 6])->first();

        if(!$user){
            return ResponseFormatter::error($request->email, 'Email tidak terdaftar', 200);
        }

        $otpCode = rand(1000, 9999);

        $user->otp_code = $otpCode;
        $user->save();

        Mail::to($request->email)->send(new SendOtp($otpCode));

        return ResponseFormatter::success(['otp_code' => $otpCode, 'email' => $request->email],'Otp berhasil dikirim.');
    }

    public function checkOtpCode(Request $request){

        $user = User::where(['email' => $request->email,'otp_code' => $request->otp_code,'role_id' => 6,])->first();

        if(!$user){
            return ResponseFormatter::error($request->email, 'Kode OTP salah.', 200);
        }

        $user->otp_code = null;
        $user->save();
        $user->generateToken();

        return ResponseFormatter::success([
            'token' => $user->api_token,
            'token_type' => 'Bearer',
            'user' => $user
        ],'Authenticated');

    }

    public function newPassword(Request $request){
        $input = $request->all();
        $user = Auth::user();

        $rules = array(
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->fails()) {

            try {
                if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {

                    return ResponseFormatter::error(null, 'Please enter a password which is not similar then current password',200);

                } else {

                    $user->password = Hash::make($request->new_password);
                    $user->api_token = null;
                    $user->save();

                    return ResponseFormatter::success($user, 'Password baru berhasil dibuat.');
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

}
