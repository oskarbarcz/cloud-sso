<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByEmail;

use App\Domain\Shared\Query\Exception\NotFoundException;
use App\Infrastructure\Share\Bus\Query\Item;
use App\Infrastructure\Share\Bus\Query\QueryHandlerInterface;
use App\Infrastructure\User\Query\Mysql\MysqlUserReadModelRepository;
use Doctrine\ORM\NonUniqueResultException;

class FindByEmailHandler implements QueryHandlerInterface
{
    private MysqlUserReadModelRepository $repository;

    public function __construct(MysqlUserReadModelRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindByEmailQuery $query
     * @return Item
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(FindByEmailQuery $query): Item
    {
        $userView = $this->repository->oneByEmail($query->email);

        return new Item($userView);
    }
}