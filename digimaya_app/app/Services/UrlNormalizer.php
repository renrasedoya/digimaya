<?php

namespace App\Services;

use Illuminate\Http\Request;

class UrlNormalizer
{
    /**
     * Normalize URL — prepend https:// if scheme missing.
     * Empty/null returns null. Already has http(s):// → unchanged.
     */
    public static function normalize(?string $url): ?string
    {
        if (empty(trim($url ?? ''))) {
            return null;
        }

        $url = trim($url);

        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        return $url;
    }

    /**
     * Normalize multiple URL fields in a Request (in-place via merge).
     */
    public static function normalizeRequest(Request $request, array $fields): void
    {
        $merge = [];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $merge[$field] = self::normalize($request->input($field));
            }
        }
        if (!empty($merge)) {
            $request->merge($merge);
        }
    }
}
