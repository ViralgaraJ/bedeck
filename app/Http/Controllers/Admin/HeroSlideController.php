<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        $slides = HeroSlide::orderBy('sort_order')->get()->groupBy('page_key');

        return view('admin.hero-slides.index', [
            'pageKeys' => HeroSlide::PAGE_KEYS,
            'slides' => $slides,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'page_key' => ['required', 'string', 'in:'.implode(',', array_keys(HeroSlide::PAGE_KEYS))],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144', 'dimensions:min_width=800,min_height=300'],
        ]);

        $nextOrder = (int) (HeroSlide::where('page_key', $data['page_key'])->max('sort_order') ?? -1) + 1;

        HeroSlide::create([
            'page_key' => $data['page_key'],
            'image' => $this->storeImage($request->file('image'), $data['page_key']),
            'sort_order' => $nextOrder,
        ]);

        return redirect(route('admin.hero-slides.index').'#'.$data['page_key'])
            ->with('success', 'Carousel image added.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $this->deleteUploadedImage($heroSlide->image);
        $pageKey = $heroSlide->page_key;
        $heroSlide->delete();

        return redirect(route('admin.hero-slides.index').'#'.$pageKey)
            ->with('success', 'Carousel image removed.');
    }

    public function moveUp(HeroSlide $heroSlide): RedirectResponse
    {
        $this->swapWithSibling($heroSlide, 'up');

        return redirect(route('admin.hero-slides.index').'#'.$heroSlide->page_key);
    }

    public function moveDown(HeroSlide $heroSlide): RedirectResponse
    {
        $this->swapWithSibling($heroSlide, 'down');

        return redirect(route('admin.hero-slides.index').'#'.$heroSlide->page_key);
    }

    private function swapWithSibling(HeroSlide $slide, string $direction): void
    {
        $sibling = HeroSlide::where('page_key', $slide->page_key)
            ->when(
                $direction === 'up',
                fn ($q) => $q->where('sort_order', '<', $slide->sort_order)->orderByDesc('sort_order'),
                fn ($q) => $q->where('sort_order', '>', $slide->sort_order)->orderBy('sort_order'),
            )
            ->first();

        if (! $sibling) {
            return;
        }

        [$a, $b] = [$slide->sort_order, $sibling->sort_order];
        $slide->update(['sort_order' => $b]);
        $sibling->update(['sort_order' => $a]);
    }

    private function storeImage(UploadedFile $file, string $pageKey): string
    {
        $dir = public_path('uploads/hero/'.$pageKey);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/hero/'.$pageKey.'/'.$filename;
    }

    private function deleteUploadedImage(?string $path): void
    {
        if ($path && Str::startsWith($path, 'uploads/hero/')) {
            $abs = public_path($path);
            if (is_file($abs)) {
                @unlink($abs);
            }
        }
    }
}
