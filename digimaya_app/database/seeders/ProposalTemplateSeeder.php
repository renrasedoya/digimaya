<?php

namespace Database\Seeders;

use App\Models\ProposalTemplate;
use App\Services\ProposalTemplateService;
use Illuminate\Database\Seeder;

class ProposalTemplateSeeder extends Seeder
{
    /**
     * Seed the two starter templates from the (placeholder) ProposalTemplateService.
     * Idempotent: updateOrCreate by key, safe to run more than once.
     */
    public function run(): void
    {
        $service = new ProposalTemplateService();

        foreach (ProposalTemplateService::TEMPLATES as $key => $name) {
            ProposalTemplate::updateOrCreate(
                ['key' => $key],
                ['name' => $name, 'content_blocks' => $service->blocksFor($key)]
            );
        }
    }
}
