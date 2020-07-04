<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->productsProvider() as $product) {
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function productsProvider()
    {
        yield (new Product())->setTitle('Fallout')->setPrice(199);
        yield (new Product())->setTitle('Don’t Starve')->setPrice(299);
        yield (new Product())->setTitle('Baldur’s Gate')->setPrice(399);
        yield (new Product())->setTitle('Icewind Dale')->setPrice(499);
        yield (new Product())->setTitle('Bloodborne')->setPrice(599);
    }
}
