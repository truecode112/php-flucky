<?php

namespace App\Http\Controllers;

use App\Models\UserPlan;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    /**
     * Show the profile page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userPlan = UserPlan::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('profile', [
            'page' => __('Profile'),
            'userPlan' => $userPlan
        ]);
    }
}
