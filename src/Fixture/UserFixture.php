<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Output\ConsoleOutput;

use function range;

class UserFixture extends Fixture
{
    private ObjectManager $manager;
    private ConsoleOutput $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    public function load(ObjectManager $manager): void
    {
        $this->output->writeln('Starting Fixture load');

        $this->manager = $manager;

        foreach (range(0, 100) as $i) {
            $this->loadUser($i);
        }

        $manager->flush();
    }

    public function loadUser(int $iteration): void
    {
        $account = new Account();
        $account
            ->setEmail("test{$iteration}@example.com")
            ->setPassword("test{$iteration}");

        $this->manager->persist($account);

        $this->output->writeln("Added user {$account->getEmail()} with password {$account->getPassword()}.");
    }
}
