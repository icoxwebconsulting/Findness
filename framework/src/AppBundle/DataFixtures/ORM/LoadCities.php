<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\City;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Parser;

class LoadCities extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $parser = new Parser();
        $data = $parser->parse(file_get_contents(__DIR__ . '/../../Resources/DataFixtures/ORM/cities.yml'));
        $cities = $data["cities"];

        foreach ($cities as $state => $cityData) {
            foreach ($cityData as $id => $name) {
                $city = new City();
                $city->setId($id);
                $city->setName($name);
                $city->setState($this->getReference('state' . $state));

                $manager->persist($city);
            }
        }

        $manager->flush();
    }
}