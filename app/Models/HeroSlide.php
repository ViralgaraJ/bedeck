<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    /** Every page whose hero carousel is admin-managed, keyed by page_key => display label. */
    public const PAGE_KEYS = [
        'home' => 'Home',
        'about' => 'About',
        'services' => 'Services',
        'products' => 'Products',
        'partners' => 'Partners',
        'contact' => 'Contact',
    ];

    protected $fillable = ['page_key', 'image', 'sort_order'];

    public function getImageUrlAttribute(): string
    {
        return asset($this->image);
    }
}
