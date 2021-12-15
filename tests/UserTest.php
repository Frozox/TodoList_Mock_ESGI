<?php

namespace App\Tests;

use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /*
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
    */
    private User $user;
    public function testValidUser()
    {
        $this->user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $this->user->isValid();
        $this->assertTrue($isValid);
    }

    public function testNotValidUserEmail()
    {
        $user = new User('testcase.fr','Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    public function testNotValidUserPasswordMinLength()
    {
        $user = new User('testcase@test.fr','Newpass',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    public function testNotValidUserPasswordMaxLength()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/Newpassword123456*/Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    public function testNotValidUserNoPassword()
    {
        $user = new User('testcase@test.fr','',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    public function testNotValidUserMinAge()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(12),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    public function testNotValidUserNoAge()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/',null,'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    public function testNotValidUserNoFirstname()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),null,'Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    public function testNotValidUserNoLastname()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),'test',null);
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }
}
