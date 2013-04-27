<?php

namespace Stormpath;

class Application extends \Stormpath\Model
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
        'accounts' => array(
            'type' => 'href',
            'class' => 'Account'
        ),
        'tenant' => array(
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
     * Extract the ID from the HREF setting
     * 
     * @param string $value HREF value for application
     */
    public function setHref($value)
    {
        $parts = explode('/', $value);
        $this->values['id'] = trim($parts[count($parts)-1]);
    }

    /**
     * Get a listing of all applications in the account
     * 
     * @return array Set of application data
     */
    public function find()
    {
        $request = $this->getRequest();
        $tenantId = $this->getConfig()->get('tenantId');

        $url = '/v1/tenants/'.$tenantId.'/applications';
        $results = $request->setUrl($url)->execute();
        return $results;
    }

    /**
     * Find one application by ID, populate object with data if found
     * 
     * @param string $appId Application unique ID (hash)
     * @return object Application data
     */
    public function findOne($appId)
    {
        $request = $this->getRequest();
        $tenantId = $this->getConfig()->get('tenantId');

        $url = '/v1/applications/'.$appId;
        $results = $request->setUrl($url)->execute();

        $this->load((array)$results);
        return $results;
    }

    /**
     * Enable an application
     * 
     * @return boolean Success/fail of enable call
     */
    public function enable()
    {
        $request = $this->getRequest();
        $url = '/v1/applications/154hoAGm9PhGECgYbh2n59';
        $data = array('status' => 'ENABLED');
        $results = $request->setUrl($url)
            ->setMethod('POST')
            ->setData($data)
            ->execute();

        return ($request->success() === true) ? true : false;
    }

    /**
     * Disable the application
     * 
     * @return boolean Success/fail of the disable call
     */
    public function disable()
    {
        $request = $this->getRequest();
        $url = '/v1/applications/154hoAGm9PhGECgYbh2n59';
        $data = array('status' => 'DISABLED');
        $results = $request->setUrl($url)
            ->setMethod('POST')
            ->setData($data)
            ->execute();

        return ($request->success() === true) ? true : false;
    }

    /**
     * Save the application data (new and update)
     * 
     * @return boolean Success/fail of the save
     */
    public function save()
    {
        $url = ($this->id === null)
            ? '/v1/applications' : '/v1/applications/'.$this->id;

        $request = $this->getRequest();
        $data = $this->toArray();
        $results = $request->setUrl($url)
            ->setMethod('POST')
            ->setData($data)
            ->execute();

        return ($request->success() === true) ? true : false;
    }
}

?>