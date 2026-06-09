<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PreventDuplicateAdminSubmits
{
    /**
     * Window dedup dalam detik.
     * Kalau request signature yang sama masuk dalam window ini,
     * skip eksekusi dan redirect back dengan flash message.
     */
    private const WINDOW_SECONDS = 5;

    public function handle(Request $request, Closure $next): Response
    {
        // Hanya guard untuk method POST (create operations)
        // PUT/PATCH/DELETE biasanya idempotent by design
        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        // Skip kalau user belum auth (tidak akan kejadian di route admin)
        if (! $request->user()) {
            return $next($request);
        }

        // Build signature unik dari: user + route + payload
        $signature = $this->buildSignature($request);
        $cacheKey  = 'duplicate-submit:' . $signature;

        // Kalau signature udah ada di cache => duplicate detected
        if (Cache::has($cacheKey)) {
            return back()
                ->withInput()
                ->with('warning', 'Data sudah disimpan sebelumnya. Tidak diduplikasi.');
        }

        // Mark signature sebagai "sedang diproses" selama WINDOW_SECONDS
        Cache::put($cacheKey, true, self::WINDOW_SECONDS);

        return $next($request);
    }

    /**
     * Build signature dari user, route, dan payload.
     * Hash deterministic biar same input = same signature.
     */
    private function buildSignature(Request $request): string
    {
        $payload = $request->except([
            '_token',     // CSRF token: beda tiap request, exclude
            '_method',    // Method spoof: bukan content
            'password',   // Sensitive: jangan masuk hash
            'password_confirmation',
        ]);

        // Ignore file upload binary content (cuma pakai filename)
        // File yang sama nama tapi berbeda byte tetap dianggap "same intent"
        if ($request->hasFile('image_file') || $request->hasFile('image') || $request->hasFile('file')) {
            $files = [];
            foreach ($request->files->all() as $key => $file) {
                if (is_array($file)) {
                    foreach ($file as $f) {
                        if ($f) $files[] = $key . ':' . $f->getClientOriginalName();
                    }
                } elseif ($file) {
                    $files[] = $key . ':' . $file->getClientOriginalName();
                }
            }
            $payload['__files'] = implode('|', $files);
        }

        ksort($payload);

        return hash('sha256', implode('|', [
            $request->user()->id,
            $request->method(),
            $request->path(),
            json_encode($payload),
        ]));
    }
}