<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingFormRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Class SettingController
 * @package App\Http\Controllers
 */
class SettingController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $settings = Setting::all()->keyBy('name');
        return view('settings.index', compact('settings'));
    }

    /**
     * @param SettingFormRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingFormRequest $request)
    {
        $settings = Setting::all()->keyBy('name');

        $settings['plan_month_day']->update(['value' => $request->plan_month_day]);
        
        return redirect()
            ->route('settings.index')
            ->with('settings_message', 'Настройки обновлены');
    }


}
