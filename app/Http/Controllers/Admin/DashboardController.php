<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'activeCount' => Product::where('is_active', true)->count(),
            'featuredCount' => Product::where('is_featured', true)->count(),
            'categoryCount' => Category::count(),
            'partnerCount' => Partner::count(),
            'unreadEnquiries' => Enquiry::where('is_read', false)->count(),
            'recentProducts' => Product::with('category')->latest()->take(6)->get(),
            'recentEnquiries' => Enquiry::latest()->take(6)->get(),
        ]);
    }
}
