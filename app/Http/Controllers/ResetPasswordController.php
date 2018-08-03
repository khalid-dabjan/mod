<?php

namespace App\Http\Controllers;

use App\Mail\PasswordChangeMail;
use App\Mail\ResetPasswordMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;

class ResetPasswordController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordReset(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 404);
        }
        $user = User::where(['backend' => 0, 'email' => $request->get('email')])->first();

        (DB::table('password_resets')->where(['email'=>$user->email])->delete());

        $token = str_random(60);
        DB::table('password_resets')->insert([
            'email' => $request->get('email'),
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        Mail::to($request->get('email'))->send(new ResetPasswordMail($user, $token));
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function reset(Request $request, $token)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return redirect()->back()->withErrors($validator);
        }
        $token = DB::table('password_resets')->where(['token' => $token])->where('created_at', '>=', Carbon::now()->subDay(1))->first();
        if (!$token) {
            return response('This link may be expires');
        }
        $user = User::where(['backend' => 0, 'email' => $token->email])->firstOrFail();
        $user->password = $request->get('password');
        $user->save();

        Mail::to($user->email)->send(new PasswordChangeMail($user));

        DB::table('password_resets')->where(['token' => $token->token])->delete();
        return redirect('/#/?popup=login');
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resetForm(Request $request, $token)
    {
        $token = DB::table('password_resets')->where(['token' => $token])->where('created_at', '>=', Carbon::now()->subDay(1))->first();
        if (!$token) {
            return response('This link may be expires', 404);
        }
        return view('reset', ['token' => $token->token]);
    }

}
