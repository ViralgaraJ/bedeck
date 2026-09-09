<?php

namespace App\Http\Controllers;

use App\Mail\EnquiryNotification;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $heroImages = collect(range(1, 7))
            ->map(fn ($n) => sprintf('assets/images/site/hero-slide-%02d.webp', $n))
            ->filter(fn ($p) => is_file(public_path($p)))
            ->values();

        return view('pages.home', [
            'heroImages' => $heroImages,
            'services' => Service::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->withCount(['products' => fn ($q) => $q->where('is_active', true)])->orderBy('sort_order')->get(),
            'featured' => Product::active()->with('category')->where('is_featured', true)->orderBy('sort_order')->take(8)->get(),
            'partners' => Partner::where('is_active', true)->orderBy('sort_order')->get(),
            'showcase' => Product::active()->where('is_featured', true)->orderBy('sort_order')->take(6)->get()
                ->whenEmpty(fn () => Product::active()->orderBy('sort_order')->take(6)->get()),
            'statProducts' => Product::active()->count(),
            'statPartners' => Partner::where('is_active', true)->count(),
            'statCategories' => Category::where('is_active', true)->count(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'services' => Service::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function services(): View
    {
        return view('pages.services', [
            'services' => Service::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function partners(): View
    {
        return view('pages.partners', [
            'partners' => Partner::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'products' => Product::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $key = 'enquiry:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withInput()->with('error', 'Too many messages sent. Please try again later.');
        }
        RateLimiter::hit($key, 3600);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'company' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:160'],
            'product_id' => ['nullable', 'exists:products,id'],
            'message' => ['required', 'string', 'max:4000'],
            'website' => ['nullable', 'size:0'], // honeypot
        ], [
            'website.size' => 'Spam detected.',
        ]);

        unset($data['website']);
        $data['ip_address'] = $request->ip();

        $enquiry = Enquiry::create($data);

        $this->notifyByEmail($enquiry);

        return back()->with('success', 'Thank you. Your enquiry has been received and Bedeck International will respond soon.');
    }

    /**
     * Email the enquiry to the configured recipient. A mail failure must never
     * break the visitor's submission — the enquiry is already stored.
     */
    protected function notifyByEmail(Enquiry $enquiry): void
    {
        $to = config('mail.enquiry_to') ?: config('mail.from.address');

        if (blank($to)) {
            return;
        }

        try {
            $enquiry->loadMissing('product');
            Mail::to($to)->send(new EnquiryNotification($enquiry));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
