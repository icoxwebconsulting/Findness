<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\PostalCode;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Parser;

class LoadPostalCodes implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $parser = new Parser();
        $data = $parser->parse(file_get_contents(__DIR__ . '/../../Resources/DataFixtures/ORM/postalCodes.yml'));
        $postalCodes = $data["postal_codes"];

        foreach ($postalCodes as $code) {
            $postalCode = new PostalCode();
            $postalCode->setId($code);

            $manager->persist($postalCode);
        }

        $manager->flush();
    }
}