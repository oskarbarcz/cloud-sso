<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Query\Mysql;

use App\Domain\Shared\Query\Exception\NotFoundException;
use App\Domain\User\Repository\CheckUserByEmailInterface;
use App\Domain\User\Repository\GetUserCredentialsByEmailInterface;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use App\Infrastructure\User\Query\Projections\UserView;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Ramsey\Uuid\UuidInterface;

final class MysqlUserReadModelRepository extends MysqlRepository implements CheckUserByEmailInterface,
                                                                            GetUserCredentialsByEmailInterface
{
    /**
     * @param UuidInterface $uuid
     * @return UserView
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function oneByUuid(UuidInterface $uuid): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $uuid->getBytes());

        return $this->oneOrException($qb);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function existsEmail(Email $email): ?UuidInterface
    {
        $userId = $this->repository
            ->createQueryBuilder('user')
            ->select('user.uuid')
            ->where('user.credentials.email = :email')
            ->setParameter('email', (string)$email)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getOneOrNullResult();

        return $userId['uuid'] ?? null;
    }

    public function add(UserView $userRead): void
    {
        $this->register($userRead);
    }

    /**
     * @param Email $email
     * @return array
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function getCredentialsByEmail(Email $email): array
    {
        $user = $this->oneByEmail($email);

        return [
            $user->uuid(),
            $user->email(),
            $user->hashedPassword(),
        ];
    }

    /**
     * @param Email $email
     * @return UserView
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function oneByEmail(Email $email): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());

        return $this->oneOrException($qb);
    }

    protected function getClass(): string
    {
        return UserView::class;
    }
}