<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        

        
            $team = new Team();
            $team->setName('chennai FC');
            $team->setSportId(1);
            
            $manager->persist($team);

            $team2 = new Team();
            $team2->setName('delhi FC');
            $team2->setSportId(1);

            $manager->persist($team2);


            $manager->flush();
    }
}
