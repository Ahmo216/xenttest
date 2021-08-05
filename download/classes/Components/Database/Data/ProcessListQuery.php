<?php

declare(strict_types=1);

namespace Xentral\Components\Database\Data;


final class ProcessListQuery
{
    /** @var int $id */
    private $id;

    /** @var string $user */
    private $user;

    /** @var string $host */
    private $host;

    /** @var string|null $db */
    private $db;

    /** @var string $command */
    private $command;

    /** @var int $time */
    private $time;

    /** @var string $state */
    private $state;

    /** @var string|null $info */
    private $info;

    /**
     * ProcessListQuery constructor.
     *
     * @param int         $id
     * @param string      $user
     * @param string      $host
     * @param string|null $db
     * @param string      $command
     * @param int         $time
     * @param string      $state
     * @param string|null $info
     */
    public function __construct(
        int $id,
        string $user,
        string $host,
        ?string $db,
        string $command,
        int $time,
        string $state,
        ?string $info
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->host = $host;
        $this->db = $db;
        $this->command = $command;
        $this->time = $time;
        $this->state = $state;
        $this->info = $info;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string|null
     */
    public function getDb(): ?string
    {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string|null
     */
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Id'      => $this->getId(),
            'User'    => $this->getUser(),
            'Host'    => $this->getHost(),
            'db'      => $this->getDb(),
            'Command' => $this->getCommand(),
            'Time'    => $this->getTime(),
            'State'   => $this->getState(),
            'Info'    => $this->getInfo(),
        ];
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @param array $dbState
     *
     * @return static
     */
    public static function fromDbState(array $dbState): self
    {
        return new self(
            (int)$dbState['Id'],
            $dbState['User'],
            $dbState['Host'],
            $dbState['db'],
            $dbState['Command'],
            (int)$dbState['Time'],
            $dbState['State'],
            $dbState['Info']
        );
    }
}
