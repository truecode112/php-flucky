<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Stripe;
use App\Models\UserPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => ['index']]);
        $this->middleware('checkPaymentMode');
    }

    /**
     * Show the pricing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $freeFeatures = Feature::where('paid', 'no')->where('status', 'active')->pluck('description');
        $paidFeatures = Feature::where('paid', 'yes')->where('status', 'active')->pluck('description');

        return view('payment.pricing', [
            'page' => __('Pricing'),
            'freeFeatures' => $freeFeatures,
            'paidFeatures' => $paidFeatures
        ]);
    }

    /**
     * payment view
     */
    public function payment(Request $request)
    {
        if (Auth::user()->plan_status == "active") {
            return redirect(route('profile'));
        }

        $price = $request->type == 'monthly' ? getSetting('MONTHLY_PRICE') : getSetting('YEARLY_PRICE');

        return view('payment.pay', [
            'page' => __('Payment'),
            'price' => $price,
            'type' => $request->type
        ]);
    }

    /**
     * handle payment and add plan details
     */
    public function handlePayment(Request $request)
    {
        $price = $request->type == 'monthly' ? getSetting('MONTHLY_PRICE') : getSetting('YEARLY_PRICE');

        try {
            Stripe\Stripe::setApiKey(getSetting('STRIPE_SECRET'));

            $transaction = Stripe\Charge::create([
                "amount" => 100 * $price,
                "currency" => getSetting('CURRENCY'),
                "source" => $request->stripeToken,
                "description" => "Plan purchased"
            ]);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return back();
        }

        $model = new UserPlan();
        $model->user_id = Auth::id();
        $model->amount = $price;
        $model->type = $request->type;
        $model->currency = getSetting('CURRENCY');
        $model->gateway = 'stripe';
        $model->transaction_id = $transaction->id;
        $model->plan_start_date = Carbon::now()->toDateString();
        $model->plan_end_date = $request->type == 'monthly' ? Carbon::now()->addMonth()->toDateString() : Carbon::now()->addYear()->toDateString();
        $model->save();

        User::where('id', Auth::id())->update(['plan_type' => 'paid', 'plan_status' => 'active']);
        Session::flash('success', __('Payment has been successfully processed, thank you'));

        return redirect(route('profile'));
    }
}
