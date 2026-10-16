<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\EmailUnsubscribe;
use Illuminate\Http\Request;

class UnsubscribeController extends Controller
{
    //
    public function unsubscribe(Request $request)
    {
        $email = decrypt($request->query('token'));

        EmailUnsubscribe::updateOrCreate(
            ['email' => $email],
            [
                'group_id'        => $request->query('group_id') ?? null,
                'unsubscribed_at' => now(),
            ]
        );

        return view('emails.unsubscribed');

    }
}
