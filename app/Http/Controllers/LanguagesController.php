<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LanguagesController extends Controller
{
    /**
     * Manage languages
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = Language::get();

        return view('admin.language.index', [
            'page' => __('Languages'),
            'data' => $data,
        ]);
    }

    //return the pages page
    public function create()
    {
        return view('admin.language.create', [
            'page' => __('Language'),
        ]);
    }

    //create language
    public function createLanguage(Request $request)
    {
        $file = $request->file;

        if ($file && $file->isValid()) {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:languages|max:64',
                'name' => 'required|max:255',
                'direction' => 'required',
                'default' => 'required',
                'status' => 'required',
                'file' => 'required|file|mimetypes:text/plain',
            ]);

            if ($validator->fails()) {
                return json_encode(['success' => false, 'message' => $validator->errors()->first()]);
            }

            if ($request->default == "yes" && $request->status == "inactive") {
                return json_encode(['success' => false, 'message' => __('The default language can not be inactive')]);
            }

            if ($request->default == 'yes') {
                Language::where(['default' => 'yes'])->update(['default' => 'no']);
            }

            $model = new Language();
            $model->code = $request->code;
            $model->name = $request->name;
            $model->direction = $request->direction;
            $model->default = $request->default;
            $model->status = $request->status;

            if ($model->save()) {
                Cache::forget('languages');
                Cache::forget('defaultLangauage');

                Storage::disk('languages')->put($request->code . '.json', File::get($file));
                $file->storeAs('public/languages', $request->code . '.json');

                return json_encode(['success' => true]);
            } else {
                return json_encode(['success' => false]);
            }
        }

        return json_encode(['success' => false, 'message' => __('The given file is not valid')]);
    }

    //return the pages page
    public function edit($id)
    {
        $model = Language::find($id);

        return view('admin.language.edit', [
            'page' => __('Language'),
            'model' => $model,
        ]);
    }

    //update language
    public function updateLanguage(Request $request)
    {
        $model = Language::find($request->id);
        $file = $request->file;

        if ($file && $file->isValid()) {
            $validator = Validator::make($request->all(), [
                'direction' => 'required',
                'default' => 'required',
                'status' => 'required',
                'file' => 'required|file|mimetypes:text/plain',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'direction' => 'required',
                'default' => 'required',
                'status' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return json_encode(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if ($request->default == "yes" && $request->status == "inactive") {
            return json_encode(['success' => false, 'message' => __('The default language can not be inactive')]);
        }
        
        if($model->default == "yes" && $request->default == "no") {
            return json_encode(['success' => false, 'message' => __('There must be at least one default language')]);
        }

        if ($model->default == 'no' && $request->default == 'yes') {
            Language::where(['default' => 'yes'])->update(['default' => 'no']);
        }

        $model->direction = $request->direction;
        $model->default = $request->default;
        $model->status = $request->status;
        if ($model->save()) {
            Cache::forget('languages');
            Cache::forget('defaultLangauage');

            if ($file && $file->isValid()) {
                Storage::disk('languages')->put($model->code . '.json', File::get($file));
                $file->storeAs('public/languages', $model->code . '.json');
            }

            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //delete language
    public function deleteLanguage (Request $request) {
        $model = Language::find($request->id);

        if($model->code == 'en') {
            return json_encode(['success' => false, 'message' => __('This language can not be deleted')]);
        } else if ($model->default == "yes") {
            return json_encode(['success' => false, 'message' => __('The default language can not be deleted.')]);
        }

        if ($model->delete()) {
            Cache::forget('languages');
            Cache::forget('defaultLangauage');

            Storage::disk('languages')->delete($model->code . '.json');
            Storage::delete('public/languages/' . $model->code . '.json');
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //download sample english file
    public function downloadEnglish () {
        return response()->download("sources/en-sample.json");
    }
    
    //download language file
    public function downloadFile ($code) {
        return response()->download("storage/languages/". $code .".json");
    }
}
