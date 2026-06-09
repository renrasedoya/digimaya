<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateVerifyController extends Controller
{
    /**
     * Public form for entering a certificate number to verify.
     */
    public function form(Request $request)
    {
        // Allow ?q=DGMY-... redirect for convenience
        if ($request->filled('q')) {
            $number = strtoupper(trim($request->input('q')));
            return redirect()->route('certificate.verify.show', ['number' => $number]);
        }

        return view('public.certificate.verify-form');
    }

    /**
     * Public verify page — show certificate validity by number.
     */
    public function show(string $number)
    {
        $number = strtoupper(trim($number));

        $certificate = Certificate::where('certificate_number', $number)->first();

        return view('public.certificate.verify-show', compact('certificate', 'number'));
    }
}
