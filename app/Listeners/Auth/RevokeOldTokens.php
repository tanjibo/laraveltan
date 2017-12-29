<?php

namespace App\Listeners\Auth;

use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Token;

class RevokeOldTokens
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccessTokenCreated $event
     * @return void
     */
    public function handle( AccessTokenCreated $event )
    {

       $accessToken= Token::where('id', '!=', $event->tokenId)
             ->where('user_id', $event->userId)
             ->where('client_id', $event->clientId)
             ->orWhere('revoked', true)->pluck('id')->toArray();

        DB::table('oauth_refresh_tokens')->whereIn('access_token_id',$accessToken)->delete();
//         $accessToken->delete();
          Token::query()->whereIn('id',$accessToken)->delete();

    }
}
