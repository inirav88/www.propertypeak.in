<?php

namespace App\Modules\RealEstate\Console\Commands;

use App\Modules\RealEstate\Models\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GeneratePropertySlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:generate-slugs 
                            {--dry-run : Show what would be updated without making changes}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for all properties that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('Running in DRY-RUN mode. No changes will be made.');
        }

        // Get all properties without slugs
        $properties = Property::withoutGlobalScope('approved')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        $count = $properties->count();

        if ($count === 0) {
            $this->info('No properties found without slugs. All properties already have slugs!');
            return self::SUCCESS;
        }

        $this->info("Found {$count} properties without slugs.");

        $force = $this->option('force');
        
        if (!$dryRun && !$force && !$this->confirm('Do you want to generate slugs for these properties?', true)) {
            $this->info('Operation cancelled.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $updated = 0;
        $errors = 0;

        foreach ($properties as $property) {
            try {
                // Generate slug from property name
                $baseSlug = Str::slug($property->name ?? $property->title ?? 'property-' . $property->id);
                $slug = $baseSlug;
                $counter = 1;

                // Ensure unique slug
                while (Property::withoutGlobalScope('approved')->where('slug', $slug)->where('id', '!=', $property->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                if (!$dryRun) {
                    $property->update(['slug' => $slug]);
                }

                $updated++;
                $bar->advance();
            } catch (\Exception $e) {
                $errors++;
                $this->error("\nError processing property ID {$property->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info("[DRY-RUN] Would have updated {$updated} properties.");
        } else {
            $this->info("Successfully updated {$updated} properties with slugs.");
        }

        if ($errors > 0) {
            $this->error("{$errors} errors occurred.");
        }

        return self::SUCCESS;
    }
}
