<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'store_name'     => Setting::get('store_name', 'Finexy POS Store'),
            'store_address'  => Setting::get('store_address', '123 Main Street, Yangon, Myanmar'),
            'store_phone'    => Setting::get('store_phone', '+95 9 123 456 789'),
            'tax_rate'       => Setting::get('tax_rate', '5'),
            'currency_symbol'=> Setting::get('currency_symbol', '$'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name'      => 'required|string|max:255',
            'store_address'   => 'required|string|max:500',
            'store_phone'     => 'required|string|max:50',
            'tax_rate'        => 'required|numeric|min:0|max:100',
            'currency_symbol' => 'required|string|max:10',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Store settings updated successfully.');
    }
}