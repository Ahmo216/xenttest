<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

class InboxOverview
{
    /** @var string $folder */
    private $folder;

    /** @var InboxMessageOverviewCollection $overviews */
    private $overviews;

    /**
     * @param string                              $folder
     * @param InboxMessageOverviewCollection|null $overviews
     */
    public function __construct(string $folder, ?InboxMessageOverviewCollection $overviews = null)
    {
        $this->folder = $folder;
        $this->overviews = $overviews ?? new InboxMessageOverviewCollection();
    }

    /**
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * @return InboxMessageOverviewCollection
     */
    public function getOverviews(): InboxMessageOverviewCollection
    {
        return $this->overviews;
    }
}
