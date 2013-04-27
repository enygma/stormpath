<?php
include_once 'MockModel.php';

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
}