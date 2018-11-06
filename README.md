# Generic collection (Under development)

[![Latest Stable Version](https://poser.pugx.org/kartavik/generic-collection-php/v/stable)](https://packagist.org/packages/kartavik/generic-collection-php)
[![Total Downloads](https://poser.pugx.org/kartavik/generic-collection-php/downloads)](https://packagist.org/packages/kartavik/generic-collection-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KartaviK/generic-collection-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KartaviK/generic-collection-php/?branch=master)
[![Build Status](https://travis-ci.org/KartaviK/generic-collection-php.svg?branch=master)](https://travis-ci.org/KartaviK/generic-collection-php)
[![codecov](https://codecov.io/gh/KartaviK/typed-collection/branch/master/graph/badge.svg)](https://codecov.io/gh/KartaviK/typed-collection)
[![License](https://poser.pugx.org/kartavik/generic-collection-php/license)](https://github.com/KartaviK/generic-collection-php/blob/master/LICENSE)

Strongly typed generic collection implementation

## Installation

Use [composer](https://getcomposer.org/) to install:

```bash
composer require kartavik/generic-collection-php
```

## Usage

Two variant to instantiate collection:

- Constructor:

```php
<?php

use kartavik\Collections\Collection;

/** @var iterable $iterable */
$collection = new Collection(stdClass::class, $iterable);

/** @var iterable[] $iterables */
$collection = new Collection(stdClass::class, ...$iterables);
```

- Static call:
```php
<?php

use kartavik\Collections\Collection;

/** @var iterable $iterable */
$collection = Collection::{stdClass::class}($iterable);

/** @var iterable[] $iterables */
$collection = Collection::{stdClass::class}(...$iterables);
```

### Use extendibility

**UserCollection.php**:
```php
<?php

use kartavik\Collections\Collection;

class StdClassCollection extends Collection
{
    // your properties, constants, traits
    
    public function __construct(iterable ...$iterable)
    {
        parent::__construct(stdClass::class, ...$iterable);
    }
    
    // your methods
}
```

## Where is strong type?

I put simple logic to set methods that will check element on `instanceof` type,
that cached in collection.

If you will try in any moment put to the collection element that is not instance of collection type
you will get [Exception\InvalidElement](./src/Exception/InvalidElement.php)

If you will try set type that is not class than you will catch [Exception\UnprocessedType](./src/Exception/UnprocessedType.php)

**Important!** this collection is only for `class` objects

## Supported interfaces:

- \ArrayAccess
- \Countable
- \IteratorAggregate
- \JsonSerializable
- \Serializable

## Helpful methods

### chunk

`Collection chunk(int $size)`

```php
<?php

use kartavik\Collections\Collection;

$collection = new Collection(stdClass::class, [new stdClass(), new stdClass()]);
$collection->chunk(1);
```

## Authors:
- [Roman <KartaviK> Varkuta](mailto:roman.varkuta@gmail.com)

## License
[MIT](./LICENSE)
