<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\ClientStatusHistory;
use Illuminate\Console\Command;

class BackfillClientStatusHistory extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'crm:backfill-status-history
                            {--dry-run : Preview without inserting rows}';

    /**
     * The console command description.
     */
    protected $description = 'Backfill client_status_history with one initial entry per existing client';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('=== DRY RUN MODE — no rows will be inserted ===');
        }

        // Iterate all clients including soft-deleted, to be inclusive
        $clients = Client::query()->get();

        $this->info(sprintf('Found %d clients (excluding soft-deleted)', $clients->count()));

        $inserted = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($clients->count());
        $bar->start();

        foreach ($clients as $client) {
            // Idempotent: skip if client already has any history row
            $hasHistory = ClientStatusHistory::where('client_id', $client->id)->exists();

            if ($hasHistory) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if (! $isDryRun) {
                ClientStatusHistory::create([
                    'client_id'   => $client->id,
                    'status_from' => null,
                    'status_to'   => $client->status,
                    'changed_at'  => $client->created_at,
                    'changed_by'  => null,
                    'notes'       => 'Backfill: initial record (historical data)',
                ]);
            }

            $inserted++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('=== Summary ===');
        $this->line(sprintf('  Inserted: %d', $inserted));
        $this->line(sprintf('  Skipped (already had history): %d', $skipped));
        $this->line(sprintf('  Total processed: %d', $inserted + $skipped));

        if ($isDryRun) {
            $this->warn('Dry run complete — no rows actually inserted.');
        } else {
            $totalHistory = ClientStatusHistory::count();
            $this->info(sprintf('Total history rows now: %d', $totalHistory));
        }

        return Command::SUCCESS;
    }
}
