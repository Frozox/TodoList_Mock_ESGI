<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\ToDoList;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\UserFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Faker;

class ToDoListFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create("fr_FR");

        $user = $manager->getRepository(User::class)->findBy(['email'=>'toto@gmail.com'])[0];
        $todolist = new ToDoList();
        $todolist
            ->setName("TodoList Toto")
            ->setOwner($user);

        for ($i = 0; $i <= random_int(1,7); $i++){
            $todolist_item = new Item();
            $todolist_item
                ->setName("item$i")
                ->setContent($faker->sentence)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setToDoList($todolist);
            $manager->persist($todolist_item);
            //$manager->flush();
        }
        $manager->persist($todolist);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }
}
