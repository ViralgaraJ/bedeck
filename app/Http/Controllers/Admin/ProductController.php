<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->when($request->filled('q'), fn ($q) => $q->search($request->string('q')))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(['is_active' => true, 'sort_order' => (Product::max('sort_order') ?? 0) + 1]),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Product::uniqueSlug($data['name']);

        $product = new Product($data);

        if ($request->hasFile('image')) {
            $paths = $this->images->store($request->file('image'), $product->slug);
            $product->fill($paths);
        }

        if ($request->hasFile('datasheet')) {
            $product->datasheet = $this->storeDatasheet($request, $product->slug);
        }

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', "“{$product->name}” has been added.");
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: $product->slug;
        $product->fill($data);

        if ($request->hasFile('image')) {
            $old = [$product->getOriginal('image'), $product->getOriginal('image_thumb')];
            $paths = $this->images->store($request->file('image'), $product->slug);
            $product->fill($paths);
            $this->images->delete(...$old);
        }

        if ($request->hasFile('datasheet')) {
            $product->datasheet = $this->storeDatasheet($request, $product->slug);
        }

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', "“{$product->name}” has been updated.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->images->delete($product->image, $product->image_thumb);
        if ($product->datasheet && Str::startsWith($product->datasheet, 'uploads/')) {
            @unlink(public_path($product->datasheet));
        }
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "“{$name}” has been deleted.");
    }

    private function storeDatasheet(Request $request, string $slug): string
    {
        $dir = public_path('uploads/datasheets');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = $slug.'-'.now()->format('YmdHis').'.pdf';
        $request->file('datasheet')->move($dir, $name);

        return 'uploads/datasheets/'.$name;
    }
}
