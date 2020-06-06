<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface as UniqueEmailSpec;
use App\Domain\User\User;
use App\Infrastructure\Share\Bus\Command\CommandHandlerInterface;

class SignUpHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private UniqueEmailSpec $uniqueEmailSpec;

    public function __construct(UserRepositoryInterface $userRepository, UniqueEmailSpec $uniqueEmailSpec)
    {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpec = $uniqueEmailSpec;
    }

    /**
     * @param SignUpCommand $command
     * @throws DateTimeException
     */
    public function __invoke(SignUpCommand $command): void
    {
        $user = User::create($command->uuid, $command->credentials, $this->uniqueEmailSpec);
        $this->userRepository->store($user);
    }
}
