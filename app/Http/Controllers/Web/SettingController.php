<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:update_privacy_policy'])->only(['storePrivacyPolicy', 'privacyPolicy']);
    }


    public function privacyPolicy()
    {
        $setting = Setting::where('key', 'privacy_policy')->first();
        if (!$setting)
            return redirect()->back()->withErrors(['صفحه مورد نظر یافت نشد.']);
        return view('settings.privacy-policy', compact('setting'));
    }

    public function storePrivacyPolicy(Request $request)
    {
        $setting = Setting::where('key', 'privacy_policy')->first();
        if (!$setting) {
            $setting = Setting::create([
                'key' => 'privacy_policy',
                'value' => null
            ]);
        }
        $setting->update(['value' => $request->get('editor')]);
        return response()->json(['message' => 'اطلاعات با موفقیت به روز رسانی شدند.', 'redirect' => url('dashboard/settings/privacy-policy/edit')]);
    }
}
