<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Optimasi gambar terpusat: setiap <img> di respons HTML otomatis mendapat
 * loading="lazy" + decoding="async" + fallback onerror, TANPA harus diedit
 * satu per satu di Blade — termasuk <img> yang dirender dari konten DB
 * (berita, dokumentasi, postingan komunitas) yang tidak tersentuh Blade.
 *
 * Cara kerja: satu pass regex di atas HTML final. Skips:
 *  - img dengan data-eager  → di atas fold (hero, logo navbar), jangan ditunda.
 *  - img yang sudah punya loading= / onerror= sendiri.
 *
 * ponytail: regex `[^>]*` pecah jika atribut alt mengandung ">" literal —
 * belum pernah terjadi di konten situs ini; ganti parser HTML bila terjadi.
 */
class OptimizeImages
{
    private const PLACEHOLDER = '/assets/img-placeholder.svg';

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            return $response;
        }

        $html = $response->getContent();

        if (! $html || ! str_contains($html, '<img')) {
            return $response;
        }

        $response->setContent($this->inject($html));

        return $response;
    }

    private function inject(string $html): string
    {
        $fallback = ' onerror="this.onerror=null;this.src=\'' . self::PLACEHOLDER . '\'"';

        return preg_replace_callback('/<img\b[^>]*>/is', function ($m) use ($fallback) {
            $tag = $m[0];

            if (str_contains($tag, 'data-eager') || str_contains($tag, 'onerror=')) {
                return $tag;
            }

            $inject = '';

            if (! str_contains($tag, 'loading=')) {
                $inject .= ' loading="lazy"';
            }
            if (! str_contains($tag, 'decoding=')) {
                $inject .= ' decoding="async"';
            }

            return substr_replace($tag, $inject . $fallback, 4, 0);
        }, $html);
    }
}
