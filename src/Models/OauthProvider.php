<?php
namespace RezaK\OAuthGoogle\Models;

use Illuminate\Database\Eloquent\Model;

class OauthProvider extends Model
{
    protected $fillable = ['user_id', 'provider', 'provider_user_id'];

    /**
     * Get the user associated with the OAuth provider.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
