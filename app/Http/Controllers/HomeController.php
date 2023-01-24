<?php

namespace App\Http\Controllers;

use App\Models\BannedUser;
use App\Models\Country;
use App\Models\ReportedUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use stdClass;

class HomeController extends Controller
{
    /**
     * show the home page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $countries = Country::get();

        return view('home', [
            'countries' => $countries
        ]);
    }

    //save the reported user ip and image
    public function reportUser(Request $request)
    {
        $file = $request->image;
        $ip = $request->ip;

        if ($file && $file->isValid()) {
            $reportedUser = ReportedUser::where('ip', $ip)->first();
            $fileName = Str::random(4) . '_' . Carbon::now()->timestamp . '.jpg';

            if ($reportedUser) {
                $reportedUser->images = $this->addImage($reportedUser->images, $fileName);
                $reportedUser->save();
            } else {
                $newArray = [];
                array_push($newArray, $fileName);

                $model = new ReportedUser();
                $model->ip = $ip;
                $model->images = json_encode($newArray);
                $model->save();
            }

            $file->storeAs('public/images/reported-users', $fileName);

            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //add image to the array
    private function addImage($images, $image)
    {
        $imagesArray = json_decode($images);
        array_push($imagesArray, $image);
        return json_encode($imagesArray);
    }

    //check if the user is banned or not
    public function checkUser(Request $request)
    {
        $count = BannedUser::where('ip', $request->ip())->count();

        if ($count) {
            return json_encode(['success' => false]);
        } else {
            return json_encode(['success' => true]);
        }
    }

    //get the application details and send it to the user
    public function getDetails(Request $request)
    {
        $details = new stdClass();
        $details->username = Auth::check() ? Auth::user()->username : getSetting('DEFAULT_USERNAME');
        $details->userGender = Auth::user() ? Auth::user()->gender : '';
        $details->stunUrl = getSetting('STUN_URL');
        $details->turnUrl = getSetting('TURN_URL');
        $details->turnUsername = getSetting('TURN_USERNAME');
        $details->turnPassword = getSetting('TURN_PASSWORD');
        $details->signalingURL = getSetting('SIGNALING_URL');
        $details->ip = $request->ip();
        $details->textChatPaid = getFeature('TEXT_CHAT', 'paid') == 'yes';
        $details->videoChatPaid = getFeature('VIDEO_CHAT', 'paid') == 'yes';
        $details->genderFilterPaid = getFeature('GENDER_FILTER', 'paid') == 'yes';
        $details->countryFilterPaid = getFeature('COUNTRY_FILTER', 'paid') == 'yes';
        $details->genderFilterActive = getFeature('GENDER_FILTER', 'status') == 'active';
        $details->countryFilterActive = getFeature('COUNTRY_FILTER', 'status') == 'active';
        $details->userType = auth()->user() ? auth()->user()->plan_type : 'free';
        $details->paidPlanName = getSetting('PRICING_PLAN_NAME_PAID');
        $details->primaryColor = getSetting('THEME_COLOR');
        $details->userLoggedIn = auth()->check();
        $details->falseVideoEnabled = getFeature('FAKE_VIDEO', 'status') == 'active';
        $details->falseVideoTime = getSetting('FAKE_VIDEO_TIME');
        $details->falseVideoFrequency = getSetting('FAKE_VIDEO_FREQUENCY');
        $details->flagCodes = json_decode(\file_get_contents('sources/flag-codes.json', true));
        $details->liveCountPrefix = getSetting('LIVE_COUNT_PREFIX') == "null" ? '' : getSetting('LIVE_COUNT_PREFIX');

        $arr = [];
        if (getFeature('FAKE_VIDEO', 'status') == 'active') {
            $files = File::files('videos');
            foreach($files as $path) {
                $file = pathinfo($path);
                if ($file['extension'] == 'mp4') {
                    array_push($arr, $file['basename']);
                }
            }
        }

        $details->videos = $arr;

        return json_encode(['success' => true, 'data' => $details]);
    }

    //set locale in the session
    public function setLocale (Request $request) {
        $locale = $request->locale;
        session(['locale' => $locale]);
        App::setLocale($locale);

        return redirect()->back();
    }
}
