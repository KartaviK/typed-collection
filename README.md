# generic-collection

[![Latest Stable Version](https://poser.pugx.org/kartavik/generic-collection-php/v/stable)](https://packagist.org/packages/kartavik/generic-collection-php)
[![Total Downloads](https://poser.pugx.org/kartavik/generic-collection-php/downloads)](https://packagist.org/packages/kartavik/generic-collection-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KartaviK/generic-collection-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KartaviK/generic-collection-php/?branch=master)
[![Build Status](https://travis-ci.org/KartaviK/generic-collection-php.svg?branch=master)](https://travis-ci.org/KartaviK/generic-collection-php)
[![codecov](https://codecov.io/gh/KartaviK/generic-collection-php/branch/master/graph/badge.svg)](https://codecov.io/gh/KartaviK/generic-collection-php)
[![License](https://poser.pugx.org/kartavik/generic-collection-php/license)](https://github.com/KartaviK/generic-collection-php/blob/master/LICENSE)

Implementation of generic collection

## Usage

Base element:

```php
<?php

namespace Root\Data;

class Product
{
    public $name;
}
```

### Extends
```php
<?php

namespace Root\Collections;

use kartavik\Designer;
use Root\Data;

class ProductCollection extends Designer\Collection
{
    public function type(): string 
    {
         return Data\Product::class;
    }
}
```

### Anonymous

```php
<?php

use kartavik\Designer\Collection;

$element = new Product();

$collection = Collection::{Product::class}($element);
$collection[0]; // will out your new Product()
$collection->offsetGet(0); // same as $collection[0]

$collection = Collection::{Product::class}([$element, $element]);
$collection[] = $element; // will add element
$collection->append($element); // same as $collection[]

$collection[0] = new Product(); // will reset element
$collection->offsetSet(0, new Product()); // same as $collection[0] = new Product()

$collection->append('some not product'); // will throw exception, because given argument is not Product
```