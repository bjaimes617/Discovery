<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ForgotPasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

use SendsPasswordResetEmails;

    protected function sendResetLinkResponse(Request $request, $response) {
        return response()->json(['msg' => trans($response), 'status' => 'success', 'url' => route('login')]);
        //return back()->with('status', trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response) {

        return response()->json(['msg' => trans($response), 'status' => 'danger']);
        //return back()->withInput($request->only('email'))->withErrors(['email' => trans($response)]);
    }


    public function sendResetLinkEmail(Request $request) {
        $this->validateEmail($request);

        if (!User::where('email', $request->email)->exists()) {
            return response()->json(['msg' => trans("El email ingresado no existe."), 'status' => 'danger']);
        } else if (User::where('email', $request->email)->Where('estatus_id', '=', '2')->exists()) {
            return response()->json(['msg' => trans("El email ingresado se encuentra inactivo."), 'status' => 'danger']);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
                $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT ? $this->sendResetLinkResponse($request, $response) : $this->sendResetLinkFailedResponse($request, $response);
    }

}
