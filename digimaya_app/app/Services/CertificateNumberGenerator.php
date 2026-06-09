<?php

namespace App\Services;

use App\Models\Certificate;

class CertificateNumberGenerator
{
    public static function next(): string
    {
        $year = date('Y');
        $maxAttempts = 10;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $hex = strtoupper(bin2hex(random_bytes(3)));
            $number = "DGMY-{$year}-{$hex}";

            // NOTE: certificates use HARD delete (no SoftDeletes trait), so a
            // plain exists() check is correct here. IF Certificate ever adopts
            // SoftDeletes, this MUST become ->withTrashed()->where(...)->exists()
            // — a soft-deleted row still occupies the unique certificate_number.
            if (!Certificate::where('certificate_number', $number)->exists()) {
                return $number;
            }
        }

        throw new \RuntimeException('Failed to generate unique certificate number after ' . $maxAttempts . ' attempts');
    }
}
