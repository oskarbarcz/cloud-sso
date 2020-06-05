<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Fixture;

use App\Entity\Account;
use App\Service\RandomStringGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Output\ConsoleOutput;

use function range;
use function sprintf;

class AccountFixture extends Fixture
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
            ->setName("Name{$iteration}")
            ->setSurname("Surname{$iteration}")
            ->setEmail("test{$iteration}@example.com")
            ->setUuid(RandomStringGenerator::generate(10))
            ->setPlainPassword("test{$iteration}")
            ->setPassword('$2y$12$8MwJlukVeeabOahyESsTgenwmFfadtBAfJfYM/.0TnT0HNFaq8m6K');

        $this->manager->persist($account);

        $log = sprintf(
            "Added user \"%s\" (email: \"%s\") with password \"%s\".",
            $account->getUsername(),
            $account->getEmail(),
            "test{$iteration}"
        );

        $this->output->writeln($log);
    }
}
