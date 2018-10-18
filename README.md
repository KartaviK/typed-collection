# Generic collection (Under development)

[![Latest Stable Version](https://poser.pugx.org/kartavik/generic-collection-php/v/stable)](https://packagist.org/packages/kartavik/generic-collection-php)
[![Total Downloads](https://poser.pugx.org/kartavik/generic-collection-php/downloads)](https://packagist.org/packages/kartavik/generic-collection-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KartaviK/generic-collection-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KartaviK/generic-collection-php/?branch=master)
[![Build Status](https://travis-ci.org/KartaviK/generic-collection-php.svg?branch=master)](https://travis-ci.org/KartaviK/generic-collection-php)
[![codecov](https://codecov.io/gh/KartaviK/generic-collection-php/branch/master/graph/badge.svg)](https://codecov.io/gh/KartaviK/generic-collection-php)
[![License](https://poser.pugx.org/kartavik/generic-collection-php/license)](https://github.com/KartaviK/generic-collection-php/blob/master/LICENSE)

I tried to implement a strongly typed generic collection as similar to List<T> in C#

`new List<Product>()` will similar to `Collection::{Product::class}()`

## Usage

For examples will use next classes:

**Product.php**
```php
<?php

class Product
{
    /** @var Name */
    protected $name;
    
    /** @var Barcode */
    protected $barcode;
    
    public function __construct(Name $name, Barcode $barcode) 
    {
        $this->name = $name;
        $this->barcode = $barcode;
    }
    
    public function getName(): Name
    {
        return $this->name;
    }
    
    public function getBarcode(): Barcode
    {
        return $this->barcode;
    }
}
```

**Name.php**:
```php
<?php

class Name
{
    /** @var string */
    protected $value;
    
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    
    public function getValue(): string
    {
        return $this->value;
    }
    
    public function __toString(): string
    {
        return $this->value;
    }
}
```

**Barcode.php**:
```php
<?php

class Barcode
{
    /** @var int */
    protected $value;
    
    public function __construct(int $value) 
    {
        $this->value = $value;
    }
    
    public function getValue(): int
    {
        return $this->value;
    }
    
    public function __toString(): string
    {
        return (string)$this->value;
    }
}
```

Now you can create own php file with class
that will implement collection with extending our abstract collection;

OR

You can create collection anonymously without implementing file;

### Use extendibility

**ProductCollection.php**:
```php
<?php

use kartavik\Designer\Collection;

class ProductCollection extends Collection
{
    // auto-generated constructor
    public function __construct(
        array $elements = [],
        int $flags = 0,
        string $iteratorClass = \ArrayIterator::class
    ) {
        parent::__construct($elements, $this->type(), $flags, $iteratorClass);
    }
    
    public function type(): string 
    {
         return Product::class;
    }
}
```

And than we can create instance:

```php
<?php

$products = [
    new Product(new Name('Apple'), new Barcode(123123)),
    new Product(new Name('Orange'), new Barcode(321321)),
];

$collection = new ProductCollection($products);
```

### Use anonymously

You can create instance without class that implements your collection

```php
<?php

use kartavik\Designer\Collection;

$barcodes = [
    new Barcode(123132),
    new Barcode(321321),
];

$collection = Collection::{Barcode::class}($barcodes);
```

## Where is strong type?

I put simple logic to set methods taht will check element on `instanceof` type,
that cached in collection.

If you will try in any moment put to the collection element that is not instance of collection type
you will get `InvalidElementException()`

**Important!** this collection is only for `class` objects

## Functions

#### **map()**

signature:
`map(callable $function, Collection ...$collection): Collection`

usage example:
```php
<?php

$products = [
    new Product(new Name('Apple'), new Barcode(123123)),
    new Product(new Name('Orange'), new Barcode(321321)),
];

$collection = Collection::{Product::class}($products);

$names = $collection->map(function (Product $product): Name {
    return $product->getName();
});

/*
 *   out is something like:
 * 
 *   class@anonymous {
 *       [0] => Name {},
 *       [1] => Name {}
 *   }
 *   
 *   this collection also instance of Collection
 */
```

#### **chunk()**

signature: 
`chunk(int $size): Collection`

usage example:
```php
<?php

$products = [
    new Product(new Name('Apple'), new Barcode(1)),
    new Product(new Name('Orange'), new Barcode(2)),
    new Product(new Name('Car'), new Barcode(3)),
    new Product(new Name('Pan'), new Barcode(4)),
];

$collection = Collection::{Product::class}($products);

$chunked = $collection->chunk(2);

/*
 * out will be:
 * 
 * class@anonymous {
 *      [0] => class@anonymous {
 *          [0] => Product { protected $name = Name { protected $value = 'Apple' } ... },
 *          [1] => Product { ... }
 *      },
 *      [1] => class@anonymous {
 *          [0] => Product { ...},
 *          [1] => Product { protected $name = Name { protected $value = 'Pan' } }
 *      }
 * }
 * 
 * Also each anonymous class is instance of Collection
 */
```
