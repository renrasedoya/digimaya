<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\ComparisonRow;
use App\Models\Faq;
use App\Models\LogoWallItem;
use App\Models\PublicService;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $awards = LogoWallItem::active()
            ->group('awards')
            ->ordered()
            ->get();

        $clients = LogoWallItem::active()
            ->group('clients')
            ->ordered()
            ->get();

        $services = PublicService::active()
            ->ordered()
            ->get();

        $comparisonRows = ComparisonRow::active()
            ->ordered()
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('position_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $caseStudies = CaseStudy::where('is_active', true)
            ->orderBy('position_order', 'asc')
            ->orderBy('id', 'desc')
            ->limit(2)
            ->get();

        $faqs = Faq::where('is_active', true)
            ->orderBy('position_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('home', [
            'awards'         => $awards,
            'clients'        => $clients,
            'services'       => $services,
            'comparisonRows' => $comparisonRows,
            'testimonials'   => $testimonials,
            'caseStudies'    => $caseStudies,
            'faqs'           => $faqs,
        ]);
    }
}
