<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Provider\Fakecar as FakeCar;
use Liior\Faker\Prices;

class AppFixtures extends Fixture
{

    public function __construct(protected  SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new FakeCar($faker));

        for($c = 0 ; $c < 7 ; $c++)
        {
            $category = new Category();
            $category->setName($faker->vehicleBrand)
                     ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($i = 0; $i < 20; $i++) {

                $car = new Car();
                $car->setName($faker->vehicleModel)
                    ->setCost($faker->price($min = 12000, $max = 25000, false, false))
                    ->setNbDoors($faker->biasedNumberBetween($min = 3, $max = 5))
                    ->setNbSeats($faker->biasedNumberBetween($min = 4, $max = 7))
                    ->setSlug(strtolower($this->slugger->slug($car->getName())))
                    ->setCategory($category);


                $manager->persist($car);
            }
        }

        $manager->flush();
    }
}
