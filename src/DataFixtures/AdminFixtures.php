<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
          $admin = new Admin();
          $admin->setEmail('admin@gmail.com');
          $admin->setRoles(['ROLE_ADMIN']);
          $admin->setPassword(
              $this->passwordHasher->hashPassword($admin, 'password')
          );
          $manager->persist($admin);
          $manager->flush();
          echo "Admin user created: admin@gmail.com | Password: password\n";
    }
}
