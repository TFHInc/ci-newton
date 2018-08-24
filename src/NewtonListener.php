<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Newton Listener
 *
 * An abstract class for the Newton Listener classes.
 *
 * @package Newton
 * @author Colin Rafuse <colin.rafuse@gmail.com>
 */
abstract class NewtonListener {
    /**
     * The abstract 'run()' method.
     *
     * @return  void
     */
    abstract public function run($event): void;
}
