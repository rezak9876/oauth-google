<?php

namespace RezaK\OAuthGoogle\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class GoogleAuthController
{
    public function redirectToGoogle()
    {
        return response()->json(['url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()]);
    }

    public function handleGoogleCallback(Request $request)
    {
        // try {
            $user = Socialite::driver('google')->stateless()->user();
            // Here you can save user information in your database
            // and issue a token, e.g., JWT token

            // Assuming you have a User model and you handle the logic to find or create a user
            // $authUser = User::firstOrCreate(
            //     ['google_id' => $user->getId()],
            //     ['name' => $user->getName(), 'email' => $user->getEmail()]
            // );
            $authUser = User::where('id',1)->first();

            // Create a token for the user
            $token = $authUser->createToken('auth_token')->plainTextToken;
            $token = '1';
            $encryptedToken =  $this->customEncrypt($token);
            return redirect()->to('http://localhost/laravel-v11-fresh/public/callback?token=' . urlencode($encryptedToken));
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Unable to login with Google'], 500);
        // }
    }

    public function customEncrypt($string)
    {
        $key = hex2bin('f3b1a70b2fd16a6b9e25d33cbf9ac8e8b4f2c52f5ab6d7e13d90c6b831be5a64'); // Use the same key as in the frontend
        $cipher = 'AES-256-CBC';
        $iv = random_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($string, $cipher, $key, 0, $iv);
        return base64_encode($iv . $encrypted); // Concatenate IV with encrypted data
    }
}
