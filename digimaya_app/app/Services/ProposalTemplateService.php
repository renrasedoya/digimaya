<?php

namespace App\Services;

use App\Models\LogoWallItem;
use App\Models\ProposalSnippet;
use App\Models\Testimonial;

/**
 * Builds the default set of content blocks for a proposal template.
 *
 * NOTE (placeholder phase): the content here is intentionally assembled from
 * existing dummy data (active snippets, pricing rate card, logo wall, testimonials)
 * purely to test the create -> auto-fill -> delete -> publish -> PDF flow.
 * It is NOT final proposal copy. Block shapes mirror the builder/parseBlocks
 * contract exactly, so the builder, snapshot, publish and PDF reuse them as-is.
 */
class ProposalTemplateService
{
    public const TEMPLATES = [
        'agency' => 'Agency',
        'training' => 'Training',
    ];

    /** @return array<string,string> key => label, for the create form selector */
    public function options(): array
    {
        return self::TEMPLATES;
    }

    public function isValid(string $template): bool
    {
        return array_key_exists($template, self::TEMPLATES);
    }

    /**
     * Return the default content_blocks array for the given template.
     * Same shape as ProposalController::parseBlocks() output.
     *
     * @return array<int,array<string,mixed>>
     */
    public function blocksFor(string $template): array
    {
        $blocks = [];

        // --- Section teks: pakai snippet dummy yang sudah ada (copy-on-insert) ---
        // Agency: semua snippet aktif. Training: ambil 1 snippet saja (beda minimal).
        $snippets = ProposalSnippet::active()->ordered()->get(['title', 'body']);
        $textSnippets = $template === 'training' ? $snippets->take(1) : $snippets;

        foreach ($textSnippets as $snippet) {
            $blocks[] = [
                'uid' => $this->uid(),
                'type' => 'snippet',
                'title' => (string) $snippet->title,
                'body' => (string) $snippet->body,
            ];
        }

        // --- Section pricing: 1 block (resolusi tier dilakukan live oleh snapshot) ---
        // Agency: semua harga. Training: level bawah saja.
        $blocks[] = [
            'uid' => $this->uid(),
            'type' => 'pricing',
            'heading' => 'Pricing',
            'option' => $template === 'training' ? 'lower' : 'all',
        ];

        // --- Section reference: Logo Wall (semua item aktif) ---
        $logoIds = LogoWallItem::active()->ordered()->pluck('id')->map(fn ($id) => (int) $id)->all();
        if (! empty($logoIds)) {
            $blocks[] = [
                'uid' => $this->uid(),
                'type' => 'reference',
                'heading' => 'Logo Wall',
                'source' => 'logo_wall',
                'ids' => $logoIds,
            ];
        }

        // --- Section reference: Testimoni (hanya Agency, untuk pembeda minimal) ---
        if ($template !== 'training') {
            $testimonialIds = Testimonial::active()->ordered()->pluck('id')->map(fn ($id) => (int) $id)->all();
            if (! empty($testimonialIds)) {
                $blocks[] = [
                    'uid' => $this->uid(),
                    'type' => 'reference',
                    'heading' => 'Testimoni',
                    'source' => 'testimonials',
                    'ids' => $testimonialIds,
                ];
            }
        }

        return $blocks;
    }

    private function uid(): string
    {
        return uniqid('b', true);
    }
}
