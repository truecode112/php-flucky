<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /**
     * Manage site pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = Content::get();

        return view('admin.page.index', [
            'page' => __('Pages'),
            'data' => $data,
        ]);
    }

    //return the pages page
    public function edit($id)
    {
        $model = Content::find($id);

        return view('admin.page.edit', [
            'page' => __('Pages'),
            'model' => $model,
        ]);
    }

    //update pages
    public function update(Request $request)
    {
        $model = Content::find($request->id);

        $request->validate([
            'value' => 'required',
        ]);

        $model->value = $request->value;

        if ($model->save()) {
            Cache::forget('content');
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }
}
