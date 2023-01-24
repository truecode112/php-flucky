<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\GlobalConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FeaturesController extends Controller
{
    /**
     * Show the features page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $features = Feature::get();
        $paymentModeLink = route('global-config') . '/edit/' . GlobalConfig::where('key', 'PAYMENT_MODE')->first()->id;

        return view('admin.features', [
            'page' => __('Features'),
            'features' => $features,
            'paymentModeLink' => $paymentModeLink
        ]);
    }

    //update feature status
    public function updateFeatureStatus(Request $request)
    {
        $feature = Feature::find($request->id);
        $feature->status = $request->checked == 'true' ? 'active' : 'inactive';

        if ($feature->save()) {
            Cache::forget('feature');
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //update feature paid
    public function updateFeaturePaid(Request $request)
    {
        $authMode = getSetting('AUTH_MODE');
        $paymentMode = getSetting('PAYMENT_MODE');

        if ($authMode == 'disabled' && $request->checked == 'true') {
            return json_encode(['success' => false, 'message' => __('Please enable the auth mode first')]);
        }

        if ($paymentMode == 'disabled' && $request->checked == 'true') {
            return json_encode(['success' => false, 'message' => __('Please enable the payment mode first')]);
        }

        $feature = Feature::find($request->id);
        $feature->paid = $request->checked == 'true' ? 'yes' : 'no';

        if ($feature->save()) {
            Cache::forget('feature');
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }
}
