<?php

namespace Stormpath;

class Model
{
    /**
     * Properties of the current model
     * @var array
     */
    protected $properties = array();

    /**
     * Values for the current model
     * @var array
     */
    protected $values = array();

    /**
     * Configuration object
     * @var \Stormpath\Config
     */
    protected $config = null;

    protected $request = null;

    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->load($data);
        }
    }

    public function setConfig(\Stormpath\Config $config)
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Extract the ID from the HREF setting
     * 
     * @param string $value HREF value for application
     */
    public function setHref($value)
    {
        $parts = explode('/', $value);
        $this->values['id'] = trim($parts[count($parts)-1]);
    }

    /**
     * Set properties for the model
     *
     * @param array $properties Properties to set
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * Get the current model's properties
     *
     * @return array Current properties
     */
    public function getProperties()
    {
        return $this->properties;
    }

    public function setRequest(\Stormpath\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Load the given data into the current object
     *
     * @param array $data Data to load
     * @return boolean True on finish
     */
    public function load($data)
    {
        // if it's an object, get the values as an array
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data to load should be an array');
        }

        foreach ($data as $index => $value) {
            // see what the type is
            if (array_key_exists($index, $this->properties)) {
                $config = $this->properties[$index];
                if ($config['type'] == 'array') {

                    // see if we have a type to try to cast
                    if (isset($config['map'])) {
                        $tmp = array();
                        $className = $config['map'];
                        foreach ($value as $mapData) {
                            $tmp[] = new $className($mapData);
                        }
                        $this->values[$index] = $tmp;

                    } else {
                        $this->values[$index] = $value;
                    }
                } else {
                    $this->values[$index] = $value;
                }
                // see if there's a "set" method that needs to do anything else
                $setMethod = 'set'.ucwords(strtolower($index));
                if (method_exists($this, $setMethod)) {
                    $this->$setMethod($value);
                }
            }
        }
        return true;
    }

    /**
     * Magic "get" method
     *
     * @param string $property Property name
     * @return mixed|null Property value if it exists, null if not
     */
    public function __get($property)
    {
        // if it's a href...
        if (array_key_exists($property, $this->values)) {

            // If it's a HREF, pull it
            if (isset($this->values[$property]->href)) {
                $urlParts = parse_url($this->values[$property]->href);
                $uri = explode('/', $urlParts['path']);
                $part = $uri[count($uri)-1];

                if (strtolower(substr($uri[count($uri)-1], -1)) == 's') {
                    $className = ucwords(strtolower(substr($part, 0, strlen($part)-1)));
                } else {
                    $className = ucwords(strtolower($part));
                }

                $method = 'get'.ucwords($uri[count($uri)-1]);
                if (method_exists($this, $method)) {
                    $data = $this->$method();
                    $class = '\\Stormpath\\'.$className;

                    if (is_array($data)) {
                        // push them into objects
                        $result = array();
                        foreach ($data as $d) {
                            $obj = new $class();
                            $obj->load($d);
                            $result[] = $obj;
                        }
                    } else {
                        $result = new $class();
                        $result->load($data);
                    }
                    
                    return $result;
                } else {
                    throw new \Exception('Cannot fetch "'.$property.'" - unknown method');
                }

            } else {
                return $this->values[$property];
            }
        } else {
            return null;
        }
    }

    /**
     * Magic "set" method
     *
     * @param string $property Property name
     * @param mixed $value Property value
     */
    public function __set($property, $value)
    {
        $this->values[$property] = $value;
    }

    /**
     * Output the values of the object as an array
     *
     * @return array Current object values
     */
    public function toArray()
    {
        return $this->values;
    }
}