<?php

class MockModel extends \Stormpath\Model
{
    protected $properties = array(
        'test1' => array(
            'type' => 'string'
        ),
        'id' => array(
            'type' => 'string'
        )
    );
}