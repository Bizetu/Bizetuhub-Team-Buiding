<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use App\Models\Domain;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     */
    public function showRegistrationForm()
    {
        // return view('auth.register');
        return redirect()->route('login.choice');
    }
    
    /**
     * Handle a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . config('twill.users_table', 'twill_users')],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => ['required', 'accepted'],
        ]);

        $emailDomain = Str::after($validated['email'], '@');
        $domain = Domain::where('name', $emailDomain)->first();

        if (!$domain) {
            return redirect()->back()->with('warning', 'Email domain is not registered with any company.');
        }
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company_id' => $domain->company_id,
            'password' => Hash::make($validated['password']),
            'published' => 1,
            'role_id' => 4,
            'last_login_at' => Carbon::now(),
            'registered_at' => Carbon::now(),
            'language' => 'en',
        ]);
        
        Auth::login($user);
        
        return redirect()->route('home')->with('success', `Welcome to RBA's ESG eLearning Platform! Your account has been created successfully.`);
    }
}