<?php

namespace Stormpath;

class Service
{
    /**
     * Current configuration
     * @var \Stormpath\Config
     */
    private $config = null;

    /**
     * Current request object
     * @var \Stormpath\Request
     */
    private $request = null;

    /**
     * Create the object and set up the config/request objects
     * 
     * @param \Stormpath\Config $config Configuration settings
     * @param \Stormpath\Request $request Request object [optional]
     */
    public function __construct(\Stormpath\Config $config, \Stormpath\Request $request = null)
    {
        $this->setConfig($config);

        if ($request === null) {
            // make a request object to use
            $request = new \Stormpath\Request($config);
        }
        $this->setRequest($request);
    }

    /**
     * Set the Request object
     * 
     * @param \Stormpath\Request $request Request object
     */
    public function setRequest(\Stormpath\Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the current Request object
     * 
     * @return \Stormpath\Request Request object
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Build an instance of the given object
     * 
     * @param string $className Name of class to instanciate
     * @return object Generated object
     */
    public function buildInstance($className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Invalid class name "'.$className.'"');
        }        
        $instance = new $className();
        $instance->setConfig($this->getConfig())
            ->setRequest($this->getRequest());

        return $instance;
    }

    /**
     * Method to handle "get*" calls on the service
     * 
     * @param string $name Method name
     * @param array $arguments Function arguments
     * @return object|null If class exists, tries to find object. Otherwise null.
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) === 'get') {
            $name = str_replace('get', '', $name);
            $className = "\\Stormpath\\".ucwords(strtolower($name));

            $instance = $this->buildInstance($className);
            call_user_func_array(array($instance, 'findOne'), $arguments);
            return $instance;
        }
        return null;
    }

    /**
     * Method to handle the property calls for a service
     *     Example: $service->applications
     * 
     * @param string $name Property name
     * @return array Collection of data or empty set 
     */
    public function __get($name)
    {
        // if it ends in "s" we're wanting a collection
        if (substr(strtolower($name), -1) == 's') {
            $name = substr($name, 0, strlen($name)-1);
            $className = "\\Stormpath\\".ucwords(strtolower($name));
            $arguments = array();

            $instance = $this->buildInstance($className);
            $results = call_user_func_array(array($instance, 'find'), $arguments);

            $collection = new \Stormpath\Collection($results->items, $className);
            return $collection;
        }
        return array();
    }

    /**
     * Get the service's current confgiuration
     * 
     * @return \Stormpath\Config $config Configuration object
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the service's configuration
     * 
     * @param \Stormpath\Config $config Configuration object
     */
    public function setConfig(\Stormpath\Config $config)
    {
        $this->config = $config;
    }

    /**
     * Call the "save" method on the object
     * 
     * @param object $object Object to call method on
     * @return boolean Success/fail of save call
     */
    public function save($object)
    {
        $object->setConfig($this->getConfig())
            ->setRequest($this->getRequest());

        return $object->save();
    }

    /**
     * Call the "delete" method on the object
     * 
     * @param object $object Object to call method on
     * @return boolean Success/fail of save call
     */
    public function delete($object)
    {
        $object->setConfig($this->getConfig())
            ->setRequest($this->getRequest());

        return $object->delete();
    }
}