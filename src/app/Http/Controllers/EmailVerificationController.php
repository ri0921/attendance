<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/attendance');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', '認証メールを再送しました');
    }
}
