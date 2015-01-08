<?php

namespace Intervention\Httpauth;

interface UserInterface
{
    /**
     * Checks for valid username & password
     *
     * @param  string $name
     * @param  string $password
     * @param  string $realm
     * @param  Closure $ha1_callback
     *
     * @return boolean
     */
    public function isValid($name, $password, $realm, $ha1_callback);

    /**
     * Parses the User Information from server variables
     *
     * @return void
     */
    public function parse();

}
