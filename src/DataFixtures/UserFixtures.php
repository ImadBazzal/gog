<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin->setEmail('admin@admin.com');

        $admin->setPassword($this->encoder->encodePassword($admin, 'admin'));

        $admin->setRoles([User::ROLE_ADMIN]);

        $manager->persist($admin);

        $client = new User();

        $client->setEmail('client@client.com');

        $client->setPassword($this->encoder->encodePassword($client, 'client'));

        $client->setRoles([User::ROLE_USER]);

        $manager->persist($client);

        $manager->flush();
    }
}
