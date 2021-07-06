<?php

namespace App\Core\Translations\Commands;

use App\Core\Translations\Scanner;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class DumpTranslationFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command scans the codebase for missing translations';

    public function handle(Filesystem $filesystem): int
    {
        $translationFiles = collect($filesystem->allFiles(resource_path('lang')))
            ->mapWithKeys(function (SplFileInfo $fileInfo) use ($filesystem) {
                return [$fileInfo->getPathname() => json_decode($filesystem->get($fileInfo->getPathname()), true)];
            });

        $scanner = new Scanner(
            $filesystem,
            [app_path(), resource_path(), base_path('classes'), base_path('www')],
            ['trans', '__', 'trans_choice']
        );
        $foundTranslationKeys = $scanner->findTranslationKeys();

        $this->info('Found ' . count($foundTranslationKeys) . ' translation keys.');

        foreach ($translationFiles as $path => $translations) {
            $this->info('Before: Language file ' . $path . ' has now ' . count($translations) . ' translation keys');

            foreach ($foundTranslationKeys as $key) {
                if (isset($translations[$key])) {
                    continue;
                }
                $translations[$key] = '';
            }
            $this->info('After: Language file ' . $path . ' has now ' . count($translations) . ' translation keys');
            ksort($translations);
            $filesystem->put($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");
        }

        return 0;
    }
}
