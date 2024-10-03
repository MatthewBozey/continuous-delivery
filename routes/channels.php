<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->user_id === (int) $id;
});

Broadcast::channel('App.Models.UpdatePackage.{updatePackageId}', static function ($user, $updatePackageId) {
    return true;
});

Broadcast::channel('App.Models.UpdatePackage', static function ($user) {
    return true;
});

Broadcast::channel('App.Models.UpdateScript', static function ($user) {
    return true;
});

Broadcast::channel('App.Models.UpdateScript.{updateScriptId}', static function ($user, $updateScriptId) {
    return true;
});

Broadcast::channel('UpdatePackage', static function ($user) {
    return true;
});

Broadcast::channel('UpdatingProjectLog.{projectLogId}', static function ($user, $projectLogId) {
    return true;
});

Broadcast::channel('UpdateUpdatePackage{userId}', function ($user, int $userId) {
    return $user->user_id === $userId;
});

Broadcast::channel('App.Models.User.{user_id}', static function ($user, $user_id) {
    return (int) $user->user_id === (int) $user_id;
});

Broadcast::channel('Role', static function ($user) {
    return $user->hasAnyRole(['super-admin', 'admin']);
});

Broadcast::channel('StatePlanning', static function (\App\Models\User $user) {
    return $user->hasAnyPermission(['state-planning list', 'state-planning show', 'state-planning create', 'state-planning edit', 'state-planning delete']);
});

Broadcast::channel('Project', static function (\App\Models\User $user) {
    return $user->hasAnyPermission(['project list', 'project show', 'project create', 'project edit', 'project delete']);
});

Broadcast::channel('Server', static function (\App\Models\User $user) {
    return $user->hasAnyPermission(['server list', 'server show', 'server create', 'server edit', 'server delete']);
});
