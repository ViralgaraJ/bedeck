<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /** Editable keys and their labels. */
    private const FIELDS = [
        'company_name' => 'Company name',
        'company_tagline' => 'Tagline / slogan',
        'company_role' => 'Role line (e.g. Engineering Consultants)',
        'established_year' => 'Established year',
        'hero_eyebrow' => 'Homepage hero — eyebrow',
        'hero_heading' => 'Homepage hero — heading',
        'hero_intro' => 'Homepage hero — intro paragraph',
        'address' => 'Address',
        'phone' => 'Phone',
        'whatsapp' => 'WhatsApp number (digits only, e.g. 94771711440)',
        'whatsapp_display' => 'WhatsApp number (display)',
        'email' => 'Primary email',
        'email_alt' => 'Secondary email',
        'stat_products' => 'Stat — product entries',
        'stat_partners' => 'Stat — principal partners',
        'stat_categories' => 'Stat — product categories',
    ];

    public function edit(): View
    {
        return view('admin.settings', [
            'fields' => self::FIELDS,
            'values' => SiteSetting::pluck('value', 'key')->all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate(
            collect(self::FIELDS)->mapWithKeys(fn ($label, $key) => [
                $key => ['nullable', 'string', 'max:2000'],
            ])->all()
        );

        foreach ($data as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return back()->with('success', 'Settings saved.');
    }
}
