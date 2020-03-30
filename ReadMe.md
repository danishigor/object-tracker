Object tracker
=

This class is a decorator that measures the runtime and used memory of dynamic methods.

Example:
```php
<?php

require '../vendor/autoload.php';

class TestClass
{
    private $storage;

    public function methodWithParams($bool, $integer, $float, $string, $array, $object, $resource, $callback)
    {
        sleep(2);

        for ($i = 0; $i < 100000; $i++) {
            $this->storage[] = new \stdClass();
        }
    }

    public function methodWithoutParams()
    {
        for ($i = 0; $i < 10000; $i++) {
            $this->storage[] = new \stdClass();
        }
    }
}
