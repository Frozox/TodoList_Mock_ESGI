<?php

namespace App\Tests;

use App\Entity\Item;
use App\Entity\ToDoList;
use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ToDoListTest extends TestCase
{
    private User $validUser;
    private ToDoList $validToDoList;

    protected function setUp(): void
    {
        $this->validUser = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $this->validToDoList = new ToDoList("TodoList");
        parent::setUp();
    }

    //Je retest si l'utilisateur est bien valid avant tout autre test
    public function testValidUser(){
        $isValid = $this->validUser->isValid();
        $this->assertTrue($isValid);
    }

    public function testValidToDoList(){
        $isValid = $this->validToDoList->isValid();
        $this->assertTrue($isValid);
        //$this->validUser->
    }

    public function testValidToDoListToUser(){
        $user = $this->validUser->setToDoList($this->validToDoList);
        $this->assertIsObject($user);
    }

    public function testSimpleItem(){
        $item = new Item("Item","Content",new \DateTimeImmutable("20-12-2021"));
        $this->assertIsObject($item);
    }

    public function testValidItem(){
        $item = new Item("Item","Content",new \DateTimeImmutable("20-12-2021"));
        $isValid = $item->isValid();
        $this->assertTrue($isValid);
    }

    public function testAddValidItem(){
        $item = new Item("Item","Content",new \DateTimeImmutable("20-12-2021"));
        $this->assertIsObject($this->validToDoList->addItem($item));
    }
}
