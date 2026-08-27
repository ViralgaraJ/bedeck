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

            // Product photo — square-ish, min 600x600, recommended 1200x1200, max 4 MB.
            // It is centre-cropped to a perfect square and re-encoded as WEBP on save.
            'image' => [
                $this->isMethod('post') && ! $this->filled('keep_image') ? 'required' : 'nullable',
                File::image()
                    ->types(ProductImageService::ACCEPT)
                    ->max(ProductImageService::MAX_BYTES / 1024),
                Rule::dimensions()
                    ->minWidth(ProductImageService::MIN_SIZE)
                    ->minHeight(ProductImageService::MIN_SIZE),
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! $value) {
                        return;
                    }
                    $size = @getimagesize($value->getRealPath());
                    if (! $size) {
                        $fail('The product photo could not be read.');
                        return;
                    }
                    [$w, $h] = $size;
                    $ratio = $w / max($h, 1);
                    if ($ratio < 0.8 || $ratio > 1.25) {
                        $fail('The product photo should be roughly square (between 4:5 and 5:4). It will be centre-cropped to 1:1.');
                    }
                },
            ],

            'datasheet' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.dimensions' => 'The product photo must be at least '
                .ProductImageService::MIN_SIZE.' x '.ProductImageService::MIN_SIZE
                .' px. 1200 x 1200 px square is recommended.',
            'image.required' => 'A product photo is required.',
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
