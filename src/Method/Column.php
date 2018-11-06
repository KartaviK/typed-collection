<?php

namespace kartavik\Support\Method;

use kartavik\Support\Collection;

/**
 * Trait Column
 * @package kartavik\Support\Method
 */
trait Column
{
    public function column(\Closure $callback): Collection
    {
        $type = get_class(call_user_func($callback, $this->current()));
        $collection = new Collection($type);
        $fetched = [];

        foreach ($this->container as $item) {
            $fetched[] = call_user_func($callback, $item);
        }

        $collection->append(...$fetched);

        return $collection;
    }
}
