<?php

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Validation\Validators\Validate;

use Validation\Helper\ErrorHandler;

$obj = new ErrorHandler();
class ValidateTest extends TestCase
{
    public function testisInt()
    {
        $this->assertTrue(Validate::isInt(12), "Invalid Boolean Value");
        $this->assertSame(true, Validate::isInt('12'));
        $this->assertSame(true, Validate::isInt(12));
        $this->assertSame(false, Validate::isInt(12.12));
        $this->assertSame(true, Validate::isInt(12.0));
    }
}
