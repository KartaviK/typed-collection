<?php

namespace kartavik\Support\Method;

use kartavik\Support;

/**
 * Trait Column
 * @package kartavik\Support\Method
 */
trait Column
{
    /**
     * Same as map method but only with current collection
     *
     * @param \Closure $callback
     *
     * @return Support\Collection
     * @throws Support\Exception\Validation
     */
    public function column(\Closure $callback): Support\Collection
    {
        $fetched = [];

        foreach ($this->container as $item) {
            $fetched[] = call_user_func($callback, $item);
        }

        return new Support\Collection(Support\Strict::typeof(current($fetched)), $fetched);
    }
}
