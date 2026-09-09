<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap_xml', 3600, function () {
            $urls = [
                ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
                ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.7'],
                ['loc' => route('services'), 'changefreq' => 'monthly', 'priority' => '0.7'],
                ['loc' => route('products.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
                ['loc' => route('partners'), 'changefreq' => 'monthly', 'priority' => '0.6'],
                ['loc' => route('contact'), 'changefreq' => 'yearly', 'priority' => '0.5'],
            ];

            Category::where('is_active', true)->get(['slug', 'updated_at'])->each(function ($category) use (&$urls) {
                $urls[] = [
                    'loc' => route('products.category', $category),
                    'lastmod' => $category->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            });

            Product::active()->get(['slug', 'updated_at'])->each(function ($product) use (&$urls) {
                $urls[] = [
                    'loc' => route('products.show', $product),
                    'lastmod' => $product->updated_at?->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ];
            });

            $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

            foreach ($urls as $url) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>'.e($url['loc'])."</loc>\n";
                if (! empty($url['lastmod'])) {
                    $xml .= '    <lastmod>'.$url['lastmod']."</lastmod>\n";
                }
                $xml .= '    <changefreq>'.$url['changefreq']."</changefreq>\n";
                $xml .= '    <priority>'.$url['priority']."</priority>\n";
                $xml .= "  </url>\n";
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n", 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
