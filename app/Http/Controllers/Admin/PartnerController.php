<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(): View
    {
        return view('admin.partners.index', [
            'partners' => Partner::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.partners.create', [
            'partner' => new Partner([
                'is_active' => true,
                'sort_order' => (Partner::max('sort_order') ?? 0) + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePartner($request);

        if ($request->hasFile('logo_file')) {
            $data['logo'] = $this->storeLogo(
                $request->file('logo_file'),
                $data['name']
            );
        }

        Partner::create($data);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner added successfully.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $data = $this->validatePartner($request);

        if ($request->hasFile('logo_file')) {
            $this->deleteCustomLogo($partner->logo);

            $data['logo'] = $this->storeLogo(
                $request->file('logo_file'),
                $data['name']
            );
        }

        $partner->update($data);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $this->deleteCustomLogo($partner->logo);

        $name = $partner->name;

        $partner->delete();

        return redirect()
            ->route('admin.partners.index')
            ->with('success', "Partner \"{$name}\" deleted successfully.");
    }

    private function validatePartner(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'country' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:255', 'url'],
            'logo_file' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp,svg',
                'max:2048',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }

    private function storeLogo($file, string $name): string
    {
        $dir = public_path('uploads/partners');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = Str::slug($name).'-'.time().'.'.$file->getClientOriginalExtension();

        $file->move($dir, $filename);

        return 'uploads/partners/'.$filename;
    }

    private function deleteCustomLogo(?string $path): void
    {
        if ($path && Str::startsWith($path, 'uploads/partners/')) {
            $absPath = public_path($path);

            if (is_file($absPath)) {
                @unlink($absPath);
            }
        }
    }
}