<?php

namespace ruslan\Emutator\Tests;

use Orchestra\Testbench\TestCase;
use ruslan\Emutator\MailTo;

class MailToTest extends TestCase
{
    /** @test */
    public function mutate_an_email_address()
    {
        $email = 'email@gmail.com';
        $this->assertNotEquals($email, MailTo::mutate($email));
    }
}