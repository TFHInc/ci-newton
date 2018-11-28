<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Newton Subscriptions
|--------------------------------------------------------------------------
|
| Set the Newton subscriptions. This is an array of arrays. The key should
| be the event class and the value should be an array of listener classes.
|
| Example:
|
|   $config['subscriptions'] = [
|       'Events\UserCreatedEvent' => [
|           'Listeners\SendAdminEmailListener',
|           'Listeners\UpdateUserStatsListener'
|       ]
|   ];
|
| Note that the subsciptions will be added by event class name, so it is
| possible to subscribe a listener to an event more than one time.
|
*/
$config['subscriptions'] = [];