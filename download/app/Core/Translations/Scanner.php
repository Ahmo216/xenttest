<?php

namespace App\Core\Translations;

use Illuminate\Filesystem\Filesystem;

class Scanner
{
    private $disk;

    private $scanPaths;

    private $translationMethods;

    public function __construct(Filesystem $disk, $scanPaths, $translationMethods)
    {
        $this->disk = $disk;
        $this->scanPaths = $scanPaths;
        $this->translationMethods = $translationMethods;
    }

    /**
     * Scan all the files in the provided $scanPath for translations.
     */
    public function findTranslationKeys(): array
    {
        $results = [];

        // Stolen from https://github.com/joedixon/laravel-translation/blob/master/src/Scanner.php
        $matchingPattern =
            '[^\w]' . // Must not start with any alphanum or _
            '(?<!->)' . // Must not start with ->
            '(' . implode('|', $this->translationMethods) . ')' . // Must start with one of the functions
            "\(" . // Match opening parentheses
            "[\'\"]" . // Match " or '
            '(' . // Start a new group to match:
            '.+' . // Must start with group
            ')' . // Close group
            "[\'\"]" . // Closing quote
            "[\),]";  // Close parentheses or new parameter

        foreach ($this->disk->allFiles($this->scanPaths) as $file) {
            if (preg_match_all("/${matchingPattern}/siU", $file->getContents(), $matches)) {
                foreach ($matches[2] as $key) {
                    $results[] = $key;
                }
            }
        }

        return $results;
    }
}
