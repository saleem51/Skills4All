<?php

namespace App\Tests\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CarControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CarRepository $repository;
    private string $path = '/car/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Car::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Car index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'car[nbSeats]' => 'Testing',
            'car[nbDoors]' => 'Testing',
            'car[name]' => 'Testing',
            'car[cost]' => 'Testing',
            'car[slug]' => 'Testing',
            'car[category]' => 'Testing',
        ]);

        self::assertResponseRedirects('/car/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Car();
        $fixture->setNbSeats('My Title');
        $fixture->setNbDoors('My Title');
        $fixture->setName('My Title');
        $fixture->setCost('My Title');
        $fixture->setSlug('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Car');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Car();
        $fixture->setNbSeats('My Title');
        $fixture->setNbDoors('My Title');
        $fixture->setName('My Title');
        $fixture->setCost('My Title');
        $fixture->setSlug('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'car[nbSeats]' => 'Something New',
            'car[nbDoors]' => 'Something New',
            'car[name]' => 'Something New',
            'car[cost]' => 'Something New',
            'car[slug]' => 'Something New',
            'car[category]' => 'Something New',
        ]);

        self::assertResponseRedirects('/car/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNbSeats());
        self::assertSame('Something New', $fixture[0]->getNbDoors());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getCost());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getCategory());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Car();
        $fixture->setNbSeats('My Title');
        $fixture->setNbDoors('My Title');
        $fixture->setName('My Title');
        $fixture->setCost('My Title');
        $fixture->setSlug('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/car/');
    }
}
