<?php
include_once 'MockModel.php';
include_once 'MockClient.php';

class ModelTest extends \PHPUnit_Framework_TestCase
{
    private $model = null;

    public function setUp()
    {
        $this->model = new MockModel();
    }

    /**
     * Test the loading of valid data
     * @covers \Stormpath\Model::load
     * @covers \Stormpath\Model::toArray
     */
    public function testLoadValidData()
    {
        $data = array('test1' => 'testing 1234');

        $this->model->load($data);
        $this->assertEquals($data, $this->model->toArray());
    }

    /**
     * Test that the get/set magic methods work correctly
     * @covers \Stormpath\Model::__set
     * @covers \Stormpath\Model::__get
     */
    public function testGetSetMagicMethods()
    {
        $this->model->test = 'foobarbaz';
        $this->assertEquals($this->model->test, 'foobarbaz');
    }

    /**
     * Test that the getter/setter for config works
     * @covers \Stormpath\Model::setConfig
     * @covers \Stormpath\Model::getConfig
     */
    public function testGetSetConfig()
    {
        $config = new \Stormpath\Config(array('test' => 'foo'));
        $this->model->setConfig($config);

        $cfg = $this->model->getConfig();
        $this->assertEquals($cfg->get('test'), 'foo');
    }

    /**
     * Test that the ID is set correctly when the HREF is parsed
     * @covers \Stormpath\Model::setHref
     */
    public function testSetHref()
    {
        $href = '12345abcdefg';
        $url = 'http://stormpath.com/'.$href;

        $this->model->setHref($url);
        $this->assertEquals($this->model->id, $href);
    }

    /**
     * Test the getter/setter for the properties of the model
     * @covers \Stormpath\Model::setProperties
     * @covers \Stormpath\Model::getProperties
     */
    public function testGetSetProperties()
    {
        $properties = array(
            'foo' => array(
                'type' => 'string'
            )
        );
        $this->model->setProperties($properties);
        $this->assertEquals($this->model->getProperties(), $properties);
    }

    /**
     * Test that the getter/setter for the request works correctly
     * @covers \Stormpath\Model::setRequest
     * @covers \Stormpath\Model::getRequest
     */
    public function testGetSetRequest()
    {
        $client = new MockClient();
        $config = new \Stormpath\Config(array('test' => 'foo'));
        $request = new \Stormpath\Request($config, $client);

        $this->model->setRequest($request);
        $this->assertEquals($this->model->getRequest(), $request);
    }

    /**
     * Test that the values of an object are correctly set in a load
     * @covers \Stormpath\Model::load
     */
    public function testLoadDataObject()
    {
        $obj = new \stdClass();
        $obj->test = 'foo';

        $this->model->setProperties(array(
            'test' => array(
                'type' => 'string'
            )
        ));

        $this->model->load($obj);
        $this->assertEquals($this->model->test, 'foo');
    }

    /**
     * Test that an exception is thrown when bad data is given
     * @covers \Stormpath\Model::load
     * @expectedException \InvalidArgumentException
     */
    public function testLoadInvalidData()
    {
        $this->model->load('baddata');
    }

    /**
     * Test the casing of the data when a "map" value is given
     * @covers \Stormpath\Model::load
     */
    public function testLoadDataWithMap()
    {
        $this->model->setProperties(array(
            'test' => array(
                'type' => 'array',
                'map' => '\\Stormpath\\Account'
            )
        ));

        $data = array(
            'test' => array(
                array('username' => 'testing1234')
            )
        );

        $this->model->load($data);
        $data = $this->model->toArray();

        $this->assertTrue(
            isset($data['test']) && ($data['test'][0] instanceof \Stormpath\Account)
        );
    }
}