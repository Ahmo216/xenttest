<?php

declare(strict_types=1);

namespace Xentral\Modules\CustomCodeImporter;

use Exception;
use GitWrapper\GitWorkingCopy;

class Importer
{
    /** @var array $appliedFiles Array that keeps track of successfully copied custom files */
    private $appliedFiles;

    /** @var GitWorkingCopy $git */
    private $workingCopy;

    /** @var string */
    private $projectRoot;

    /** @var string */
    private $repositoryUrl;

    /** @var Validator */
    private $validator;

    /**
     * Importer constructor.
     *
     * @param GitWorkingCopy $workingCopy Represents the git repository on the local filesystem
     * @param Validator      $validator   Class that does quality checks on the custom code
     * @param string         $url         The URL of the git repository containing the custom code
     * @param string         $projectRoot The path of the system root
     */
    public function __construct(
        GitWorkingCopy $workingCopy,
        Validator $validator,
        string $url,
        string $projectRoot
    ) {
        $this->workingCopy = $workingCopy;
        $this->validator = $validator;
        $this->repositoryUrl = $url;
        $this->projectRoot = $projectRoot;
    }

    /**
     * Clone the repo into userdata/custom_code/ dir or pull in new changes if already cloned.
     *
     * @throws Exception
     */
    public function importCustomFiles()
    {
        // Do a completely new clone of a new repository.
        if (!$this->workingCopy->isCloned()) {
            $this->workingCopy->cloneRepository($this->repositoryUrl);
            $this->validateRemote();
            $this->workingCopy->setCloned(true);

            return;
        }

        // Pull latest remote changes to the existing local repository.
        if ($this->workingCopy->getRemoteUrl('origin') === $this->repositoryUrl) {
            $this->validateRemote();
            $this->workingCopy->pull('origin');

            return;
        }

        // The remote url that user entered is different from the one that was used
        // for creating the local repository so replace the remote.
        $this->workingCopy->removeRemote('origin');
        $this->workingCopy->addRemote('origin', $this->repositoryUrl);

        $this->validateRemote();

        $branches = $this->workingCopy->getBranches()->fetchBranches();
        $branch = in_array('remotes/origin/main', $branches) ? 'main' : 'master';

        // Do a hard reset to overwrite all local files from the old remote with
        // files from the new remote.
        $this->workingCopy->reset(['hard' => true], "remotes/origin/{$branch}");

        // Default remote branch is lost when doing a reset, so we need to define
        // it again or otherwise the next pull from the same remote will fail.
        $this->workingCopy->run('branch', [['set-upstream-to' => "origin/{$branch}"]]);
    }

    /**
     * Delete all known custom files from the project structure.
     *
     * If a file has been remove from the git repository, there is no easy way
     * of knowing whether the same file has already been removed from the project
     * structure. Therefore it is safer to just delete all known custom files
     * before pulling a new version from git.
     */
    public function removeCustomFiles()
    {
        $remover = new CodeRemover($this->projectRoot);
        $remover->removeCustomFiles($this->getFilesInClonedRepository());
    }

    /**
     * Check that the files in the git repo have correct names and target directories.
     *
     * @throws Exception\InvalidClassNameException
     * @throws Exception\InvalidFilenameException
     * @throws Exception\InvalidTargetDirectoryException
     */
    public function validateCustomFiles()
    {
        $files = $this->getFilesInClonedRepository();

        if (empty($files)) {
            // Remove the local working copy completely to force a full cloning on
            // the text attempt. Otherwise it will try to pull into the existing empty
            // repository without knowing what should be used as the remote HEAD.
            // Github for example is not using the default git ref "refs/heads/master"
            // but instead uses "refs/heads/main".
            unlink($this->workingCopy->getDirectory());

            throw new Exception('The git repository does not seem to contain any files.');
        }

        foreach ($files as $filename) {
            $filename = "{$this->workingCopy->getDirectory()}/{$filename}";
            $contents = file_get_contents($filename);
            $this->validator->validate($filename, $contents);
        }
    }

    /**
     * Copy the files from userdata/custom_code/<repository_name>/ into the project structure.
     *
     * @throws Exception
     */
    public function applyCustomFiles()
    {
        foreach ($this->getFilesInClonedRepository() as $file) {
            $source = "{$this->workingCopy->getDirectory()}/{$file}";
            $target = "{$this->projectRoot}/{$file}";

            $target_dir = pathinfo($target, PATHINFO_DIRNAME);

            if (!is_dir($target_dir)) {
                throw new Exception("The target directory {$target_dir} does not exist.");
            }

            if (!is_writable($target_dir)) {
                throw new Exception("The target directory {$target_dir} is not writable.");
            }

            $copied = copy($source, $target);
            if (!$copied) {
                throw new Exception("Failed to apply the file {$file}.");
            }

            $this->appliedFiles[] = $file;
        }
    }

    /**
     * Get a list of the files that were imported and applied to the system.
     *
     * @return array
     */
    public function getAppliedFiles(): array
    {
        return $this->appliedFiles;
    }

    /**
     * Get a list of all the custom files from the userdata/ directory.
     *
     * These are the files that have been cloned from git, but haven't yet
     * necessarily been copied into the project structure.
     *
     * @return array
     */
    private function getFilesInClonedRepository(): array
    {
        if (!$this->workingCopy->isCloned()) {
            return [];
        }

        $files = explode("\n", $this->workingCopy->run('ls-files'));

        // Remove empty value (not sure where it comes from).
        $files = array_diff($files, ['']);

        return $files;
    }

    /**
     * Check whether the remote actually contains something that can be cloned.
     *
     * @throws Exception
     */
    private function validateRemote()
    {
        $this->workingCopy->fetch('origin');
        $branches = $this->workingCopy->getBranches()->fetchBranches();

        if (in_array('remotes/origin/master', $branches)) {
            return;
        }

        // Github uses 'main' instead of 'master' as the main branch.
        if (in_array('remotes/origin/main', $branches)) {
            return;
        }

        throw new Exception('The repository does not appear to contain any code.');
    }
}
