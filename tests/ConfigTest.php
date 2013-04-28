<?php

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $config;

    public function setUp()
    {
        $this->config = new \Stormpath\Config();
    }

    /**
     * Test that loading additional values through the constructor works correctly
     * @covers \Stormpath\Config::load
     * @covers \Stormpath\Config::__construct
     */
    public function testLoadAdditional()
    {
        $addl = array('test' => 'foo');
        $config = new \Stormpath\Config($addl);

        $this->assertEquals($config->get('test'), 'foo');
    }

    /**
     * Test that the getter/setters work correctly
     * @covers \Stormpath\Config::setConfig
     * @covers \Stormpath\Config::getConfig
     */
    public function testGetSetAllConfig()
    {
        $this->config->setConfig(array('foo' => 'bar'));
        $this->assertEquals($this->config->getConfig('foo'), 'bar');
    }

    /**
     * Test that the single getter/setter works
     * @covers \Stormpath\Config::get
     * @covers \Stormpath\Config::set
     */
    public function testGetSetConfig()
    {
        $this->config->set('foo', 'baz');
        $this->assertEquals($this->config->get('foo'), 'baz');
    }
}