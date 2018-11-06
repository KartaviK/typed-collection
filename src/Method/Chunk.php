<?php

namespace kartavik\Support\Method;

use kartavik\Support\Collection;

/**
 * Trait Chunk
 * @package kartavik\Support\Method
 */
trait Chunk
{
    public function chunk(int $size): Collection
    {
        /** @var Collection $collection */
        $collection = new static(Collection::class);
        $chunked = array_chunk($this->container, $size);

        foreach ($chunked as $chunk) {
            $collection->append(new Collection($this->type(), $chunk));
        }

        return $collection;
    }
}
