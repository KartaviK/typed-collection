<?php

namespace kartavik\Support\Method;

/**
 * Trait Append
 * @package kartavik\Support\Method
 */
trait Append
{
    public function append(): void
    {
        $items = func_get_args();

        foreach ($items as $item) {
            $this->add($item);
        }
    }
}
