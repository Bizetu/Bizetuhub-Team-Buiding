<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use App\Models\PlatformSetting;

class LoginController extends Controller
{
    /**
     * Show the login choice page.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginChoice()
    {
        $platformSettings = PlatformSetting::where('id', 1)->first();

        $logoImage = $platformSettings->hasImage('login_background')
                        ? PlatformSetting::where('id', 1)->first()->image('login_background')
                        : 'assets/front/svgs/login-side.svg';

        return view('auth.login-choice', compact('logoImage'));
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $platformSettings = PlatformSetting::where('id', 1)->first();

        $logoImage = $platformSettings->hasImage('login_background')
                        ? PlatformSetting::where('id', 1)->first()->image('login_background')
                        : 'assets/front/svgs/login-side.svg';

        if (App::environment('local', 'staging')){
            return view('auth.login');
        } else {
            return view('auth.login-choice', compact('logoImage'));
        }
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (in_array($user->role_id, [1, 2, 3])) {
                Auth::guard('twill_users')->login($user);
            }

            $user->last_login_at = Carbon::now();
            $user->save();

            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Welcome back!');
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->onlyInput('email');
    }

    /**
     * Log the user out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        Auth::guard('twill_users')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
