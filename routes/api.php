<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DictionaryCheckResultController;
use App\Http\Controllers\KronTm\KronTmDeptController;
use App\Http\Controllers\KronTm\KronTmServerController;
use App\Http\Controllers\KronTm\KronTmUserController;
use App\Http\Controllers\PackageTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermissionSectionController;
use App\Http\Controllers\ProductionProjectLogController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectLog\ProjectLogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScriptTypeController;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\StatePlanningController;
use App\Http\Controllers\TelegramUserTokenController;
use App\Http\Controllers\UpdatePackageColorController;
use App\Http\Controllers\UpdatePackageController;
use App\Http\Controllers\UpdateScriptController;
use App\Http\Controllers\UserController;
use App\Notifications\TestingTelegramNotification;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the 'api' middleware group. Enjoy building your API!
|
*/

Route::post('/send', function () {
    \Illuminate\Support\Facades\Notification::send(453665523, new TestingTelegramNotification());
});

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'user-telegram'], static function ($router) {
    Route::put('unlink', [TelegramUserTokenController::class, 'unlinkUserTelegram']);
    Route::post('link', [TelegramUserTokenController::class, 'generateUrlForTelegramUser']);
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], static function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('jwt.refresh');
    Route::post('me', [AuthController::class, 'me']);
});

Route::middleware(['jwt.auth'])->resource('permission', PermissionController::class);


Route::group(['middleware' => 'jwt.auth', 'prefix' => 'users'], static function ($router) {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
    Route::get('/get-info', [UserController::class, 'getUserInfo']);
    Route::post('/update-info', [UserController::class, 'updateUserInfo'])->middleware([HandlePrecognitiveRequests::class]);
    Route::post('/reset-user-password', [UserController::class, 'resetUserPassword']);
    Route::put('/save-user-setting', [UserController::class, 'saveUserSettings']);
    Route::delete('/delete-avatar-users', [UserController::class, 'deleteUserAvatar']);
    Route::delete('/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+');
});

Route::middleware(['jwt.auth'])->resource('script-type', ScriptTypeController::class);
Route::middleware(['jwt.auth'])->resource('permission-section', PermissionSectionController::class);
Route::middleware(['jwt.auth'])->resource('role', RoleController::class);
Route::middleware(['jwt.auth'])->resource('update-package-color', UpdatePackageColorController::class);
Route::group(['middleware' => 'jwt.auth', 'prefix' => 'server'], static function ($router) {
    Route::get('/', [ServersController::class, 'index']);
    Route::get('/{id}', [ServersController::class, 'show'])->where('id', '[0-9]+');
    Route::post('/', [ServersController::class, 'store']);
    Route::put('/{id}', [ServersController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/{id}', [ServersController::class, 'destroy'])->where('id', '[0-9]+');
    Route::get('/trash', [ServersController::class, 'trash']);
    Route::post('/trash/restore/{id}', [ServersController::class, 'restore'])->where('id', '[0-9]+');
    Route::delete('/trash/{id}', [ServersController::class, 'forceDelete'])->where('id', '[0-9]+');
});
Route::middleware(['jwt.auth'])->post('server/get-selected-packages-servers', [ServersController::class, 'getSelectedPackagesServers']);
Route::middleware(['jwt.auth'])->resource('production-project-log', ProductionProjectLogController::class);
Route::middleware(['jwt.auth'])->post('update-package/update-servers', [UpdatePackageController::class, 'updateServers']);
Route::middleware(['jwt.auth'])->get('update-package/update-project-log-info/{id}', [UpdatePackageController::class, 'updateProjectLogInfo'])->where('id', '[0-9]+');
Route::middleware(['jwt.auth'])->post('update-package/update-error-log', [UpdatePackageController::class, 'getUpdatePackageErrors']);
Route::middleware(['jwt.auth'])->get('update-package/user-list', [UpdatePackageController::class, 'getUserList']);
Route::middleware(['jwt.auth'])->resource('update-package', UpdatePackageController::class);
Route::middleware(['jwt.auth'])->put('update-script/reorder', [UpdateScriptController::class, 'reorderScripts']);
Route::middleware(['jwt.auth'])->resource('update-script', UpdateScriptController::class);
Route::middleware(['jwt.auth'])->resource('project', ProjectController::class);
Route::middleware(['jwt.auth'])->resource('package-type', PackageTypeController::class);
Route::middleware(['jwt.auth'])->resource('dictionary-check-result', DictionaryCheckResultController::class);
Route::middleware(['jwt.auth'])->post('dictionary-check-result/check', [DictionaryCheckResultController::class, 'checkHandler']);
//Route::middleware(['jwt.auth'])->resource('state-planning', \App\Http\Controllers\StatePlanningController::class);

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'state-planning'], static function ($router) {
    Route::get('/', [StatePlanningController::class, 'index']);
    Route::get('/{id}', [StatePlanningController::class, 'show'])->where('id', '[0-9]+');
    Route::post('/', [StatePlanningController::class, 'store']);
    Route::put('/{id}', [StatePlanningController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/{id}', [StatePlanningController::class, 'destroy'])->where('id', '[0-9]+');
});

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'kron-tm'], static function ($router) {
    Route::get('/users', [KronTmUserController::class, 'get']);
    Route::get('/depts', [KronTmDeptController::class, 'get']);
    Route::get('/servers', [KronTmServerController::class, 'get']);
});

Route::get('/project-log/listbox', [ProjectLogController::class, 'getProjectLogList'])->middleware(['jwt.auth']);
