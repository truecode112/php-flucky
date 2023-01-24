<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Show all the users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = DB::table('users')
            ->select('id', 'username', 'gender', 'email', 'status', 'plan_type', 'plan_status', 'created_at')
            ->where('role', 'end-user')
            ->get();

        return view('admin.user.index', [
            'page' => __('Users'),
            'users' => $users,
        ]);
    }

    //udpate user status
    public function updateUserStatus(Request $request)
    {
        $user = User::find($request->id);
        $user->status = $request->checked == 'true' ? 'active' : 'inactive';

        if ($user->save()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }
}
