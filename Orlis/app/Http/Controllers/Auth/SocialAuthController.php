<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        $guzzle = new \GuzzleHttp\Client(['verify' => false]);
        return Socialite::driver($provider)->setHttpClient($guzzle)->redirect();
    }

    public function callback(string $provider)
    {
        try {
            $guzzle = new \GuzzleHttp\Client(['verify' => false]);
            $socialUser = Socialite::driver($provider)->setHttpClient($guzzle)->user();
            
            // Check if user exists by provider_id
            $user = User::where('provider', $provider)->where('provider_id', $socialUser->getId())->first();
            
            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $socialUser->getEmail())->first();
                
                if ($user) {
                    // Link the social account to existing user
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(24)),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'role' => 'customer',
                        'membership_level' => 'classic',
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            }
            
            Auth::guard('web')->login($user);
            return redirect()->route('product')->with('success', 'Đăng nhập thành công!');
            
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['email' => 'Đăng nhập bằng mạng xã hội thất bại.']);
        }
    }
}
