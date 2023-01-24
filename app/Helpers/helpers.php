<?php

use App\Models\GlobalConfig;
use App\Models\Content;
use App\Models\Currency;
use App\Models\Feature;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;

//get settings from the global config table
function getSetting($key)
{
	$settings = Cache::rememberForever('settings', function () {
		return GlobalConfig::all()->pluck('value', 'key');
	});

	if (!$settings[$key]) {
		Cache::forget('settings');
		$settings = GlobalConfig::all()->pluck('value', 'key');
	}

	return $settings[$key];
}

//get content from the content table
function getContent($key)
{
	$content = Cache::rememberForever('content', function () {
		return Content::all()->pluck('value', 'key');
	});

	if (!$content[$key]) {
		Cache::forget('content');
		$content = Content::all()->pluck('value', 'key');
	}

	return $content[$key];
}

//get currency symbol from the selected currency
function getCurrencySymbol()
{
	return Cache::rememberForever('symbol', function () {
		return Currency::where('code', getSetting('CURRENCY'))->first()->symbol;
	});
}

//get feature from the feature table
function getFeature($key, $value)
{
	$settings = Cache::rememberForever('feature', function () use ($value) {
		return Feature::all(['title', 'status', 'paid'])->groupBy('title')->toArray();
	});

	if (!isset($settings[$key])) {
		Cache::forget('feature');
		$settings = Feature::all(['title', 'status', 'paid'])->groupBy('title')->toArray();
	}

	return $settings[$key][0][$value];
}

//get languages
function getLanguages () {
	$languages = Cache::rememberForever('languages', function () {
		return Language::where(['status' => 'active'])->select('code', 'name', 'default', 'direction')->get();
	});

	return $languages;
}

//get selected language
function getSelectedLanguage () {
	if (session('locale')) {
        $selectedLanguage = getLanguages()->first(function($langauage) {
            return $langauage->code == session('locale');
        });

		if ($selectedLanguage) return $selectedLanguage;
	}

	return getDefaultLanguage();
}

//get default language
function getDefaultLanguage() {
	$languages = Cache::rememberForever('defaultLangauage', function () {
		return Language::where(['default' => 'yes'])->select('code', 'name', 'direction')->first();
	});

	return $languages;
}

//set value
function installed($value)
{
	session(['installed' => $value]);
}

//get value
function isInstalled()
{
	return session('installed');
}
