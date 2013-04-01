<?php

namespace Stormpath;
use Guzzle\Http\Client;

class Request
{
    /**
     * Current request configuration
     * @var \Stormpath\Config
     */
    private $config = null;

    /**
     * Current HTTP client (uses Guzzle)
     * @var object
     */
    private $client = null;

    /**
     * Request HTTP method (default "GET")
     * @var string
     */
    private $method = 'GET';

    /**
     * Current request URL
     * @var string
     */
    private $url = '';

    /**
     * Data to send in the request
     * @var array
     */
    private $data = array();

    /**
     * Success/fail of request
     * @var boolean
     */
    private $success = false;

    /**
     * Create the object and set up the configuration, make a client object
     * @param \Stormpath\Config $config [description]
     */
    public function __construct(\Stormpath\Config $config)
    {
        $this->setConfig($config);
        $this->setClient();
    }

    /**
     * Set the client object
     * 
     * @param object $client Client object
     */
    public function setClient($client = null)
    {
        // create a new Guzzle client
        $basepath = $this->getConfig()->get('api.basepath');
        $this->client = new Client($basepath);
        return $this;
    }

    /**
     * Get the current client object
     * 
     * @return object Client instance
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the Request configuration
     * 
     * @param \Stormpath\Config $config Config settings
     * @return \Stormpath\Request instance
     */
    public function setConfig(\Stormpath\Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get the current configuration settings
     * 
     * @return \Stormpath\Config instance
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the current requested URL
     * 
     * @return string URL requested
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the URL for the request
     * 
     * @param string $url Request URL
     * @return \Stormpath\Request instance
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get the HTTP method for request (Ex. "GET" or "POST")
     * 
     * @return string HTTP method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the HTTP method for request
     * 
     * @param string $method HTTP method (Ex. "GET" or "POST")
     * @return \Stormpath\Request instance
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Set the data for the request
     * 
     * @param array $data Request data
     * @return \Stormpath\Request instance
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get the data for the request
     * 
     * @return array Request data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the success status of the request
     * 
     * @param boolean $status Success status
     * @return \Stormpath\Request instance
     */
    public function setSuccess($status)
    {
        $this->success = $status;
        return $this;
    }

    /**
     * Get the success status of request
     * 
     * @return boolean Request status
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Execute the request
     * 
     * @return array/object Request results
     */
    public function execute()
    {   
        $results = null;

        $method = strtolower($this->getMethod());
        $url = $this->getUrl();
        $apiId = $this->getConfig()->get('apiKey.id');
        $apiSecret = $this->getConfig()->get('apiKey.secret');
        $data = $this->getData();
        $client = $this->getClient();

        if (in_array(strtoupper($method), array('GET', 'DELETE'))) {
            $request = $client->$method($url)->setAuth($apiId, $apiSecret);
        } elseif (in_array(strtoupper($method), array('POST', 'PUT'))) {
            $request = $client->$method(
                $url, 
                array('Content-Type' => 'application/json'),
                json_encode($data)
            )->setAuth($apiId, $apiSecret);
        } else {
            throw new \InvalidArgumentException('Invalid method "'.$method.'"');
        }

        try {
            $response = $request->send();
            if ($response->getStatusCode() == '200') {
                $this->setSuccess(true);
                $results = json_decode($response->getBody());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $results;
    }

    public function success()
    {
        return $this->getSuccess();
    }
}