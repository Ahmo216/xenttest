<?php

namespace Xentral\Modules\CustomCodeImporter;

class CodeRemover
{
    /** @var string */
    private $projectRoot;

    /**
     * CodeRemover constructor.
     *
     * @param string $projectRoot
     */
    public function __construct(string $projectRoot)
    {
        $this->projectRoot = $projectRoot;
    }

    /**
     * Removes custom files imported using the CustomCodeImporter module.
     *
     * Either give a list of files, or allow the function to find all imported files.
     *
     * @param array $files
     *
     * @throws \Exception
     *
     * @return array
     */
    public function removeCustomFiles(array $files): array
    {
        $removed_files = [];
        foreach ($files as $file) {
            $filename = "{$this->projectRoot}/{$file}";

            if (!file_exists($filename)) {
                continue;
            }

            $removed = unlink($filename);

            if (!$removed) {
                throw new \Exception("Failed to remove the old custom file {$file}. Check " .
                    'file permissions or remove the file manually.');
            }

            $removed_files[] = $file;
        }

        return $removed_files;
    }
}
