<?php
include_once 'MockClient.php';

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    private $service = null;
    private $request = null;

    public function setUp()
    {
        $config = new \Stormpath\Config();
        $mockClient = new MockClient();

        $this->request = new \Stormpath\Request($config, $mockClient);
        $this->service = new \Stormpath\Service($config, $this->request);
    }

    /**
     * Test that the request on the service object is equal to the one we gave
     * @covers \Stormpath\Service::getRequest
     */
    public function testGetSetRequestValid()
    {
        $this->assertEquals($this->request, $this->service->getRequest());
    }
}