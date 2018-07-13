<?php

namespace kartavik\Designer;

/**
 * Interface CollectionInterface
 * @package kartavik\Designer
 */
interface CollectionInterface
{
    public function instanceOfType($object): void;

    public function type(): string;
}
