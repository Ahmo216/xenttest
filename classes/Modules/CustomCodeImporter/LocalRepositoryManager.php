<?php

namespace Xentral\Modules\CustomCodeImporter;

use DirectoryIterator;
use FilesystemIterator;
use GitWrapper\GitWrapper;

class LocalRepositoryManager
{
    /** @var GitWrapper */
    private $gitWrapper;

    /** @var string */
    private $repositoryPath;

    /**
     * LocalRepositoryManager constructor.
     *
     * @param GitWrapper $gitWrapper
     * @param string     $repositoryPath
     */
    public function __construct(GitWrapper $gitWrapper, string $repositoryPath)
    {
        $this->gitWrapper = $gitWrapper;
        $this->repositoryPath = $repositoryPath;
    }

    /**
     * Get list of all files in the cloned git repository.
     *
     * @return string[]
     */
    public function getFilesInClonedRepository(): array
    {
        if (!$this->isCloned()) {
            return [];
        }

        $gitWrapper = $this->gitWrapper->workingCopy($this->repositoryPath);

        $files = explode("\n", $gitWrapper->run('ls-files'));

        // Remove empty value (not sure where it comes from).
        return array_diff($files, ['']);
    }

    /**
     * Check whether the git repo target directory exists and contains something.
     *
     * @return bool
     */
    public function isCloned(): bool
    {
        if (!file_exists($this->repositoryPath)) {
            // Most likely nothing has yet been cloned or the whole
            // custom_code/ directory has been deleted manually.

            return false;
        }

        $iterator = new FilesystemIterator($this->repositoryPath);

        return $iterator->valid();
    }
}
