<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailVerificationController extends Controller
{

    /**
     * GET verification/{token}
     * @route verification.mail
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verification($token)
    {
        $tokenObject = DB::table('verification_tokens')->where('token', $token)->first();
        if (!$tokenObject) {
            return response('This link not exist or expired', 404);
        }
        $user = User::findOrFail($tokenObject->user_id);
        if (!$user) {
            return response('This link not exist or expired', 404);
        }
        $user->status = 1;
        $user->email_verify = 1;
        $user->save();
        DB::table('verification_tokens')->where(['token'=>$token,'user_id'=>$user->id])->delete();
        return redirect('/#/?popup=login');
    }
}
