<?php

namespace Stormpath;

class Config
{
    private $config = array(
        'api.basepath' => 'https://api.stormpath.com'
    );

    private $filename = 'apiKey.properties';

    public function __construct($file = null)
    {
        if ($file !== null) {
            $this->setFilename($file);
        }
        $this->load();
    }

    public function setFilename($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException('Invalid file path: '.$file);
        }
        $this->filename = $file;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function get($key)
    {
        return (isset($this->config[$key])) ? $this->config[$key] : null;
    }

    public function getConfig($key = null)
    {
        return ($key !== null && isset($this->config[$key]))
            ? $this->config[$key] : $this->config;
    }

    public function load()
    {
        $file = $this->getFilename();
        $config = parse_ini_file($file);
        if ($config == false) {
            throw new \InvalidArgumentException(
                'Configuration file could not be parsed: '.$file
            );
        }
        $this->setConfig($config);
    }
}