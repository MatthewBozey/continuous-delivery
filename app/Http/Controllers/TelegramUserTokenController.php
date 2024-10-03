<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiSuccessResponse;
use App\Models\TelegramUserToken;
use App\Models\User;
use App\Service\TelegramBotManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;
use Symfony\Component\HttpFoundation\Response;

class TelegramUserTokenController extends Controller
{
    public function generateUrlForTelegramUser(Request $request, TelegramBotManager $telegramBotManager): ApiSuccessResponse
    {
        $user = User::findOrFail($request->user()->user_id);
        $data = [];
        if ($user->telegram) {
            $user->update(['telegram' => null]);
        }

        $data['user_id'] = $user->user_id;
        $data['expired_time'] = Carbon::now()->addMinutes(3)->toDateTimeString();

        $token = base64_encode(json_encode($data));
        $telegramUserToken = new TelegramUserToken(['token' => $token, 'id' => Str::uuid()]);
        $telegramUserToken->save();
        $data['telegram_url'] = $telegramBotManager->getBot()->url().'?start='.urlencode($token);

        return new ApiSuccessResponse($data);
    }

    public function unlinkUserTelegram(Request $request): ApiSuccessResponse
    {
        $user = User::findOrFail($request->user()->user_id);
        if (! $user->telegram) {
            return new ApiSuccessResponse([], Response::HTTP_NOT_MODIFIED);
        }

        $user->update(['telegram' => null]);

        return new ApiSuccessResponse($user);
    }
}
