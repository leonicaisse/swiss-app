<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin.demo');
        $user->setPassword($this->encoder->encodePassword($user, 'admin.demo'));
        $user->setRoles([User::ROLES['1']]);
        $user->setEmail('admin@demo.fr');
        $user->setFirstname('Administrateur');
        $user->setLastname('Administrateur');
        $user->setPhone('+33333333333');
        $user->setService('Administrateur');
        $manager->persist($user);
        $manager->flush();
    }
}