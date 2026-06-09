<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Faq;
use App\Models\Module;
use App\Models\Testimonial;

class AcademyLandingController extends Controller
{
    /**
     * GET /google-ads-academy
     */
    public function index()
    {
        $modules = Module::query()
            ->where('is_published', true)
            ->orderBy('display_order')
            ->orderBy('id')
            ->limit(6)
            ->get();

        return view('public.academy.landing', compact('modules'));
    }

    /**
     * GET /corporate-training
     */
    public function corporate()
    {
        return view('public.academy.corporate-training', $this->corporateData());
    }

    /**
     * Shared CMS data for corporate training page.
     *
     * @return array
     */
    protected function corporateData(): array
    {
        return [
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