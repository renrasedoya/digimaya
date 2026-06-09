<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\ComparisonRow;
use App\Models\Faq;
use App\Models\Testimonial;

class ServicesController extends Controller
{
    /**
     * GET /google-ads-management
     */
    public function management()
    {
        return view('public.services.google-ads-management', $this->sharedData());
    }

    /**
     * GET /google-ads-audit
     */
    public function audit()
    {
        return view('public.services.google-ads-audit', $this->sharedData());
    }

    /**
     * GET /google-ads-consulting
     */
    public function consulting()
    {
        return view('public.services.google-ads-consulting', $this->sharedData());
    }

    /**
     * Shared CMS data for all service pages.
     *
     * @return array
     */
    protected function sharedData(): array
    {
        return [
            'comparisonRows' => ComparisonRow::where('is_active', true)
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')
                ->get(),

            'caseStudy' => CaseStudy::where('is_active', true)
                ->orderBy('position_order', 'asc')
                ->orderBy('id', 'desc')
                ->with('results')
                ->first(),

            'testimonial' => Testimonial::where('is_active', true)
                ->orderBy('position_order', 'asc')
                ->orderBy('id', 'asc')
                ->first(),

            'faqs' => Faq::where('is_active', true)
                ->orderBy('position_order', 'asc')
                ->orderBy('id', 'asc')
                ->get(),
        ];
    }
}