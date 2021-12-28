<?php

namespace App\Tests;

use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    //Création utilisateur Valide
    public function testValidUser()
    {
        $this->user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $this->user->isValid();
        $this->assertTrue($isValid);
    }

    //test utilisateur invalide par l'email
    public function testNotValidUserEmail()
    {
        $user = new User('testcase.fr','Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    //Création utilisateur invalide par le mot de passe (taille max)
    public function testNotValidUserPasswordMinLength()
    {
        $user = new User('testcase@test.fr','Newpass',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    //Création utilisateur invalide par le mot de passe (taille min)
    public function testNotValidUserPasswordMaxLength()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/Newpassword123456*/Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    //Création utilisateur invalide par le mot de passe (aucun)
    public function testNotValidUserNoPassword()
    {
        $user = new User('testcase@test.fr','',Carbon::now()->subYears(20),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    //Création utilisateur invalide par l'age (min)
    public function testNotValidUserMinAge()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(12),'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    //Création utilisateur invalide par l'age (aucun)
    public function testNotValidUserNoAge()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/',null,'Test','Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    //Création utilisateur invalide par le prénom (aucun)
    public function testNotValidUserNoFirstname()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),null,'Case');
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }

    //Création utilisateur invalide par le nom (aucun)
    public function testNotValidUserNoLastname()
    {
        $user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),'test',null);
        $isValid = $user->isValid();
        $this->assertFalse($isValid);
    }
}
