<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Dot\Users\Models\User;

class OAuthApi
{

    /**
     * Front-end roles
     */
    const ROLES = [
        "designer" => 2,
        "normal" => 3
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!$token = $this->getAuthorizationToken($request)) {
            return response('Unauthorized.', 401);
        }

        // Authenticate
        if (!$this->authenticate($token)) {
            return response('Unauthorized.', 401);
        }
        // Authorize
        if (!$this->authorize($roles)) {
            return response('Unauthorized.', 401);
        }
        return $next($request);
    }

    /**
     * Authorize function
     * @param $roles
     * @return bool
     */
    private function authorize($roles)
    {
        if (empty($roles) || in_array('all', $roles)) {
            return true;
        }

        if (($name = array_search(fauth()->user()->role_id, OAuthApi::ROLES)) && (in_array($name, $roles))) {
            return true;
        }
        return false;
    }

    /**
     * Make User Authenticate
     * @param $token
     * @return bool
     */
    private function authenticate($token)
    {
        $user = User::where('api_token', $token)->where('backend', '<>', 1)->first(['id']);
        if (!isset($user)) {
            return false;
        }
        Auth::guard('frontend')->onceUsingId($user->id);
        return true;
    }

    /**
     * Get api token form request
     * @param $request
     * @return null
     */
    private function getAuthorizationToken($request)
    {
        $header = $request->header('authorization');
        if (empty($header) || strlen($header) <= 8 || !str_contains($header, 'Bearer ')) {
            return null;
        }
        // 7 is number of char of 'Bearer '
        return (substr($header, 7));
    }
}
