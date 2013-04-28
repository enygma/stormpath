<?php

namespace Stormpath;

class Config
{
    /**
     * Default configuration options
     * @var array
     */
    private $config = array(
        'api.basepath' => 'https://api.stormpath.com'
    );

    /**
     * Filename (path) to API properties file
     * @var string
     */
    private $filename = 'apiKey.properties';

    /**
     * Init the object and load the config
     * @param array $addl Additional config options [optional]
     */
    public function __construct(array $addl = null)
    {
        $this->load($addl);
    }

    /**
     * Set the filename for the config properties file
     * @param string $file File path
     */
    public function setFilename($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException('Invalid file path: '.$file);
        }
        $this->filename = $file;
    }

    /**
     * Get the current filename
     * @return string File path
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the current configuration (merges in new options)
     * @param array $config Configuration options
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Set a configuration option
     * @param string $key Key name
     * @param mixed $value Option value
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * Get a configuration option
     * @param string $key Key name to find
     * @return mixed Config value if found, null if not
     */
    public function get($key)
    {
        return (isset($this->config[$key])) ? $this->config[$key] : null;
    }

    public function getConfig($key = null)
    {
        return ($key !== null && isset($this->config[$key]))
            ? $this->config[$key] : $this->config;
    }

    public function load($addl = null)
    {
        $file = $this->getFilename();
        $config = null;

        if (is_file($file)) {
            $config = parse_ini_file($file);
            if ($config == false) {
                throw new \InvalidArgumentException(
                    'Configuration file could not be parsed: '.$file
                );
            }
        } else if ($addl !== null && is_array($addl)) {
            $config = $addl;
        }
        if ($config !== null) {
            $this->setConfig($config);
        }
    }
}