<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProposalPublicController extends Controller
{
    public function show(string $token): View
    {
        $proposal = $this->findPublishedOrFail($token);

        $blocks = is_array($proposal->published_content) ? $proposal->published_content : [];

        return view('public.proposals.show', compact('proposal', 'blocks'));
    }

    public function downloadPdf(string $token): Response
    {
        $proposal = $this->findPublishedOrFail($token);

        $blocks = is_array($proposal->published_content) ? $proposal->published_content : [];

        $safe = preg_replace('/[^A-Za-z0-9_.-]+/', '-', $proposal->title ?: 'proposal');
        $filename = 'Proposal-' . trim($safe, '-') . '.pdf';

        return Pdf::loadView('admin.proposals.pdf', [
            'proposal' => $proposal,
            'blocks' => $blocks,
        ])->setPaper('a4', 'portrait')->setOption('isRemoteEnabled', true)->download($filename);
    }

    /**
     * Resolve a published proposal by token, or 404.
     * Draft / unpublished / unknown token => 404 (no info leak).
     */
    private function findPublishedOrFail(string $token): Proposal
    {
        $proposal = Proposal::where('public_token', $token)->first();

        if (! $proposal || ! $proposal->isPublished()) {
            throw new NotFoundHttpException();
        }

        return $proposal;
    }
}
