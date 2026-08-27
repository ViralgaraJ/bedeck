<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/products.json');
        if (! is_file($path)) {
            $this->command->warn("products.json not found at {$path}");

            return;
        }

        $items = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $categories = Category::pluck('id', 'name');

        $featured = [
            'ISOIL SBM 32 PD Meter',
            'E&H ProMass H 300 Coriolis Meter',
            'HYTEK ALPHA FMC FC10 Dispenser',
            'ISOIL ISOVALVE',
            'FLUIDWELL C Series Batch Controller',
            'ACCORD Forecourt Controller',
            'ISOIL Automation Solutions',
            'E&H FMP55 Guided Radar Level',
        ];

        foreach ($items as $i => $item) {
            $name = trim($item['name']);
            $description = trim($item['description'] ?? '');

            Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $categories[$item['category']] ?? null,
                    'name' => $name,
                    'brand' => $item['brand'] ?? null,
                    'image' => $item['image'] ?? null,
                    'image_thumb' => $item['image'] ?? null,
                    'short_description' => Str::limit(Str::before($description, '. ').'.', 160, ''),
                    'description' => $description,
                    'keywords' => $item['keywords'] ?? null,
                    'is_featured' => in_array($name, $featured, true),
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ],
            );
        }
    }
}
