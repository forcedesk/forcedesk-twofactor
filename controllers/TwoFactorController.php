<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\User;

class TwoFactorController extends Controller
{

    public function get2faRegister(Request $request)
    {



        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Save the registration data in an array
        $user = \Auth::user();

        // Check if already enrolled.
        if($user->twofactor_enabled == true) {
            return redirect('/')->withMessage('You are already enrolled for two factor. Contact Support if you have lost your key.');
        }

        // Add the secret key to the registration data
        $twofactor_secret = $google2fa->generateSecretKey();

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $twofactor_secret
        );

        // Pass the QR barcode image to our view
        return view('twofactor.register', ['QR_Image' => $QR_Image, 'secret' => $twofactor_secret]);
    }

    public function post2faComplete(Request $request)
    {

        $user = \Auth::user();

        if($user->twofactor_enabled == true) {
            return redirect('/')->withMessage('You are already enrolled for two factor. Contact Support if you have lost your key.');
        }

        $user->twofactor_secret = $request->twofactor_secret;
        $user->twofactor_enabled = true;
        $user->save();

        return redirect('/')->withMessage('You have been enrolled successfully.');
    }

    public function get2faManual(Request $request)
    {

        return view('twofactor.index');
        
    }

    public function post2faManual(Request $request)
    {

        $user = \Auth::user();
        $secret = $request->one_time_password;

        $google_2fa = new Google2FA;
        $valid = $google_2fa->verifyKey($user->twofactor_secret, $secret);

        if($valid == 'true') {
            // The user has provided a valid one-time passcode, so we don't need the U2F key this time.
            \Session::put('otp', 'true');
        } else {
            return redirect()->back()->withMessage('The key was not valid!');
        }

        return redirect()->intended();
        
    }

}
