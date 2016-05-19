<?php

namespace Pfw;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testIsAdmin()
    {
        $this->defineConstant('XH_ADM', true);
        $this->assertTrue(User::isAdmin());
        $this->defineConstant('XH_ADM', false);
        $this->assertFalse(User::isAdmin());
    }
    
    private function defineConstant($name, $value)
    {
        if (defined($name)) {
            runkit_constant_redefine($name, $value);
        } else {
            define($name, $value);
        }
    }
}
