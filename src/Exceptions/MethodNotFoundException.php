<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Method Not Found Exception
 *
 * A class method doesn't exist.
 *
 */
class MethodNotFoundException extends Exception {
    /**
     * Construct the exception.
     *
     * @param   string              $message
     * @param   integer             $code
     * @param   Exception|null      $previous
     * @return  MethodNotFoundException
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Construct the event.
     *
     * @return  string
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
