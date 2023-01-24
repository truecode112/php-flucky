<?php

namespace App\Http\Controllers;

use App\Models\BannedUser;
use App\Models\ReportedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportedUserController extends Controller
{
    /**
     * Manage site settings.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = ReportedUser::get();

        return view('admin.reported-users', [
            'page' => __('Reported Users'),
            'data' => $data,
        ]);
    }

    //remove the entry and delete the images if the user is ignroed
    public function ignoreUser(Request $request)
    {
        $model = ReportedUser::find($request->id);
        $images = $model->images;

        if ($model->delete()) {
            foreach (json_decode($images) as $image) {
                Storage::delete('public/images/reported-users/' . $image);
            }
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //remove the entry and delete the images if the user is banned, add entry to the banned table
    public function banUser(Request $request)
    {
        $reportedUser = ReportedUser::find($request->id);
        $ip = $reportedUser->ip;
        $images = $reportedUser->images;

        if ($reportedUser->delete()) {
            foreach (json_decode($images) as $image) {
                Storage::delete('public/images/reported-users/' . $image);
            }

            $count = BannedUser::where('ip', $ip)->count();

            if (!$count) {
                $model = new BannedUser();
                $model->ip = $ip;
                $model->save();
            }

            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //get and show the banned users
    public function bannedUsers()
    {
        $data = BannedUser::get();

        return view('admin.banned-users', [
            'page' => __('Banned Users'),
            'data' => $data,
        ]);
    }

    //unban user
    public function unbanUser(Request $request)
    {
        if (BannedUser::where('ip', $request->ip)->delete()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //bulk ignore
    public function bulkIgnoreUser(Request $request)
    {
        $ids = json_decode($request->ids);
        $images = ReportedUser::whereIn('id', $ids)->pluck('images')->toArray();

        if (ReportedUser::destroy($ids)) {
            $result = [];
            foreach ($images as $image) {
                $result = array_merge($result, json_decode($image));
            }
            $finalArray = preg_filter('/^/', 'public/images/reported-users/', $result);

            Storage::delete($finalArray);
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //bulk ban
    public function bulkBanUser(Request $request)
    {
        $ids = json_decode($request->ids);
        $images = ReportedUser::whereIn('id', $ids)->pluck('images')->toArray();
        $ips = ReportedUser::whereIn('id', $ids)->pluck('ip')->toArray();

        if (ReportedUser::destroy($ids)) {
            $result = [];
            foreach ($images as $image) {
                $result = array_merge($result, json_decode($image));
            }
            $finalArray = preg_filter('/^/', 'public/images/reported-users/', $result);

            Storage::delete($finalArray);

            foreach($ips as $ip) {
                $model = new BannedUser();
                $model->ip = $ip;
                $model->save();
            }

            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //bulk unban
    public function bulkUnbanUser(Request $request)
    {
        if (BannedUser::destroy(json_decode($request->ids))) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }
}
