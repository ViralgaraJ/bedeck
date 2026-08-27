<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = $categories->firstWhere('slug', $request->string('category'));
        }

        $products = Product::active()
            ->with('category')
            ->when($activeCategory, fn ($q) => $q->where('category_id', $activeCategory->id))
            ->search($request->string('q'))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->paginate(24)
            ->withQueryString();

        return view('pages.products.index', [
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'products' => $products,
            'q' => $request->string('q')->toString(),
            'total' => Product::active()->count(),
        ]);
    }

    public function category(Category $category, Request $request): View
    {
        abort_unless($category->is_active, 404);

        $products = $category->products()
            ->where('is_active', true)
            ->with('category')
            ->search($request->string('q'))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->paginate(24)
            ->withQueryString();

        return view('pages.products.category', [
            'category' => $category,
            'products' => $products,
            'categories' => Category::where('is_active', true)
                ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('sort_order')->get(),
            'q' => $request->string('q')->toString(),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);
        $product->load('category');

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($related->count() < 4) {
            $related = $related->merge(
                Product::active()->where('id', '!=', $product->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->inRandomOrder()->take(4 - $related->count())->get()
            );
        }

        return view('pages.products.show', compact('product', 'related'));
    }
}
