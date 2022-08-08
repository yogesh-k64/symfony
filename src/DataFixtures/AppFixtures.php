<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadTeam($manager);
        $this->loadPlayer($manager);
    }

    public function loadTeam (ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('SAG FC');
        $team->setSportId(1);

        $this->addReference('sag',$team);
        $manager->persist($team);

        $manager->flush();
    }
    public function loadPlayer (ObjectManager $manager)
    {
        $team = $this->getReference('sag');

        $player = new Player();
        $player->setFirstName('jadhav');
        $player->setLastName('R');
        $player->setPhoneNumber('9895989685');
        $player->addTeam($team);
        
        $manager->persist($player);

        $player2 = new Player();
        $player2->setFirstName('radhav');
        $player2->setLastName('R');
        $player2->setPhoneNumber('9895900685');
        $player2->addTeam($team);
        
        $manager->persist($player2);

        $manager->flush();
    }
}
