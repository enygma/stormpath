<?php

namespace Stormpath;

class Collection extends \ArrayIterator
{
    /**
     * Collection data
     * @var array
     */
    private $data = array();

    /**
     * Create and populate the object with data/objects
     *     Takes in data and creates objects based on class name
     * 
     * @param array $data Input data
     * @param string $className Name of class to use for object creation
     */
    public function __construct($data, $className)
    {
        if (class_exists($className)) {
            foreach ($data as $index => $content) {
                $obj = new $className((array)$content);
                $this->data[] = $obj;
            }
        }
    }
}