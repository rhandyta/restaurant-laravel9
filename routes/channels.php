<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('order.{rolesId}', function ($userRoles, $rolesId) {
    return $userRoles->id == $rolesId;
});


Broadcast::channel('transaction.{id}', function ($user, $id) {
    Log::info("User {$user->id} attempting to join transaction channel {$id}");
    Log::debug("User {$user->id} attempting to join transaction channel {$id}");
    return $id == $user->id;
});
