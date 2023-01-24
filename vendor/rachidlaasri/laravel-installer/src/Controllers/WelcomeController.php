<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;

class WelcomeController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome(Redirector $redirect)
    {
    	if (isInstalled()) {
    		return $redirect->route('LaravelInstaller::requirements');
    	}

        return view('vendor.installer.welcome');
    }

    //verification
    public function verifyPurchaseCode(Request $request, Redirector $redirect)
    {
    	$rules = config('installer.welcome.rules');
        
    	$validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $redirect->route('LaravelInstaller::welcome')->withInput()->withErrors($validator->errors());
        }

    	$LICENSE_CODE = $request->code;
    	aplVerifyEnvatoPurchase($LICENSE_CODE);
    	$license_notifications_array=aplInstallLicense(url('/'), '', $LICENSE_CODE, '');

		if ($license_notifications_array['notification_case']=="notification_license_ok") {
    		installed(true);
    		session(['email' => $request->email]);
    		session(['password' => $request->password]);
    		return $redirect->route('LaravelInstaller::requirements');
    	} else {
    		return $redirect->route('LaravelInstaller::welcome')->withErrors([
                'code' => $license_notifications_array['notification_text'],
            ]);
    	}
    }
}
