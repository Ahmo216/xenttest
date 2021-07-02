<?php

namespace Xentral\Modules\Api\Resource\Result;


/**
 * Represents a collection of items to return from the API.
 *
 * In case of an empty collection, this class will cause the API to return:
 *
 *     {"data":[]}
 *
 */
class ListResult extends AbstractResult
{
    /**
     * ListResult constructor.
     *
     * @param array $collection
     * @param array|null $pagination
     */
    public function __construct(array $collection, array $pagination = null)
    {
        $this->type = self::RESULT_TYPE_COLLECTION;
        $this->data = $collection;
        $this->pagination = $pagination;
    }
}
