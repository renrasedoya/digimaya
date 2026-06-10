<?php

namespace App\Services;

/**
 * Decode + sanitize the content_blocks coming from the Alpine builder.
 * Unknown keys are dropped; bodies are HTML-sanitized (blog profile).
 *
 * Logic moved verbatim from ProposalController::parseBlocks() so that the
 * proposal editor (and, later, the template editor) share one source of truth.
 *
 * Accepts either an already-decoded array or a JSON string (the hidden
 * content_blocks field), matching the original behaviour exactly.
 */
class ProposalBlockParser
{
    public function parse($raw): array
    {
        if (is_array($raw)) {
            $decoded = $raw;
        } else {
            $decoded = json_decode((string) $raw, true);
        }

        if (!is_array($decoded)) {
            return [];
        }

        $clean = [];
        foreach ($decoded as $block) {
            if (!is_array($block) || empty($block['type'])) {
                continue;
            }

            $type = $block['type'];

            if ($type === 'custom') {
                $imageUrl = trim((string) ($block['image_url'] ?? ''));
                // URL-only for now (upload layer added later). Accept only http(s) URLs.
                if ($imageUrl !== '' && !str_starts_with($imageUrl, 'http')) {
                    $imageUrl = '';
                }
                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'custom',
                    'title' => mb_substr(trim((string) ($block['title'] ?? '')), 0, 255),
                    'body' => clean((string) ($block['body'] ?? ''), 'blog'),
                    'image_url' => mb_substr($imageUrl, 0, 1000),
                    'caption' => mb_substr(trim((string) ($block['caption'] ?? '')), 0, 255),
                ];
            } elseif ($type === 'snippet') {
                // Copy-on-insert: title + body + images are stored on the block itself,
                // fully editable, decoupled from the source snippet record.
                // Images are URL strings (Bagian A: shared URLs, no physical copy yet).
                $images = is_array($block['images'] ?? null) ? $block['images'] : [];
                $images = array_values(array_filter(
                    array_map(fn ($u) => mb_substr(trim((string) $u), 0, 2000), $images),
                    fn ($u) => $u !== '' && str_starts_with($u, 'http')
                ));
                $images = array_slice($images, 0, 8);

                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'snippet',
                    'title' => mb_substr(trim((string) ($block['title'] ?? '')), 0, 255),
                    'body' => clean((string) ($block['body'] ?? ''), 'blog'),
                    'images' => $images,
                ];
            } elseif ($type === 'pricing') {
                // Stores only the display choice; tier rows are resolved live at render.
                $option = $block['option'] ?? 'all';
                if (!in_array($option, ['all', 'lower', 'upper'], true)) {
                    $option = 'all';
                }
                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'pricing',
                    'heading' => mb_substr(trim((string) ($block['heading'] ?? '')), 0, 255),
                    'option' => $option,
                ];
            } elseif ($type === 'reference') {
                // Stores source + ids only; actual records resolved live at render,
                // frozen into the snapshot on publish (Fase 4).
                $source = $block['source'] ?? '';
                if (!in_array($source, ['logo_wall', 'testimonials', 'case_studies', 'services'], true)) {
                    continue; // invalid source, drop the block
                }
                $ids = $block['ids'] ?? [];
                $ids = is_array($ids) ? array_values(array_unique(array_map('intval', $ids))) : [];
                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'reference',
                    'heading' => mb_substr(trim((string) ($block['heading'] ?? '')), 0, 255),
                    'source' => $source,
                    'ids' => $ids,
                ];
            }
        }

        return $clean;
    }
}
