<?php

namespace DanishIgor\ObjectTracker;

/**
 * Object tracker.
 *
 * @package DanishIgor\ObjectTracker
 */
class TrackerDecorator
{
    /**
     * @var object Tracked object.
     */
    private $object;

    /**
     * Tracked data.
     *
     * @var array $statistics Statistics of called methods.
     */
    private $statistics;

    /**
     * Constructor.
     *
     * @param  object  $object  Tracked object.
     */
    public function __construct($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Only objects are allowed for tracking.');
        }

        $this->object = $object;

        $this->statistics[] = [
            'MethodName',
            'Parameters(param1tame:param1type; ... paramXname:paramXtype)',
            'WorkTime(seconds)',
            'WorkMemory(bytes)',
        ];
    }

    /**
     * Calling methods of the tracked object.
     *
     * @param  string  $name       Name of the method of the object.
     * @param  array   $arguments  Arguments of the object method.
     */
    public function __call($name, $arguments)
    {
        if (!method_exists($this->object, $name)) {
            throw new \BadMethodCallException('Unknown method on tracked object.');
        }

        $record = [];

        // MethodName.
        array_push($record, $name);

        // Parameters.
        $params = '';
        for ($i = 0; $i < count($arguments); $i++) {
            $currentArgument = $arguments[$i];
            $currentRecord = $currentArgument;

            if (is_array($currentArgument)) {
                $currentRecord = json_encode($currentArgument);
            } elseif (is_object($currentArgument)) {
                $currentRecord = get_class($currentArgument);
            } elseif (is_resource($currentArgument)) {
                $currentRecord = get_resource_type($currentArgument);
            } elseif (is_callable($currentArgument)) {
                $currentRecord = '...';
            }

            $params .= gettype($currentArgument) . ':' . $currentRecord;

            if (array_key_last($arguments) != $i) {
                $params .= ';';
            }
        }
        $record[] = empty($params) ? '[MISSING]' : $params;

        $timeStart = microtime(true);
        $memoryStart = memory_get_usage();

        call_user_func_array([$this->object, $name], $arguments);

        // WorkTime.
        $record[] = (microtime(true) - $timeStart);

        // WorkMemory.
        $record[] = (memory_get_usage() - $memoryStart);

        $this->statistics[] = $record;
    }

    /**
     * Get statistics.
     *
     * @return array
     */
    public function getTrackerStatistics()
    {
        return $this->statistics;
    }
}
