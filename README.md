# Newton

[![Latest Version on Packagist][ico-version]][link-packagist]
[![PHP Version][ico-php-version]][link-packagist]
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Simply Observe - Newton allows you to Subscribe and Listen for Event Broadcasts in the [Codeigniter](https://codeigniter.com/) framework.

## Installation

```bash
composer require tfhinc/ci-newton
```

Run the post install `publish-files` command to publish the config, helper and library class files to the appropriate CI directories:
```bash
composer --working-dir=vendor/tfhinc/ci-newton/ run-script publish-files
```

## Loading the Library

There are a few available options for loading the Newton library:

### Using the `newton()` helper function

The Newton helper function will resolve the Newton class via the CI instance. It will either load the class or return the existing class instance:

``` php
$this->load->helper('newton');
```

### Using the Newton Class

The Newton class can be instantiated via namespace:

``` php
$newton = new TFHInc/Newton/Newton();
```

### Using the Newton CI Library

The Newton class can be loaded like any other CI library:

``` php
$this->load->library('Newton');
```

## Class Structure

The Newton library allows for `Listener` classes to subscribe to `Event` class broadcasts. This helps decouple your business logic into single purpose `Listener` classes that can be invoked en masse by the broadcast of an `Event`.

### Event Classes

The `Event` class defines the properties that a given event will require and receive where there is a broadcast. The `Event` class does not contain any business logic - think of it as a blueprint of required data for a given event.

*application/events/UserCreatedEvent.php*
``` php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

namespace Events;

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

The `Listener` class contains the business logic that will be performed when a subscribed `Event` is broadcast. The `Listener` class will receive an instance of the `Event` class, which includes the event properties for usage in your business logic:

Note that the `Listener` class must extend the `TFHInc/Newton/NewtonListener` abstract class.

*application/listeners/SendAdminEmailListener.php*
``` php
<?php

namespace Listeners;

/**
 * SendAdminEmailListener
 *
 * Send the admin an email.
 *
 */
class SendAdminEmailListener extends TFHInc/Newton/NewtonListener {
    /**
     * Run the listener.
     *
     * @return  void
     */
    public function run($event): void
    {
        // Send the Admin an Email.
        // mailto('admin@example.com', 'New User ' . $event->email . ' just signed up!');
    }
}
```

*application/listeners/UpdateUserStatsListener.php*
```php
<?php

namespace Listeners;

/**
 * UpdateUserStatsListener
 *
 * Update the User stats with the new User data.
 *
 */
class UpdateUserStatsListener extends TFHInc/Newton/NewtonListener {
    /**
     * Run the listener.
     *
     * @return  void
     */
    public function run($event): void
    {
        // Update the User Stats.
    }
}
```

## Subscriptions

You can subscribe `Listener` classes to the `Event` classes. This means when the `Event` is broadcast the subscribed `Listener` classes wil be invoked via reflection. There are two ways to subscribe `Listeners` to `Events`:

### Via Configuration File

``` php
$config['subscriptions'] = [
    'Events\UserCreatedEvent' => [
        'Listeners\SendAdminEmailListener',
        'Listeners\UpdateUserStatsListener'
    ]
];
```

### Via Subscribe Method

```php
newton()->subscribe('Events\UserCreatedEvent', [
    'Listeners\SendAdminEmailListener',
    'Listeners\UpdateUserStatsListener'
]);
```

## Broadcasting Events

An `Event` can be broadcast via the `broadcast()` method using the Newton library or `newton()` helper function:

``` php
// Broadcast an Event to all Subscribed Listeners via Newton helper
newton()->broadcast('UserCreatedEvent', 'bob@example.com', 'Bob', 'Belcher');

// Broadcast an Event to all Subscribed Listeners via Newton class
$newton->broadcast('UserCreatedEvent', 'bob@example.com', 'Bob', 'Belcher');
```

In this example the `UserCreatedEvent` class will be instantiated and in turn,
instantiate the subscribed classes `SendAdminEmailListener` and `UpdateUserStatsListener`.

## Contributing

Feel free to create a GitHub issue or send a pull request with any bug fixes. Please see the GutHub issue tracker for isses that require help.

## Acknowledgements

- [Colin Rafuse][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tfhinc/ci-newton.svg?style=flat-square
[ico-php-version]: https://img.shields.io/packagist/php-v/tfhinc/ci-newton.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tfhinc/ci-newton.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tfhinc/ci-newton
[link-downloads]: https://packagist.org/packages/tfhinc/ci-newton
[link-author]: https://github.com/crafuse
[link-contributors]: ../../contributors
