Stormpath API Client
=============

This library is an example of working with the Stormpath user authentication REST API.
It's a limited functionality proof of concept, mostly defined around their concept 
of "Applications".

Sample Usage:
=============

You'll need to have your `apiKey.properties` file they give you in the same directory 
as your test script - it looks for it there.

#### Sample code:

```php
<?php
require_once 'vendor/autoload.php';

// Define a configuration object with your tenant ID
$config = new \Stormpath\Config();
$config->set('tenantId', 'sample-tenant-id-here');

// Create a service instance
$service = new \Stormpath\Service($config);

// You can use "magic properties" to get the application list
$apps = $service->applications;

// Or you can fetch the info for just one on the list
$appId = 'your-application-id-here';
$app = $service->getApplication($appId);
echo 'App name: '.$app->name." (".$app->status.")\n";

// You can also update the application...
$app->name = 'test6';
$service->save($app);

// ...or make a new one
$newApp = new \Stormpath\Application();
$newApp->name = 'test4';
$newApp->description = 'test1 desc';
$newApp->status = 'enabled';
$service->save($newApp);

// There's two functions to enable and disable the application too
if ($app->disable() == true) {
    echo 'Application disabled!';
}
if ($app->enable() == true) {
    echo 'Application enabled!';
}

?>
```