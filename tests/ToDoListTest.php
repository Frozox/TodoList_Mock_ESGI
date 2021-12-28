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

    //Test d'une todolist valide
    public function testValidToDoList(){
        $isValid = $this->validToDoList->isValid();
        $this->assertTrue($isValid);
    }

    //Associer todoliste à un utilisateur
    public function testValidToDoListToUser(){
        $user = $this->validUser->setToDoList($this->validToDoList);
        $this->assertIsObject($user);
    }

    //utilisateur a déja une todoliste
    public function testUserAlreadyHaveToDoList(){
        $this->expectException(\Exception::class);
        $tdl = new ToDoList("TodoList already");
        $this->validUser->setToDoList($this->validToDoList);
        $this->validUser->setToDoList($tdl);
    }

    /**** ITEMS ****/
    //création basique d'un item
    public function testSimpleItem(){
        $item = new Item("Item","Content",new \DateTimeImmutable("20-12-2021"));
        $this->assertIsObject($item);
    }

    //test de isValid sur un item valide
    public function testValidItem(){
        $item = new Item("Item","Content",new \DateTimeImmutable("20-12-2021"));
        $isValid = $item->isValid();
        $this->assertTrue($isValid);
    }

    //création d'un item invalide par le nom
    public function testNotValidItemName(){
        $this->expectError();
        $item = new Item(null,"Content",new \DateTimeImmutable("20-12-2021"));
    }

    //création d'un item invalide par le contenu
    public function testNotValidContent(){
        $this->expectError();
        $item = new Item("Item",null,new \DateTimeImmutable("20-12-2021"));
    }

    //création d'un item invalide par le contenu (taille)
    public function testNotValidContentLength(){
        $item = new Item("Item",random_bytes(1001),new \DateTimeImmutable("20-12-2021"));
        $isValid = $item->isValid();
        $this->assertFalse($isValid);
    }

    //création d'un item invalide par la date
    public function testNotValidDate(){
        $this->expectError();
        $item = new Item("Item","Content",null);
    }

    //création d'un item invalide par la Date de création
    public function testNotValid_DateTimeImmutable(){
        $this->expectException(\Exception::class);
        $date = new \DateTimeImmutable("not a valid date");
        $item = new Item("Item","Content",$date);
    }
    /**** FIN ITEMS ****/

    //Ajout d'un item valide à un todoliste
    public function testAddValidItem(){
        $item = new Item("Item","Content",new \DateTimeImmutable("20-12-2021"));
        $this->assertIsObject($this->validToDoList->addItem($item));
    }

    //Ajout de deux items totalement identique
    public function testAddSameItem(){
        $item = new Item("Item","Content",new \DateTimeImmutable("20-12-2021"));
        $this->validToDoList->addItem($item);
        $this->assertFalse($this->validToDoList->addItem($item));
    }

    //Ajout de deux items ayant le même nom
    public function testAddSameNameItem(){
        $item1 = new Item("Item","Content",new \DateTimeImmutable("20-12-2021"));
        $item2 = new Item("Item","Contenu de l'item 2",new \DateTimeImmutable("22-08-1999"));
        $this->validToDoList->addItem($item1);
        $this->assertFalse($this->validToDoList->addItem($item2));
    }

    //Ajout de plusieurs items différents
    public function testAddMultipleItem(){
        $item1 = new Item("Item1","Content",new \DateTimeImmutable("20-12-2021"));
        $item2 = new Item("Item2","Contenu de l'item 2",new \DateTimeImmutable("22-08-1999"));
        $this->validToDoList->addItem($item1);
        $this->assertIsObject($this->validToDoList->addItem($item2));
    }

    //Ajout de deux items avec un interval de date de création de -30min
    public function testAddTwoItemBefore30mins(){
        $item1 = new Item("Item1","Content",new \DateTimeImmutable("20-12-2021 00:00:00"));
        $item2 = new Item("Item2","Contenu de l'item 2",new \DateTimeImmutable("20-12-2021 00:29:00"));
        $this->validToDoList->addItem($item1);
        $this->assertFalse($this->validToDoList->addItem($item2));
    }

}
