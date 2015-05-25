<?php

namespace MusicBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MusicBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@smileyaudio.com');
        $user->setPlainPassword('pixie');
        $user->addRole('ROLE_USER');
        $user->addRole('ROLE_SONATA_ADMIN');
        $user->setExpired(false);
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();
    }
}