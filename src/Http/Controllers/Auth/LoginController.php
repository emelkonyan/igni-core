<?php

namespace Despark\Cms\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Despark\Cms\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Despark\Helpers\DesparkEncryptor;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {


        $request->merge(['email' => DesparkEncryptor::encrypt($request->email)]);
        //$request->merge(['password' => '%756Ij9rk2{}X|h']);

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        // First check if user is admin
        $user = \Auth::getProvider()->retrieveByCredentials($credentials);

        
        // We trigger two request to database, maybe we need custom auth provider.
        if ($user && $user->is_admin) {
            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('ignicms::admin.auth.login');
    }

    /**
     * Get the number of minutes to throttle for.
     *
     * @return int
     */
    public function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 15;
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        if ($seconds < 60) {
            $time = $seconds;
            $dimension = str_plural('second', $time);
        } else {
            $time = round($seconds / 60);
            $dimension = str_plural('minute', $time);
        }

        $message = trans('ignicms::admin.throttle', ['time' => $time, 'dimension' => $dimension]);

        $errors = [$this->username() => $message];

        if ($request->expectsJson()) {
            return response()->json($errors, 423);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
}
