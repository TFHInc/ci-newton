# Newton

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Simply Observe - Newton allows you to Subscribe and Listen for Event Broadcasts in the [Codeigniter](https://codeigniter.com/) framework.

## Installation

### Manual Install

- Download the source code .zip and extract
- Copy the `Newton` directory into `application/libraries/`
- Copy the `Newton/Config/newton.example.php` file into `application/config/newton.php`
- Copy the `Newton/Helpers/newton_helper.php` file into `application/helpers/newton_helper.php`

## Configuration

### Base Paths

The base paths for the `Listener` and `Event` class file locations must be defined:

```php
// Base Event path: application/events/
$config['base_event_path'] = APPPATH . 'events/';

// Base Listener path: application/listeners/
$config['base_listener_path'] = APPPATH . 'listeners/';
```

You will need to manually create the `application/events/` and `application/listeners/` directories if you'd like to structure your `Events` and `Listeners` into their own directories off of the application root.

If you'd prefer to store the `Event` and `Listener` classes in sub directories throughout your application, you can set the base paths to the `APPPATH` and then provide the specific directory structure when defining your subscriptions.

```php
// Base Event path: application/
$config['base_event_path'] = APPPATH;

// Base Listener path: application/
$config['base_listener_path'] = APPPATH;
```

See the `Subscriptions` section of the documentation for further details.
### Subscriptions

See the `Subscriptions` section of the documentation for further details.

## Loading the Library

There are a few available options for loading the Newton libray:

### Using the `newton()` helper function

The Newton helper function will resolve the library via the CI instance. It will either load the library or return the existing library instance:

``` php
$this->load->helper('newton');
```

### Using the Newton library

The Newton library can be loaded when you require it:

``` php
$this->load->library('Newton/src/Newton');
```

or autoloaded in the `config/autoload.php`:

``` php
$autoload['libraries'] = array('Newton/src/Newton');
```

## Class Structure

The Newton library allows for `Listener` classes to subscribe to `Event` class broadcasts. This helps decouple your business logic into single purpose `Listener` classes that can be invoked en masse by the broadcast of an `Event`.

### Event Classes

The `Event` class defines the properties that a given event will require and receive where there is a broadcast. The `Event` class does not contain any business logic - think of it as a blueprint of required data for a given event.

*application/events/UserCreatedEvent.php*
``` php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * UserCreatedEvent
 *
 * A new User has been created. Yay, new user!
 *
 */
class UserCreatedEvent {
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * Construct the event.
     *
     * @param   string   $email
     * @param   string   $first_name
     * @param   string   $last_name
     * @return  UserCreatedEvent
     */
    public function __construct(string $email, string $first_name, string $last_name)
    {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }
}
```

### Listener Classes

The `Listener` class contains the business logic that will be performed when a subscribed `Event` is broadcast. The `Listener` class will receieve an instance of the `Event` class, which includes the event properties for usage in your business logic:

*application/listeners/SendAdminEmailListener.php*
``` php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SendAdminEmailListener
 *
 * Send the admin an email.
 *
 */
class SendAdminEmailListener {
    /**
     * Run the listener.
     *
     * @return  void
     */
    public function run(UserCreatedEvent $event): void
    {
        // Send the Admin an Email.
        // mailto('admin@example.com', 'New User ' . $event->email . ' just signed up!');
    }
}
```

*application/listeners/UpdateUserStatsListener.php*
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * UpdateUserStatsListener
 *
 * Update the User stats with the new User data.
 *
 */
class UpdateUserStatsListener {
    /**
     * Run the listener.
     *
     * @return  void
     */
    public function run(UserCreatedEvent $event): void
    {
        // Update the User Stats.
        //
    }
}
```

## Subscriptions

You can subscribe `Listener` classes to the `Event` classes. This means when the `Event` is broadcast, the subscribed `Listener` classes wil be invoked via reflection. There are two ways to subscribe `Listeners` to `Events`:

### Via Configuration File

``` php
$config['subscriptions'] = [
    'UserCreatedEvent' => [
        'SendAdminEmailListener',
        'UpdateUserStatsListener'
   ]
];
```

### Via Subscribe Method

```php
// Subscribe the Event Listeners via Newton helper
newton()->subscribe('UserCreatedEvent', [
    'SendAdminEmailListener',
    'UpdateUserStatsListener'
]);

// Subscribe the Event Listeners via Newton library
$this->newton->subscribe('UserCreatedEvent', [
    'SendAdminEmailListener',
    'UpdateUserStatsListener'
]);
```

## Broadcasting Events

An `Event` can be broadcast via the `broadcast()` method using the Newton library or `newton()` helper function:

``` php
// Broadcast an Event to all Subscribed Listeners via Newton helper
newton()->broadcast('UserCreatedEvent', 'bob@example.com', 'Bob', 'Belcher');

// Broadcast an Event to all Subscribed Listeners via Newton library
$this->newton->broadcast('UserCreatedEvent', 'bob@example.com', 'Bob', 'Belcher');
```

In this example, the `UserCreatedEvent` class will be instantiated and will
then instantiate the subscribed classes `SendAdminEmailListener` and `UpdateUserStatsListener`.

## Contributing

Feel free to create a GitHub issue or send a pull request with any bug fixes. Please see the GutHub issue tracker for isses that require help.

## Acknowledgements

- [Colin Rafuse][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-author]: https://github.com/crafuse
[link-contributors]: ../../contributors