<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $password = "123456789";
        $password_encoded = $this->encoder->hashPassword($user,$password);

        $user
            ->setEmail("toto@gmail.com")
            ->setPassword($password_encoded)
            ->setFirstname("Toto")
            ->setLastname("Tata")
            ->setBirthdate(new \DateTime("01-01-1999"));
        // $product = new Product();
        $manager->persist($user);
        $manager->flush();
    }
}
