<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name', 'country', 'logo', 'website', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset($this->logo) : null;
    }

    /** Shift every partner at or after $order down the list by one, opening up a slot at $order. */
    public static function makeRoomAt(int $order, ?int $ignoreId = null): void
    {
        static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('sort_order', '>=', $order)
            ->increment('sort_order');
    }

    /** Pull every partner after $order back by one, closing the gap left behind at $order. */
    public static function closeGapAt(int $order, ?int $ignoreId = null): void
    {
        static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('sort_order', '>', $order)
            ->decrement('sort_order');
    }

    /** Re-slot an existing partner from $oldOrder to $newOrder, nudging everything in between so no two partners share a position. */
    public static function moveToOrder(int $newOrder, int $oldOrder, int $ignoreId): void
    {
        if ($newOrder === $oldOrder) {
            return;
        }

        static::closeGapAt($oldOrder, $ignoreId);
        static::makeRoomAt($newOrder, $ignoreId);
    }
}
