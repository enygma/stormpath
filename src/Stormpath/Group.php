<?php

namespace Stormpath;

class Group extends \Stormpath\Model
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
        'directory' => array(
            'type' => 'href'
        ),
        'groups' => array(
            'type' => 'href'
        ),
        'href' => array(
            'type' => 'href'
        ),
        'id' => array(
            'type' => 'string'
        ),
        'accounts' => array(
            'type' => 'href'
        )
    );

    /**
     * Find one Group by ID
     * 
     * @param string $groupId Group ID
     * @return array Group details
     */
    public function findOne($groupId)
    {
        $request = $this->getRequest();

        $url = '/v1/groups/'.$groupId;
        $results = $request->setUrl($url)->execute();

        $this->load((array)$results);
        return $results;
    }

    /**
     * Get the Accounts in this group
     * 
     * @param string $groupId Group ID [optional]
     * @return array Set of accounts
     */
    public function getAccounts($groupId = null)
    {
        $request = $this->getRequest();
        $groupId = ($groupId !== null) ? $groupId : $this->id;

        $url = '/v1/groups/'.$groupId.'/accounts';
        $results = $request->setUrl($url)->execute();
        return $results->items;
    }
}