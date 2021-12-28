<?php

namespace App\Tests;

use App\Entity\Item;
use App\Entity\ToDoList;
use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use App\Entity\EmailSenderService;
use Exception;

class EmailSenderServiceTest extends TestCase
{
    private EmailSenderService $emailSenderService;

    protected function setUp(): void
    {
        $this->emailSenderService = $this->getMockBuilder(EmailSenderService::class)
            ->onlyMethods(['sendEmail'])
            ->getMock();
        parent::setUp();
    }

    //Test envoi d'un mail après l'ajout de 8 éléments
    public function testSendEmail(): void
    {
        $toDoList = new ToDoList('test', $this->emailSenderService);
        $user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $user->setToDoList($toDoList);

        $this->emailSenderService->expects($this->once())
            ->method('sendEmail')
            ->willthrowException(new Exception('Il ne vous reste que 2 items à ajouter'));

        $this->expectException(Exception::class);
        for($i = 0; $i < 8; $i++) {
            $item = new Item("$i","Content",new \DateTimeImmutable("2$i-12-2021"));
            $toDoList->addItem($item);
        }
    }

    //Test AUCUN envoi de mail après l'ajout de 2 éléments
    public function testEmailNotSent(): void
    {
        $toDoList = new ToDoList('test', $this->emailSenderService);
        $user = new User('testcase@test.fr','Newpassword123456*/',Carbon::now()->subYears(20),'Test','Case');
        $user->setToDoList($toDoList);

        $this->emailSenderService->expects($this->never())
            ->method('sendEmail');

        for($i = 0; $i < 2; $i++) {
            $item = new Item("$i","Content",new \DateTimeImmutable("2$i-12-2021"));
            $toDoList->addItem($item);
        }
    }

}

