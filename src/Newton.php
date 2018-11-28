<?php

namespace TFHInc\Newton;

/**
 * Newton
 *
 * Simply Observe - Subscribe and Listen for Broadcasted Events.
 *
 * @package Newton
 * @author Colin Rafuse <colin.rafuse@gmail.com>
 */
class Newton {
    /**
     * @var object
     */
    protected $CI;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $subscriptions = [];

    /**
     * Create an instance of Newton.
     *
     * @return Newton
     */
    public function __construct()
    {
        // Get the CodeIgnitor Instance
        $this->CI =& get_instance();

        // Load the Newton config file located in application/config/newton.php
        // An example file can be found in Config/newton.php
        $this->CI->config->load('newton', TRUE);

        // Subscribe listeners to events via the config file, if any have been set
        $this->config['subscriptions'] = $this->CI->config->item('subscriptions', 'newton');
        if (is_array($this->config['subscriptions'])) {
            $this->subscribeFromConfig();
        }
    }

/*
|--------------------------------------------------------------------------
| Newton Broadcast
|--------------------------------------------------------------------------
|
| Handle the Newton Broadcast.
|
*/
    /**
     * Broadcast a Newton Event.
     *
     * @param   string      $event
     * @param   array       $arguments
     * @return  void
     */
    public function broadcast(string $event, ...$arguments): void
    {
        // Is this event subscribed to? If not, just return and don't fire
        if (!array_key_exists($event, $this->subscriptions)) {
            return;
        }

        // Construct the event class via Reflection
        $event_class = $this->constructReflectionClass($event);

        // Validate the event class constructor argument count against the provided argument count
        // Throw an exception if the count does not equal
        if (count($event_class->getConstructor()->getParameters()) !== count($arguments)) {
            throw new Exceptions\ArgumentCountException('The argument count is incorrect for the ' . $event . ' class constructor.');
        }

        // Create a new event class instance and pass the provided arguments
        $event_instance = $event_class->newInstance(...array_values($arguments));

        // Fire the registered listeners for the event
        $this->fire($event_class, $event_instance);
    }

/*
|--------------------------------------------------------------------------
| Newton Subscriptions
|--------------------------------------------------------------------------
|
| Handle the Newton Subscriptions.
|
*/
    /**
     * Subscribe the Newton Listeners.
     *
     * @param   string      $event
     * @param   mixed       $listeners
     * @return  void
     */
    public function subscribe(string $event, $listeners): void
    {
        if (gettype($listeners) === 'string') {
            $this->subscriptions[$event][] = $listeners;
        } elseif (gettype($listeners) === 'array') {
            foreach ($listeners as $listener) {
                $this->subscriptions[$event][] = $listener;
            }
        }
    }

    /**
     * Subscribe the Newton Listeners from the Newton configuration file.
     *
     * @return  void
     */
    private function subscribeFromConfig(): void
    {
        foreach ($this->config['subscriptions'] as $event => $listeners) {
            $this->subscribe($event, $listeners);
        }
    }

/*
|--------------------------------------------------------------------------
| Newton Listeners
|--------------------------------------------------------------------------
|
| Handle the Newton Listeners.
|
*/
    /**
     * Fire the Subscribed Listeners for the given Event.
     *
     * @param   mixed      $event_class
     * @param   mixed      $event_instance
     * @return  void
     */
    private function fire($event_class, $event_instance): void
    {
        foreach ($this->subscriptions[$event_class->getName()] as $listener) {
            $listener_class = null;
            $listener_run_method = null;

            // Construct the listener class via Reflection
            $listener_class = $this->constructReflectionClass($listener);

            // Invoke the Listener
            if (in_array('TFHInc\\Echelon\\Traits\\EchelonQueueListener', $listener_class->getTraitNames()) === true) {
                $listener_run_method = new \ReflectionMethod($listener, 'queue');
                $listener_run_method->invoke(new $listener, $event_instance);
            } else {
                $listener_run_method = new \ReflectionMethod($listener, 'run');
                $listener_run_method->invoke(new $listener, $event_instance);
            }
        }
    }

/*
|--------------------------------------------------------------------------
| Newton Utilities
|--------------------------------------------------------------------------
|
| Newton Utility Methods.
|
*/
    /**
     * Construct a class via Reflection.
     *
     * @param   string      $class_name
     * @return  mixed
     */
    private function constructReflectionClass($class_name)
    {
        try {
            return new \ReflectionClass($class_name);
        } catch (\ReflectionException | \Exception $e) {
            throw new Exceptions\ClassNotFoundException('The class ' . $class_name . ' does not exist and cannot be constructed.');
        }
    }
}
