<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Newton Base Paths
|--------------------------------------------------------------------------
|
| Set the base paths for the Newton event and listener classes.
|
*/
$config['base_event_path'] = APPPATH . 'events/';
$config['base_listener_path'] = APPPATH . 'listeners/';

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
|       'UserCreatedEvent' => [
|           'SendAdminEmailListener',
|           'UpdateUserStatsListener'
|       ]
|   ];
|
| Note that the subsciptions will be added by event class name, so it is
| possible to subscribe a listener to an event more than one time.
|
*/
$config['subscriptions'] = [];