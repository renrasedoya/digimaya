<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TroubleshooterNode;
use Illuminate\View\View;

class TroubleshooterController extends Controller
{
    public function index(): View
    {
        $nodes = TroubleshooterNode::where('is_active', true)
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'parent_id', 'type', 'label', 'answers', 'videos']);

        return view('public.tools.troubleshooter', compact('nodes'));
    }
}
