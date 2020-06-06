<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Fixture;

use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\User\Repository\UserStore;
use Assert\AssertionFailedException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Output\ConsoleOutput;

use function range;
use function sprintf;

class AccountFixture extends Fixture
{
    private ConsoleOutput $output;
    private UserStore $userStore;
    private UniqueEmailSpecificationInterface $emailSpecification;

    public function __construct(UserStore $userStore, UniqueEmailSpecificationInterface $emailSpecification)
    {
        $this->output = new ConsoleOutput();
        $this->userStore = $userStore;
        $this->emailSpecification = $emailSpecification;
    }

    /**
     * @param ObjectManager $manager
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public function load(ObjectManager $manager): void
    {
        $this->output->writeln('Starting Fixture load');

        foreach (range(0, 100) as $i) {
            $this->loadUser($i);
        }
    }

    /**
     * @param int $iteration
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public function loadUser(int $iteration): void
    {
        $raw = "password{$iteration}";
        $password = HashedPassword::encode($raw);
        $credentials = new Credentials(Email::fromString("test{$iteration}@test.com"), $password);


        $user = User::create(Uuid::uuid4(), $credentials, $this->emailSpecification);

        $log = sprintf(
            "Added user new user (email: \"%s\") with password \"%s\".",
            $user->email(),
            $raw
        );

        $this->userStore->store($user);
        $this->output->writeln($log);
    }
}
