<?php

declare(strict_types=1);

namespace Xentral\Components\Database;

use Exception;
use Xentral\Components\Database\Data\ProcessListQueryCollection;

final class ReadOnlyDbQueryKiller
{
    /** @var DatabaseProcessListInterface $databaseProcessListService */
    private $databaseProcessListService;

    /** @var DatabaseProcessKillInterface $databaseProcessKillService */
    private $databaseProcessKillService;

    /** @var bool $isReadOnlyDbActive */
    private $isReadOnlyDbActive;

    /** @var int $maxRunningTime */
    private $maxRunningTime;

    /** @var string $readonlyUsername */
    private $readonlyUsername;

    /** @var string $dbHost */
    private $dbHost;

    /** @var string $dbName */
    private $dbName;

    /**
     * ReadOnlyDbQueryKiller constructor.
     *
     * @param DatabaseProcessListInterface $databaseProcessListService
     * @param DatabaseProcessKillInterface $databaseProcessKillService
     * @param string                       $dbHost
     * @param string                       $dbName
     * @param string                       $dbUserName
     * @param string                       $readonlyUsername
     * @param int                          $maxRunningTime
     */
    public function __construct(
        DatabaseProcessListInterface $databaseProcessListService,
        DatabaseProcessKillInterface $databaseProcessKillService,
        string $dbHost,
        string $dbName,
        string $dbUserName,
        string $readonlyUsername,
        int $maxRunningTime
    ) {
        $this->databaseProcessListService = $databaseProcessListService;
        $this->databaseProcessKillService = $databaseProcessKillService;
        $this->isReadOnlyDbActive = $readonlyUsername !== ''
            && $dbUserName !== $readonlyUsername;
        $this->maxRunningTime = $maxRunningTime;
        $this->readonlyUsername = $readonlyUsername;
        $this->dbName = $dbName;
        $this->dbHost = $dbHost;
    }

    /**
     * @return bool
     */
    public function isReadonlyDbActive(): bool
    {
        return $this->isReadOnlyDbActive;
    }

    /**
     * @return ProcessListQueryCollection
     */
    public function tryKillLongRunningQueries(): ProcessListQueryCollection
    {
        $killedProcesses = new ProcessListQueryCollection();
        if (!$this->isReadOnlyDbActive || $this->maxRunningTime <= 0) {
            return $killedProcesses;
        }
        try {
            $processList = $this->databaseProcessListService->getProcessList();
            $processListQueryCollection = $this->getQueriesToKill(
                $processList,
                $this->dbHost,
                $this->dbName,
                $this->readonlyUsername,
                $this->maxRunningTime
            );
        } catch (Exception $e) {
            return $killedProcesses;
        }

        foreach ($processListQueryCollection as $process) {
            if ($this->tryKillProcess($process->getId())) {
                $killedProcesses->add($process);
            }
        }

        return $killedProcesses;
    }

    /**
     * @param ProcessListQueryCollection $processListQueryCollection
     * @param string                     $host
     * @param string                     $dbName
     * @param string                     $readOnlyUserName
     * @param int                        $maxRunningTime
     *
     * @return ProcessListQueryCollection
     */
    public function getQueriesToKill(
        ProcessListQueryCollection $processListQueryCollection,
        string $host,
        string $dbName,
        string $readOnlyUserName,
        int $maxRunningTime
    ): ProcessListQueryCollection {
        $filteredProcessListQueryCollection = new ProcessListQueryCollection();
        if (!$this->isReadOnlyDbActive || $this->maxRunningTime <= 0) {
            return $filteredProcessListQueryCollection;
        }
        foreach ($processListQueryCollection as $processListQuery) {
            if ($processListQuery->getHost() !== $host) {
                continue;
            }
            if ($processListQuery->getDb() !== $dbName) {
                continue;
            }
            if ($processListQuery->getUser() !== $readOnlyUserName) {
                continue;
            }
            if ($processListQuery->getState() === 'Sleep') {
                continue;
            }
            if ($processListQuery->getTime() < $maxRunningTime) {
                continue;
            }
            $filteredProcessListQueryCollection->add($processListQuery);
        }

        return $filteredProcessListQueryCollection;
    }

    /**
     * @param int $processId
     *
     * @return bool
     */
    public function tryKillProcess(int $processId): bool
    {
        try {
            $this->databaseProcessKillService->kill($processId);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
