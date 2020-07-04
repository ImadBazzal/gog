<?php


namespace Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiTestCase extends BaseApiTestCase
{
    protected function createUser(string $email, string $password, array $roles): User
    {
        $em = self::$container->get('doctrine.orm.entity_manager');

        $encoder = self::$container->get(UserPasswordEncoderInterface::class);

        $user = (new User());

        $user->setEmail($email)
            ->setRoles($roles)
            ->setPassword($encoder->encodePassword($user, $password));

        $em->persist($user);

        $em->flush();

        return $user;
    }

    protected function getToken(UserInterface $user): string
    {
        $jwtManager = self::$container->get('lexik_jwt_authentication.jwt_manager');

        return $jwtManager->create($user);
    }
}