<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\State;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Parser;

class LoadStates extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $parser = new Parser();
        $data = $parser->parse(file_get_contents(__DIR__ . '/../../Resources/DataFixtures/ORM/states.yml'));
        $states = $data["states"];

        foreach ($states as $id => $name) {
            $state = new State();
            $state->setId($id);
            $state->setName($name);

            $manager->persist($state);
            $this->addReference('state' . $id, $state);
        }

        $manager->flush();
    }
}