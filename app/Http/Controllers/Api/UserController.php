<?php

namespace App\Http\Controllers\Api;

use App\Events\UserFollowing;
use App\Events\VerificationMail;
use App\Model\Media;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    /**
     * POST api/signIn
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $response = ['data' => [], 'errors' => []];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $response['errors'] = ($validator->errors()->all());
            return response()->json($response, '400');
        }

        $isAuthed = fauth()->once([
            'username' => $request->get('email'),
            'password' => $request->get('password'),
            'backend' => 0
        ]);

        if (!$isAuthed) {
            $response['errors'] = ["Email or password incorrect."];
            return response()->json($response, '400');
        }

        if (fauth()->user()->status == 0) {
            $response['errors'] = ["Please Verification your mail (check your e-mail)."];
            return response()->json($response, '400');
        }

        if (fauth()->user()->suspended == 1) {
            $response['errors'] = ["Your account suspended for ever "];
            return response()->json($response, '400');
        }

        if (fauth()->user()->suspended_to && fauth()->user()->suspended_to->getTimestamp() >= Carbon::now()->getTimestamp()) {
            $response['errors'] = ["Your account suspended for " . fauth()->user()->suspended_to->format('l jS \\of F Y h:i:s A')];
            return response()->json($response, '400');
        }
        $user = fauth()->user();

        $response['data'] = \Maps\User\login($user);
        $response['data']->currency = $user->currency;
        $response['data']->last_login = isset($user->last_login) && !empty($user->last_login) ? $user->last_login->timestamp : null;
        $response['data']->about = $user->about;
        $response['data']->email_verify = $user->email_verify;
        $response['token'] = $user->api_token;
        $user->last_login = Carbon::now();
        $user->save();
        return response()->json($response);
    }


    /**
     * POST /api/register
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function register(Request $request)
    {
        $response = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,[id],id',
            'password' => 'required',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $response['errors'] = ($validator->errors()->all());
            return response()->json($response, '400');
        }
        $user = new User();
        $user->username = $request->get('email');
        $user->email = $request->get('email');
        $user->password = ($request->get('password'));
        $names = explode(' ', $request->get('name'));
        $user->first_name = isset($names[0]) ? $names[0] : '';
        $user->last_name = isset($names[1]) ? $names[1] : '';
        $user->api_token = str_random(60);
        $user->backend = 0;
        $user->status = 0;
        $user->role_id = 3;
        $user->save();
        event(new VerificationMail($user));

        $response['data'] = \Maps\User\login($user);
        $response['token'] = $user->api_token;
        return response()->json($response);
    }


    /**
     * @param $user
     * @throws \Throwable
     */
    private function sendVerify($user)
    {
        DB::table('verification_tokens')->insert([
            'user_id' => $user->id,
            'token' => $token = str_random(60),
        ]);
        \Log::debug('send mail for :' . $user->email);
//        Mail::to($user->email)->send(new \App\Mail\VerificationMail($user->email, $token));

//        $headers = "MIME-Version: 1.0" . "\r\n";
//        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//        $headers .= 'From: <info@modasti.com>' . "\r\n";
//
//        mail($user->email, 'Modsiti', view('emails.verification', ['url' => route('verification.mail', ['token' => $token])])->render(), $headers);
    }

    /**
     * POST /api/registerDesigner
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerDesigner(Request $request)
    {
        $response = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'website' => 'required|url',
            'email' => 'required|email|unique:users,email,[id],id',
            'password' => 'required',
            'city_name' => 'required',
            'country_id' => 'required',
            'brand_name' => 'required',
            'phone' => 'required'
        ]);
        if ($validator->fails()) {
            $response['errors'] = ($validator->errors()->all());
            return response()->json($response);
        }
        $user = new User();
        $user->username = $request->get('email');
        $user->email = $request->get('email');
        $user->password = ($request->get('password'));
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->website = $request->get('website');
        $user->brand_name = $request->get('brand_name');
        $user->city_name = $request->get('city_name');
        $user->country_id = $request->get('country_id');
        $user->api_token = str_random(60);
        $user->backend = 0;
        $user->status = 1;
        $user->role_id = 2;
        $user->save();
        event(new VerificationMail($user));
        $response['data'] = \Maps\User\login($user);
        $response['token'] = $user->api_token;
        return response()->json($response);
    }

    /**
     * POST api/followUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function followUser(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $id = $request->get('userId');
        if (!User::find($id)) {
            $data['errors'][] = "User not found";
            return response()->json($data, 400);
        }
        $isblocked = DB::table('users_blocked')
            ->where(['user_id' => fauth()->user()->id, 'blocked_id' => $request->get('userId')])
            ->OrWhere(['blocked_id' => fauth()->user()->id, 'user_id' => $request->get('userId')])->count() ? true : false;

        if ($isblocked) {
            $data['errors'][] = "User blocked";
            return response()->json($data, 400);
        }
        (fauth()->user()->following()->detach($id));
        (fauth()->user()->following()->attach($id));
        event(new UserFollowing($id));
        return response()->json($data);
    }

    /**
     * POST api/unfollowUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfollowUser(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $id = $request->get('userId');
        if (!User::find($id)) {
            $data['errors'][] = "User not found";
            return response()->json($data, 400);
        }
        (fauth()->user()->following()->detach($id));
        return response()->json($data);
    }

    /**
     * POST api/getProfile
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $id = $request->get('userId');
        $user = User::find($id);
        if (!$user) {
            $data['errors'][] = "User not found";
            return response()->json($data, 400);
        }
        return response()->json(\Maps\User\users($user));
    }

    /**
     * POST api/getFollowingUsers
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowingUsers(Request $request)
    {
        $data = ['data' => ['users' => ''], 'errors' => []];
        $limit = $request->get('limit', 8);
        $offset = $request->get('offset', 0);
        $user = User::find($request->get('userId', 0));
        if (!$user) {
            $data['errors'][] = "User not found";
            return response()->json($data, 400);
        }

        $followingUsers = $user->following()->take($limit)->offset($offset)->get();
        $data['data']['users'] = \Maps\User\users($followingUsers);
        return response()->json($data);
    }

    /**
     * POST api/getFollowersUsers
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowersUsers(Request $request)
    {
        $data = ['data' => ['users' => ''], 'errors' => []];
        $limit = $request->get('limit', 8);
        $offset = $request->get('offset', 0);
        $user = User::find($request->get('userId', 0));
        if (!$user) {
            $data['errors'][] = "User not found";
            return response()->json($data, 400);
        }

        $followerUsers = $user->follower()->take($limit)->offset($offset)->get();
        $data['data']['users'] = \Maps\User\users($followerUsers);
        return response()->json($data);
    }

    /**
     * POST api/profileUpdate
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $user = fauth()->user();
        $validator = Validator::make($request->all(), []);
        $validator->sometimes('lastName', 'required', function () use ($request) {
            return $request->filled('lastName');
        });
        $validator->sometimes('firstName', 'required', function () use ($request) {
            return $request->filled('firstName');
        });
        $validator->sometimes('password', 'required|min:6', function () use ($request) {
            return $request->filled('password') || $request->filled('currentPassword');
        });
        $validator->sometimes('currentPassword', 'required|min:6', function () use ($request) {
            return $request->filled('password') || $request->filled('currentPassword');
        });
        $validator->sometimes('email', 'required|email|unique:users,email', function () use ($request, $user) {
            return $request->filled('email') && (trim($request->get('email')) != trim($user->email));
        });

        $media = new Media();

        if ($validator->fails() && ($request->filled('image') && !$media->isBase64($request->get('image')))) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        if ($request->filled('firstName')) {
            $user->first_name = $request->get('firstName');
        }
        if ($request->filled('lastName')) {
            $user->last_name = $request->get('lastName');
        }
        if ($request->filled('email')) {
            if ($request->get('email') != $user->email) {
                $user->email_verify = 0;
                event(new VerificationMail($user));
            }
            $user->email = $request->get('email');
            $user->username = $request->get('email');
        }
        if ($request->filled('password')) {
            $user->password = $request->get('password');
        }
        if ($request->filled('currency')) {
            $user->currency = $request->get('currency');
        }
        if ($request->filled('about')) {
            $user->about = $request->get('about');
        }
        if ($request->filled('profession')) {
            $user->profession = $request->get('profession');
        }
        if ($request->get('image', false)) {
            $media = $media->saveContent(explode('base64,', $request->get('image'))[1]);
            $user->photo_id = $media->id;
        }
        $user->save();
        return response()->json($data);
    }

    /**
     * POST api/blockUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function blockUser(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $isBlocked = fauth()->user()->blocked_users()->where(['blocked_id' => $request->get('userId')])->count() ? true : false;
        if ($isBlocked) {
            $data['errors'][] = "User already blocked";
            return response()->json($data, 400);
        }
        fauth()->user()->blocked_users()->attach($request->get('userId'));
        return response()->json($data);
    }


    /**
     * POST api/unblockUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unblockUser(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $isBlocked = fauth()->user()->blocked_users()->where(['blocked_id' => $request->get('userId')])->count() ? true : false;
        if (!$isBlocked) {
            $data['errors'][] = "User already unblocked";
            return response()->json($data, 400);
        }
        fauth()->user()->blocked_users()->detach($request->get('userId'));
        return response()->json($data);
    }


    /**
     * POST api/listBlocked
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listBlocked(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);
        $data['data'] = \Maps\User\users(fauth()->user()->blocked_users()->offset($offset)->take($limit)->get());
        return response()->json($data);
    }


    /**
     * POST api/recommendedUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recommendedUser(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);
        $users = User::with('photo')->where(['backend' => 0, 'status' => 1])
            ->where('id', '<>', fauth()->id())
            ->inRandomOrder()
            ->take($limit)
            ->offset($offset)
            ->get();;
        $data['data'] = \Maps\User\users($users);
        return response()->json($data);
    }


    /**
     * POST api/getProfileForm
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myProfile(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $id = fauth()->id();
        $user = User::find($id);
        if (!$user) {
            $data['errors'][] = "User not found";
            return response()->json($data, 400);
        }
        $data['data'] = $user;
        return response()->json($data['data']);
    }


    /**
     * GET or POST 'api/setPushToken'
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPushToken(Request $request){
        $id = fauth()->id();
        $user = User::find($id);
        $user->device_token=$request->get('pushToken');
        $user->save();
        return response()->json(['success'=>true]);
    }
}
