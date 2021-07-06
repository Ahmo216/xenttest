<?php

declare(strict_types=1);

namespace Xentral\Modules\CustomCodeImporter;

use GitWrapper\GitWrapper;

class ImporterFactory
{
    /** @var Validator */
    private $validator;

    /** @var string */
    private $repositoryPath;

    /** @var string */
    private $projectRoot;

    /**
     * ImporterFactory constructor.
     *
     * @param Validator $validator
     * @param string    $projectRoot
     * @param string    $repositoryPath
     */
    public function __construct(
        Validator $validator,
        string $repositoryPath,
        string $projectRoot
    ) {
        $this->validator = $validator;
        $this->repositoryPath = $repositoryPath;
        $this->projectRoot = $projectRoot;
    }

    /**
     * Create a new git repository importer from the repository URL.
     *
     * For private repositories a private key can be entered as the second parameter.
     *
     * @param string      $url
     * @param string|null $privateKeyPath
     *
     * @return Importer
     */
    public function createFromRepositoryUrl(string $url, ?string $privateKeyPath = null): Importer
    {
        $gitWrapper = new GitWrapper('/usr/bin/git');

        if ($privateKeyPath) {
            $gitWrapper->setPrivateKey($privateKeyPath);
        }

        $workingCopy = $gitWrapper->workingCopy($this->repositoryPath);

        return new Importer(
            $workingCopy,
            $this->validator,
            $url,
            $this->projectRoot
        );
    }
}
