<?php

namespace App\Console\Commands;

use App\Models\Tag;
use App\Services\TranslationService;
use Illuminate\Console\Command;

class PopulateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:translations {--count=100000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the translations table with test records';

    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        // Define a fixed set of possible tags.
        $possibleTags = ['mobile', 'desktop', 'web'];
        // Pre-create these tags so we don't have to query/create them repeatedly.
        $tags = [];
        foreach ($possibleTags as $tagName) {
            $tags[$tagName] = Tag::firstOrCreate(['name' => $tagName]);
        }

        $batchSize = 1000;
        for ($i = 0; $i < $count; $i += $batchSize) {
            $currentBatchSize = min($batchSize, $count - $i);
            $translations = $this->translationService->processTranslationBatch($currentBatchSize, $tags);

            // Advance progress bar for each translation in the batch.
            foreach ($translations as $translation) {
                $bar->advance();
            }
        }

        $bar->finish();
        $this->info("\nPopulated {$count} translations successfully with tag associations.");
    }
}
