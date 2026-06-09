<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProposalPublicController extends Controller
{
    public function show(string $token): View
    {
        $proposal = Proposal::where('public_token', $token)->first();

        // Only published proposals are publicly viewable.
        // Draft / unpublished / unknown token => 404 (no info leak).
        if (!$proposal || !$proposal->isPublished()) {
            throw new NotFoundHttpException();
        }

        $blocks = is_array($proposal->published_content) ? $proposal->published_content : [];

        return view('public.proposals.show', compact('proposal', 'blocks'));
    }
}
