<?php

namespace RezaK\OAuthGoogle\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

/**
 * @OA\Tag(
 *     name="OAuth Google",
 *     description="OAuth authentication with Google"
 * )
 */
class GoogleAuthController
{
    /**
     * @OA\Get(
     *     path="/auth/oauth/google",
     *     summary="Redirect to Google for authentication",
     *     description="Redirect the user to Google OAuth page to authenticate.",
     *     operationId="redirectToGoogle",
     *     tags={"OAuth Google"},
     *     @OA\Response(
     *         response=200,
     *         description="Redirect URL for Google OAuth",
     *         @OA\JsonContent(
     *             @OA\Property(property="url", type="string", example="https://accounts.google.com/o/oauth2/auth?...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function redirectToGoogle()
    {
        return response()->json(['url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()]);
    }

    /**
     * @OA\Get(
     *     path="/auth/oauth/google/callback",
     *     summary="Handle the Google OAuth callback",
     *     description="Handle the callback from Google after authentication, retrieve or create the user, and generate an authentication token.",
     *     operationId="handleGoogleCallback",
     *     tags={"OAuth Google"},
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         description="OAuth code returned by Google",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Redirect with token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Redirecting with token.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function handleGoogleCallback(Request $request)
    {
        $user = Socialite::driver('google')->stateless()->user();
        $authUser = User::whereHas('oauthProviders', function ($query) use ($user) {
            $query->where('provider', 'google')
                ->where('provider_user_id', $user->getId());
        })->first();

        if (!$authUser) {
            $authUser = User::firstOrCreate(
                ['email' => $user->getEmail()],
                ['name' => $user->getName()]
            );

            $authUser->oauthProviders()->create([
                'provider' => 'google',
                'provider_user_id' => $user->getId(),
            ]);
        }

        $token = $authUser->createToken('auth_token')->plainTextToken;

        return redirect()->to('http://localhost:3000/fa?token=' . urlencode($token));
    }
}
