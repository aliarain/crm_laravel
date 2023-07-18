<?php

namespace App\Http\Controllers\Frontend\Localization;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class LocalizationController extends Controller
{
    public function index(Request $request, $locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);

        if ($request->ajax()) {
            return response()->json(1, 200);
        } else {
            return redirect()->back();
        }
    }

    public function langIndex($lang=null)
    {
        $data['title'] = _trans('common.ভাষা পরিবর্তন');
        $data['translations'] = Translation::all();
        return view('backend.language.show', compact('data'));
    }

    public function langEdit($id)
    {
        $data['title'] = _trans('common.ভাষা পরিবর্তন');
        $data['translations'] = Translation::where('id',$id)->get();
        return view('backend.language.edit', compact('data'));
    }


    public function value_store(Request $request)
    {
        $default =$request->default;
        $en =$request->en;
        $bn =$request->bn;

        $tran =  Translation::where('default',$default)->first();
        $tran->en = $en;
        $tran->bn = $bn;
        $tran->save();

        Toastr::success(_trans('response.Value stored successfully'), 'Success');
        return redirect(route('langIndex'));
    }
}
