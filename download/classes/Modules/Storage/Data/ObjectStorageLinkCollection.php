<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;

use Iterator;
use Countable;

final class ObjectStorageLinkCollection implements Iterator, Countable
{
    /** @var int $position */
    private $position = 0;

    /** @var ObjectStorageLink[] $objectStorageLinks */
    private $objectStorageLinks = [];

    /**
     * ObjectStorageLinkCollection constructor.
     *
     * @param ObjectStorageLink[] $objectStorageLinks
     */
    public function __construct(array $objectStorageLinks = [])
    {
        foreach ($objectStorageLinks as $objectStorageLink) {
            $this->add($objectStorageLink);
        }
    }

    /**
     * @param ObjectStorageLink $objectStorageLink
     */
    public function add(ObjectStorageLink $objectStorageLink): void
    {
        $this->objectStorageLinks[] = ObjectStorageLink::fromDbState($objectStorageLink->toArray());
    }

    /**
     * @return ObjectStorageLink[]
     */
    public function getObjectStorageLinks(): array
    {
        $objectStorageLinks = [];

        foreach ($this->objectStorageLinks as $objectStorageLink) {
            $objectStorageLinks[] = ObjectStorageLink::fromDbState($objectStorageLink->toArray());
        }

        return $objectStorageLinks;
    }

    /**
     * @return array
     */
    public function getStorageLocationsIds(): array
    {
        $storageLocationIds = [];
        foreach ($this->objectStorageLinks as $objectStorageLink) {
            $storageLocationIds[] = $objectStorageLink->getStorageLocationId();
        }

        return array_unique($storageLocationIds);
    }

    /**
     * @return array
     */
    public function getArticleIds(): array
    {
        $articleIds = [];
        foreach ($this->objectStorageLinks as $objectStorageLink) {
            $articleIds[] = $objectStorageLink->getArticleId();
        }

        return array_unique($articleIds);
    }

    /**
     * @return ObjectStorageLink
     */
    public function current(): ObjectStorageLink
    {
        return ObjectStorageLink::fromDbState($this->objectStorageLinks[$this->position]->toArray());
    }

    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->objectStorageLinks[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->objectStorageLinks);
    }
}
