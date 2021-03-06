Object tracker
===

This class is a decorator that measures the runtime and used memory of dynamic methods.

Install
---

The preferred way to install this extension is composer.
You can download composer from the official website: "http://getcomposer.org/download/".

To connect the library to the project, use:
> composer require danishigor/object-tracker

or add this line to the "require" section of your composer.json:
> "danishigor/object-tracker": "@stable"

Example:
---
```php
<?php

use DanishIgor\ObjectTracker\TrackerDecorator;

class TestClass
{
    private $storage;

    public function methodWithParams($bool, $integer, $float, $string, $array, $object, $resource, $callback)
    {
        for ($i = 0; $i < 400000; $i++) {
            $this->storage[] = new \stdClass();
        }
    }

    public function methodWithoutParams()
    {
        for ($i = 0; $i < 200000; $i++) {
            $this->storage[] = new \stdClass();
        }
    }
}

/**
 * @var TestClass|TrackerDecorator $decoratedTestClass Decorated object.
 */
$decoratedTestClass = new TrackerDecorator(new TestClass());

$decoratedTestClass->methodWithParams(
    true,
    234,
    100.23,
    "MyString param!",
    [1, 23, "33", new \stdClass()],
    new \stdClass(),
    tmpfile(),
    function () {
        return "test";
    }
);
$decoratedTestClass->methodWithoutParams();

print_r($decoratedTestClass->getTrackerStatistics());
```

Output:
---

```text
Array
(
    [0] => Array
        (
            [0] => MethodName
            [1] => Parameters(param1tame:param1type; ... paramXname:paramXtype)
            [2] => WorkTime(seconds)
            [3] => WorkMemory(bytes)
        )
    [1] => Array
        (
            [0] => methodWithParams
            [1] => boolean:1;integer:234;double:100.23;string:MyString param!;array:[1,23,"33",{}];object:stdClass;resource:stream;object:Closure
            [2] => 0.10632991790771
            [3] => 39060584

        )
    [2] => Array
        (
            [0] => methodWithoutParams
            [1] => [MISSING]
            [2] => 0.055673122406006
            [3] => 28971520
        )
)
```
