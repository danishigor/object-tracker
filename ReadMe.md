Object tracker
=

This class is a decorator that measures the runtime and used memory of dynamic methods.

Example:
```php
<?php

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

$tracker = new \DanishIgor\ObjectTracker\TrackerDecorator(new TestClass());

$tracker->methodWithParams(
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
$tracker->methodWithoutParams();

print_r($tracker->getTrackerStatistics());
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
