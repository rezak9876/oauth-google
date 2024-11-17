<?php
namespace RezaK\OAuthGoogle\Traits;

use RezaK\OAuthGoogle\Models\OauthProvider;
trait HasOAuthProviders
{
    /**
     * Get the OAuth providers associated with the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OauthProvider::class);
    }

    /**
     * Check if the model is connected to a given OAuth provider.
     *
     * @param  string  $provider
     * @return bool
     */
    public function isConnectedToProvider($provider)
    {
        return $this->oauthProviders()->where('provider', $provider)->exists();
    }
}
