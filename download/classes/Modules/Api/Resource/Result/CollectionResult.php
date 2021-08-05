<?php

namespace Xentral\Modules\Api\Resource\Result;

/**
 * Class CollectionResult.
 *
 * The problem with this class is that it does not support returning an empty collection.
 * Because of this, the only way for the system to communicate that there weren't any
 * results is to throw an exception, which is then returned by the API as "404 - Not found"
 * result. This of course is wrong since the API endpoint does indeed exist, it just didn't
 * have any data to return. This is why all new API endpoints should use the ListResult
 * class instead.
 *
 * Old API endpoints should still keep using this to assure backwards compatibility.
 *
 * @deprecated For all completely new API endpoints, use the ListResult instead.
 */
class CollectionResult extends AbstractResult
{
    /**
     * @param array      $collection
     * @param array|null $pagination
     */
    public function __construct(array $collection, ?array $pagination = null)
    {
        if (empty($pagination)) {
            //throw new \CountryInvalidArgumentException('CollectionResult must contain pagination'); // @todo für GetIDs
        }

        if (empty($collection)) {
            throw new \InvalidArgumentException('CollectionResult can not be empty');
        }
        $firstKey = key($collection);
        if (!is_numeric($firstKey)) {
            throw new \InvalidArgumentException('CollectionResult can only store an index based array');
        }
        if (!is_array($collection[$firstKey]) || empty($collection[$firstKey])) {
            throw new \RuntimeException('CollectionResult must contain at least one result');
        }

        // @todo Sicherstellen dass Paginierung passt

        $this->type = self::RESULT_TYPE_COLLECTION;
        $this->data = $collection;
        $this->pagination = $pagination;
    }
}
