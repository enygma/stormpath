<?php

namespace Stormpath;

class Account extends \Stormpath\Model
{
    protected $properties = array(
        'href' => array(
            'type' => 'string'
        ),
        'username' => array(
            'type' => 'string'
        ),
        'email' => array(
            'type' => 'text'
        ),
        'givenName' => array(
            'type' => 'text'
        ),
        'middleName' => array(
            'type' => 'text'
        ),
        'surname' => array(
            'type' => 'text'
        ),
        'status' => array(
            'type' => 'string'
        ),
        'directory' => array(
            'type' => 'href'
        ),
        'tenant' => array(
            'type' => 'href'
        ),
        'group' => array(
            'type' => 'href'
        ),
        'groupMemberships' => array(
            'type' => 'href'
        ),
        'passwordResetTokens' => array(
            'type' => 'href'
        ),
        
    );

    /**
     * Find list of current accounts
     * 
     * @throws \Exception Not supported
     * @todo
     */
    public function find()
    {
        throw new \Exception('Operation not supported');
    }

    /**
     * Find the details for one account
     * 
     * @param string $accountId Account ID
     * @return array Return data
     */
    public function findOne($accountId)
    {
        $request = $this->getRequest();
        $tenantId = $this->getConfig()->get('tenantId');

        $url = '/v1/accounts/'.$accountId;
        $results = $request->setUrl($url)->execute();

        $this->load((array)$results);
        return $results;
    }
}