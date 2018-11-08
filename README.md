# Generic collection (Under development)

[![Latest Stable Version](https://poser.pugx.org/kartavik/typed-collection/v/stable?format=flat-square)](https://packagist.org/packages/kartavik/typed-collection)
[![Latest Unstable Version](https://poser.pugx.org/kartavik/typed-collection/v/unstable?format=flat-square)](https://packagist.org/packages/kartavik/typed-collection)
[![Total Downloads](https://poser.pugx.org/kartavik/typed-collection/downloads?format=flat-square)](https://packagist.org/packages/kartavik/typed-collection)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KartaviK/typed-collection/badges/quality-score.png?b=master&format=flat-square)](https://scrutinizer-ci.com/g/KartaviK/typed-collection/?branch=master)
[![Build Status](https://travis-ci.org/KartaviK/typed-collection.svg?branch=master&format=flat-square)](https://travis-ci.org/KartaviK/generic-collection-php)
[![codecov](https://codecov.io/gh/KartaviK/typed-collection/branch/master/graph/badge.svg?format=flat-square)](https://codecov.io/gh/KartaviK/typed-collection)
[![License](https://poser.pugx.org/kartavik/generic-collection-php/license?format=flat-square)](https://github.com/KartaviK/generic-collection-php/blob/master/LICENSE)

Strongly typed generic collection implementation

## Installation

Use [composer](https://getcomposer.org/) to install:

```bash
composer require kartavik/typed-collection
```

## Usage

### Dynamic

- internal types

```php
<?php

use kartavik\Support\Collection;
use kartavik\Support\Strict;

$items = [1, 2, 3, 4,];
$collection = new Collection(Strict::integer(), $items); // Return instance of typed collection
$collection = Collection::{'integer'}($items); // Work same as constructor

// Another examples

// string
Collection::{Strict::STRING}(['str1', 'str2']);

// float
Collection::{Strict::FLOAT}([12.3, 23.5, 3., 54.321,]);

// array
Collection::{Strict::ARRAYABLE}([[1, 2], ['str1'], [123.456]]);

// boolean
Collection::{Strict::BOOLEAN}([true, false]);

// object
Collection::{Strict::OBJECT}([new stdClass(), new Exception()]);
```

- User types

```php
<?php

use kartavik\Support\Collection;
use kartavik\Support\Strict;

// You can put name of class to static call
// In this case collection can take only stdClass
// It will work with any declared classes
$collection = Collection::{stdClass::class}([]);

// you can also do it with constructor
$collection = new Collection(Strict::object(stdClass::class), []);

// Strict class also support static call for class name
$strict = Strict::{stdClass::class}();
$collection = new Collection($strict, []);
```

### Extend

**StringCollection.php**:
```php
<?php

use kartavik\Support;

class StringCollection extends Support\Collection
{
    public function __construct(array $items)
    {
        // do something
        
        parent::__construct(Support\Strict::string(), $items);
    }
}
```

## Where is strong type?

Class [Strict](./src/Strict.php) help collection to validate type of elements;

If you will try in any moment put to the collection element that is not of element type
you will get [Exception\Validation](./src/Exception/Validation.php)

If you will try set some specific type you will catch [Exception\UnprocessedType](./src/Exception/UnprocessedType.php)

## Authors:
- [Roman <KartaviK> Varkuta](mailto:roman.varkuta@gmail.com)

## License
[MIT](./LICENSE)
