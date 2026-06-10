<?php

namespace App\Services;

use App\Models\CaseStudy;
use App\Models\LogoWallItem;
use App\Models\PricingTier;
use App\Models\Proposal;
use App\Models\Testimonial;

class ProposalSnapshotService
{
    public function resolve(Proposal $proposal): array
    {
        $blocks = is_array($proposal->content_blocks) ? $proposal->content_blocks : [];
        $resolved = [];

        foreach ($blocks as $block) {
            $type = $block['type'] ?? null;

            if ($type === 'custom' || $type === 'snippet') {
                $images = is_array($block['images'] ?? null) ? $block['images'] : [];
                $images = array_values(array_filter(
                    array_map(fn ($u) => (string) $u, $images),
                    fn ($u) => $u !== ''
                ));

                $resolved[] = [
                    'type' => $type,
                    'title' => (string) ($block['title'] ?? ''),
                    'body' => (string) ($block['body'] ?? ''),
                    'image_url' => (string) ($block['image_url'] ?? ''),
                    'caption' => (string) ($block['caption'] ?? ''),
                    'images' => $images,
                ];
            } elseif ($type === 'pricing') {
                $resolved[] = [
                    'type' => 'pricing',
                    'heading' => (string) ($block['heading'] ?? ''),
                    'rows' => $this->resolvePricing($block['option'] ?? 'all'),
                ];
            } elseif ($type === 'reference') {
                $resolved[] = [
                    'type' => 'reference',
                    'heading' => (string) ($block['heading'] ?? ''),
                    'source' => (string) ($block['source'] ?? ''),
                    'items' => $this->resolveReference($block['source'] ?? '', $block['ids'] ?? []),
                ];
            }
        }

        return $resolved;
    }

    private function resolvePricing(string $option): array
    {
        $query = PricingTier::where('is_active', true);

        if ($option === 'lower') {
            $query->where('zone', 'lower');
        } elseif ($option === 'upper') {
            $query->where('zone', 'upper');
        }

        return $query->orderBy('sort_order')->orderBy('budget')
            ->get(['budget', 'agency_fee'])
            ->map(fn ($t) => ['budget' => (int) $t->budget, 'agency_fee' => (int) $t->agency_fee])
            ->values()
            ->all();
    }

    private function resolveReference(string $source, $ids): array
    {
        $ids = is_array($ids) ? array_map('intval', $ids) : [];
        if (empty($ids)) {
            return [];
        }

        if ($source === 'logo_wall') {
            $rows = LogoWallItem::active()->whereIn('id', $ids)->ordered()
                ->get(['id', 'name', 'image']);
            return $rows->map(fn ($r) => [
                'name' => (string) $r->name,
                'image' => (string) ($r->image_url ?? ''), // accessor: local path -> absolute URL
            ])->values()->all();
        }

        if ($source === 'testimonials') {
            $rows = Testimonial::active()->whereIn('id', $ids)->ordered()
                ->get(['id', 'name', 'position', 'company', 'photo', 'quote', 'rating']);
            return $rows->map(fn ($r) => [
                'name' => (string) $r->name,
                'position' => (string) $r->position,
                'company' => (string) $r->company,
                'photo' => (string) ($r->photo_url ?? ''), // accessor: local path -> absolute URL
                'quote' => (string) $r->quote,
                'rating' => (int) $r->rating,
            ])->values()->all();
        }

        if ($source === 'case_studies') {
            $rows = CaseStudy::active()->whereIn('id', $ids)->ordered()
                ->get(['id', 'client_name', 'title', 'industry', 'thumbnail', 'problem', 'solution']);
            return $rows->map(fn ($r) => [
                'client_name' => (string) $r->client_name,
                'title' => (string) $r->title,
                'industry' => (string) $r->industry,
                'thumbnail' => (string) ($r->thumbnail_url ?? ''), // accessor: local path -> absolute URL
                'problem' => (string) $r->problem,
                'solution' => (string) $r->solution,
            ])->values()->all();
        }

        if ($source === 'services') {
            $rows = \App\Models\PublicService::active()->whereIn('id', $ids)->ordered()
                ->get(['id', 'title', 'description', 'icon_image', 'icon_url']);
            return $rows->map(fn ($r) => [
                'title' => (string) $r->title,
                'description' => (string) $r->description,
                'icon' => (string) ($r->icon_src ?? ''),
            ])->values()->all();
        }

        return [];
    }
}