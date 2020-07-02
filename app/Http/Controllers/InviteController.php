<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invite;

class InviteController extends Controller
{
    protected function inviteIsExist(Request $request)
    {
        $invite = Invite::where('invite_code', '=', $request['invite_code'])->first();

        if(!$invite) {
            return response()->json([
                'Error' => 'Инвайт-кода не существует.',
            ]);
        } elseif($invite && $invite->isActive == 1) {
            return response()->json([
                'Error' => 'Инвайт-код уже использован.',
            ]);
        } elseif($invite && $invite->isActive == 0) {
            return response()->json([
                'Success' => 'Инвайт-код валиден.',
            ]);
        }
    }
}
