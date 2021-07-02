<?php

declare(strict_types=1);

namespace Xentral\Modules\TransferModule\Data;

use ArrayIterator;
use Countable;
use DateTimeInterface;
use IteratorAggregate;

final class ApiRequestCollection implements countable, IteratorAggregate
{
    /** @var ApiRequest[] $apiRequests */
    private $apiRequests = [];

    /**
     * ApiRequestCollection constructor.
     *
     * @param ApiRequest[] $apiRequests
     */
    public function __construct(array $apiRequests = [])
    {
        foreach ($apiRequests as $apiRequest) {
            $this->add($apiRequest);
        }
    }

    /**
     * @param ApiRequest $apiRequest
     */
    public function add(ApiRequest $apiRequest): void
    {
        $this->apiRequests[] = ApiRequest::fromDbState($apiRequest->toArray());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->apiRequests);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->apiRequests);
    }

    /**
     * @param array $dbState
     *
     * @return static
     */
    public static function fromDbState(array $dbState): self
    {
        $instance = new self();
        foreach($dbState as $apiRequestArray)
        {
            $instance->add(ApiRequest::fromDbState($apiRequestArray));
        }

        return $instance;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $dbState = [];
        foreach ($this as $apiRequest) {
            $dbState[] = $apiRequest->toArray();
        }

        return $dbState;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function filterByStatus(string $status): self
    {
        $instance = new self();
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            if($apiRequest->getStatus() === $status) {
                $instance->add($apiRequest);
            }
        }
        return $instance;
    }

    /**
     * @param array $apiAccountIds
     *
     * @return $this
     */
    public function filterByIdList(array $apiAccountIds): self
    {
        $instance = new self();
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            if(in_array($apiRequest->getId(), $apiAccountIds, false)) {
                $instance->add($apiRequest);
            }
        }
        return $instance;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function filterByType(string $type): self
    {
        $instance = new self();
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            if($apiRequest->getType() === $type) {
                $instance->add($apiRequest);
            }
        }
        return $instance;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $instance = new self();
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            $apiRequest->setType($type);
            $instance->add($apiRequest);
        }
        return $instance;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $instance = new self();
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            $apiRequest->setStatus($status);
            $instance->add($apiRequest);
        }
        return $instance;
    }

    /**
     * @param bool $isTransferred
     *
     * @return $this
     */
    public function setTransferred(bool $isTransferred): self
    {
        $instance = new self();
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            $apiRequest->setIsTransferred($isTransferred);
            $instance->add($apiRequest);
        }
        return $instance;
    }

    /**
     * @param DateTimeInterface|null $tranferedOn
     *
     * @return $this
     */
    public function setTransferdOn(?DateTimeInterface $tranferedOn): self
    {
        $instance = new self();
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            $apiRequest->setTransferredOn($tranferedOn);
            $instance->add($apiRequest);
        }
        return $instance;
    }

    /**
     * @return $this
     */
    public function incrementTransferCount(): self
    {
        $instance = new self();
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            $apiRequest->setTransferCount($apiRequest->getTransferCount() + 1);
            $instance->add($apiRequest);
        }

        return $instance;
    }

    /**
     * @return array
     */
    public function getApiRequestIds(): array
    {
        $ids = [];
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            if($apiRequest->getId() === null) {
                continue;
            }
            $ids[] = $apiRequest->getId();
        }

        return array_unique($ids);
    }

    /**
     * @return array
     */
    public function getApiRequestTypes(): array
    {
        $types = [];
        /** @var ApiRequest $apiRequest */
        foreach($this as $apiRequest) {
            if(!in_array($apiRequest->getType(), $types)) {
                $types[] = $apiRequest->getType();
            }
        }

        return array_unique($types);
    }
}
