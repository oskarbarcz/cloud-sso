<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use function range;

class UserFixture extends Fixture
{
    private ObjectManager $manager;
    private ConsoleOutput $output;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->output = new ConsoleOutput();
        $this->userPasswordEncoder = $userPasswordEncoder;
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

        $rawPassword = "test{$iteration}";
        $encoded = $this->userPasswordEncoder->encodePassword($account, $rawPassword);

        $account
            ->setEmail("test{$iteration}@example.com")
            ->setPassword($encoded);

        $this->manager->persist($account);

        $log = sprintf(
            "Added user %s with password %s (raw: %s).",
            $account->getEmail(),
            $account->getPassword(),
            $rawPassword
        );

        $this->output->writeln($log);
    }
}
