<?php

namespace App\Repository;

use App\Entity\PasswordRecoveryToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordRecoveryToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordRecoveryToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordRecoveryToken[]    findAll()
 * @method PasswordRecoveryToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordRecoveryTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordRecoveryToken::class);
    }
}
