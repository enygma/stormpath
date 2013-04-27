<?php

namespace Stormpath;

class Directory extends \Stormpath\Model
{
    protected $properties = array(
        'name' => array(
            'type' => 'string'
        ),
        'description' => array(
            'type' => 'string'
        ),
        'status' => array(
            'type' => 'string'
        ),
        'tenant' => array(
            'type' => 'href'
        ),
        'groups' => array(
            'type' => 'href'
        ),
        'accounts' => array(
            'type' => 'href'
        ),
        'href' => array(
            'type' => 'href'
        ),
        'id' => array(
            'type' => 'string'
        )
    );

    /**
     * Find one details for one directory
     * 
     * @param string $directoryId Directory ID
     * @return array Directory details
     */
    public function findOne($directoryId)
    {
        $request = $this->getRequest();

        $url = '/v1/directories/'.$directoryId;
        $results = $request->setUrl($url)->execute();

        $this->load((array)$results);
        return $results;
    }

    /**
     * Get the groups for a directory
     * 
     * @param string $directoryId Directory ID [optional]
     * @return array Set of groups for directory
     */
    public function getGroups($directoryId = null)
    {
        $request = $this->getRequest();
        $directoryId = ($directoryId !== null) ? $directoryId : $this->id;

        $url = '/v1/directories/'.$directoryId.'/groups';
        $results = $request->setUrl($url)->execute();
        return $results->items;
    }

}