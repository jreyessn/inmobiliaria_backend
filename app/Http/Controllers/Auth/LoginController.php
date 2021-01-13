<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use ThrottlesLogins;
    
    /**
     * The maximum number of attempts to allow.
     *
     * @return int
     */
    protected $maxAttempts = 5;


    /**
     * The number of minutes to throttle for.
     *
     * @return int
     */
    protected $decayMinutes = 2;
    /**
     * @var $repository
     */

    public function showLoginForm(){
      
    }

    /**
     * Inicio de sesión y creación de token
     */

    public function login(Request $request)
    {

        $request->validate([
            'email'         =>  'required|string|max:100',
            'password'      =>  'required|string',
            'remember_me'   =>  'boolean',
        ]);

        $credentials = $this->credentials($request);

        if (!Auth::attempt($credentials, $request->remember)) {
            $this->incrementLoginAttempts($request);

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if (method_exists($this, 'hasTooManyLoginAttempts') &&  $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }

            return response()->json([
                'message' => __('auth.failed'),
            ], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addDays(1);
        }

        $token->save();
        $user = $request->user()->load('roles.permissions');

        $expires_at = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();

        return response()->json([
            'message'       => __('auth.success'),
            'access_token'  => $tokenResult->accessToken,
            'token_type'    => 'Bearer',
            'user'          => $user,
            'time'          => now(),
            'expires_at'    => $expires_at
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user()->load('roles.permissions'));
    }

    public function logout(Request $request)
    {

        if (!Auth::check()) 
            return response()->json([
                'message' => __('auth.logout_failed'),
            ]);

        $token =  $request->user()->token();
        $token->revoke();

        return response()->json([
            'message' => __('auth.logout'),
        ]);
        
    }

    /**
     * Logout all sessions user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout_all_sessions(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens->each(function ($token, $key) {
                $token->revoke();
            });
            return response()->json([
                'message' => __('auth.logout'),
            ]);
        }else{
            return response()->json([
                'message' => __('auth.logout_failed'),
            ]);
        }
    }

    protected function credentials(Request $request)
    {
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return [
            $field      =>  $request->email,
            'password'  =>  $request->password,
        ];
    }

    protected function username()
    {
        return 'email';
    }

}
