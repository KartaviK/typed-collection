# Generic collection

[![Latest Stable Version](https://poser.pugx.org/kartavik/generic-collection-php/v/stable)](https://packagist.org/packages/kartavik/generic-collection-php)
[![Total Downloads](https://poser.pugx.org/kartavik/generic-collection-php/downloads)](https://packagist.org/packages/kartavik/generic-collection-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KartaviK/generic-collection-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KartaviK/generic-collection-php/?branch=master)
[![Build Status](https://travis-ci.org/KartaviK/generic-collection-php.svg?branch=master)](https://travis-ci.org/KartaviK/generic-collection-php)
[![codecov](https://codecov.io/gh/KartaviK/generic-collection-php/branch/master/graph/badge.svg)](https://codecov.io/gh/KartaviK/generic-collection-php)
[![License](https://poser.pugx.org/kartavik/generic-collection-php/license)](https://github.com/KartaviK/generic-collection-php/blob/master/LICENSE)

Strongly typed generic collection implementation

## Installation

Use [composer](https://getcomposer.org/) to install:

```bash
composer require kartavik/generic-collection-php
```

## Usage

For all examples we will use this mock:

```php
<?php

class User
{
    /** @var string */
    protected $name;
    
    /** @var int */
    protected $age;
    
    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getAge(): int
    {
        return $this->age;
    }
}

```

### Constructor

#### Instantiate empty collection:
```php
<?php

use kartavik\Collections\Collection;

$collection = new Collection(User::class);

print_r($collection);
```
Output:
```text
kartavik\Collections\Collection Object
(
    [type:kartavik\Collections\Collection:private] => kartavik\Collections\Tests\Mocks\Element
    [container:protected] => Array
    (
    )
)
```

#### Instantiate with array:
```php
<?php

use kartavik\Collections\Collection;

$users = [
    new User('Roman', 17),
    new User('Dima', 22),
];

$collection = new Collection(User::class, $users);
```

#### Instantiate with another collection:
```php
<?php

use kartavik\Collections\Collection;

$users = [
    new User('Roman', 17),
    new User('Dima', 22),
];

$subCollection = new Collection(User::class, $users);
$collection = new Collection(User::class, $subCollection);
```

#### Instantiate with unbounded arrays:
```php
<?php

use kartavik\Collections\Collection;

$users[0] = [new User('Roman', 17), new User('Denis', 43),];
$users[1] = [new User('Vadim', 24),];
$users[2] = [new User('Jho', 12), new User('Anna', 33), new User('Cabo', 25),];
$users[3] = [new User('Ruby', 11),];
$users[4] = [new User('Venom', 45), new User('Dima', 64), new User('Many', 52)];

$collection = new Collection(
    User::class,
    $users[0],
    $users[1],
    $users[2],
    $users[3],
    $users[4]
);
```

#### Instantiate with unbounded collections:
```php
<?php

use kartavik\Collections\Collection;

$collection[0] = new Collection(User::class, [new User('Roman', 17), new User('Denis', 43),]);
$collection[1] = new Collection(User::class, [new User('Vadim', 24),]);
$collection[2] = new Collection(User::class, [new User('Jho', 12), new User('Anna', 33), new User('Cabo', 25),]);
$collection[3] = new Collection(User::class, [new User('Ruby', 11),]);
$collection[4] = new Collection(User::class, [new User('Venom', 45), new User('Dima', 64), new User('Many', 52)]);

$collection = new Collection(
    User::class, 
    $collection[0], 
    $collection[1], 
    $collection[2],
    $collection[3], 
    $collection[4]
);
```

#### Instantiate with combined iterable objects
```php
<?php

use kartavik\Collections\Collection;

$array = [new User('Roman', 17), new User('Denis', 43),];
$collection = $collection[1] = new Collection(User::class, [new User('Vadim', 24),]);
$iterable = 'any iterable object';

$collection = new Collection(User::class, $array, $collection, $iterable);
```

### Use extendibility

**UserCollection.php**:
```php
<?php

use kartavik\Collections\Collection;

class UserCollection extends Collection
{
    // your properties, constants, traits
    
    public function __construct(array ...$iterable)
    {
        parent::__construct(User::class, ...$iterable);
    }
    
    // your methods
}
```

And just create instance:

```php
<?php

use UserCollection;

$users = [
    new User('Roman', 17),
    new User('Denis', 43),
];

$collection = new UserCollection($users);
```

### Use static (beta)

You can create instance without class that implements your collection.

This implementation of static call method works with constructor
```php
<?php

use kartavik\Collections\Collection;

$users = [
    new User('Roman', 17),
    new User('Denis', 43),
];

$collection = Collection::{User::class}($users);
```

## Where is strong type?

I put simple logic to set methods taht will check element on `instanceof` type,
that cached in collection.

If you will try in any moment put to the collection element that is not instance of collection type
you will get `InvalidElementException()`

**Important!** this collection is only for `class` objects

## Methods

### map

signature:
`Collection map(callable $function, Collection ...$collection)`

### chunk

signature: 
`Collection chunk(int $size)`

## Contributors:
- [Roman <KartaviK> Varkuta](mailto:roman.varkuta@gmail.com)

## License

[MIT](./LICENSE)
