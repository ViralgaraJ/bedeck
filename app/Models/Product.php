<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'brand', 'image', 'image_thumb',
        'short_description', 'description', 'keywords', 'datasheet',
        'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (blank($product->slug)) {
                $product->slug = static::uniqueSlug($product->name, $product->id);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base ?: 'product';
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Resolve a stored path (assets/... seeded, or uploads/... uploaded) to a URL. */
    public function getImageUrlAttribute(): string
    {
        if (blank($this->image)) {
            return asset('assets/images/site/placeholder.svg');
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset($this->image);
    }

    public function getThumbUrlAttribute(): string
    {
        $path = $this->image_thumb ?: $this->image;
        if (blank($path)) {
            return asset('assets/images/site/placeholder.svg');
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset($path);
    }

    public function getDatasheetUrlAttribute(): ?string
    {
        return $this->datasheet ? asset($this->datasheet) : null;
    }

    /** Shift every product at or after $order down the list by one, opening up a slot at $order. */
    public static function makeRoomAt(int $order, ?int $ignoreId = null): void
    {
        static::query()
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->where('sort_order', '>=', $order)
            ->increment('sort_order');
    }

    /** Pull every product after $order back by one, closing the gap left behind at $order. */
    public static function closeGapAt(int $order, ?int $ignoreId = null): void
    {
        static::query()
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->where('sort_order', '>', $order)
            ->decrement('sort_order');
    }

    /** Re-slot an existing product from $oldOrder to $newOrder, nudging everything in between so no two products share a position. */
    public static function moveToOrder(int $newOrder, int $oldOrder, int $ignoreId): void
    {
        if ($newOrder === $oldOrder) {
            return;
        }

        static::closeGapAt($oldOrder, $ignoreId);
        static::makeRoomAt($newOrder, $ignoreId);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }
        $like = '%'.str_replace('%', '\\%', $term).'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('brand', 'like', $like)
                ->orWhere('short_description', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('keywords', 'like', $like);
        });
    }
}
