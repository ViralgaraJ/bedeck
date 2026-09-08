<?php

namespace App\Http\Requests\Admin;

use App\Services\ProductImageService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash', Rule::unique('products', 'slug')->ignore($productId)],
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:120'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:8000'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],

            // Product photo — accepts JPG, PNG, WEBP, GIF up to 12 MB.
            // System automatically fits/crops, centers, resizes and converts to 1200x1200 and 600x600 WEBP.
            'image' => [
                $this->isMethod('post') && ! $this->filled('keep_image') ? 'required' : 'nullable',
                File::image()
                    ->types(ProductImageService::ACCEPT)
                    ->max(ProductImageService::MAX_BYTES / 1024),
            ],

            'datasheet' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'A product photo is required.',
            'image.max' => 'The product photo must not be larger than 12 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
