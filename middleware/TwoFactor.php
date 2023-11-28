<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TwoFactor
{
    /**
     * The config repository instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The auth factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a Webauthn.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Contracts\Auth\Factory $auth
     */
    public function __construct(Config $config, AuthFactory $auth)
    {
        $this->config = $config;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        // Don't require two-factor if the user isn't logged in.
        if (!Auth::check()) {
            return $next($request);
        }

        // If the user has already provided an OTP for this session, do not prompt for Authenticator.
        if (\Session::has('otp')) {
            return $next($request);
        } else {
            return redirect()->guest('/2fa/attest');
        }

        return $next($request);
    }
}
