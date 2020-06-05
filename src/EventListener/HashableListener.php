<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Hashable;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Hashes password on entity changes
 */
class HashableListener
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $hashable = $args->getObject();
        dump($args->getObject());

        if (!$hashable instanceof Hashable) {
            return;
        }

        $this->hash($hashable);
    }

    public function hash(Hashable $hashable): void
    {
        $plain = $hashable->getPlainPassword();
        $encoded = $this->userPasswordEncoder->encodePassword($hashable, $plain);

        $hashable->setPassword($encoded);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $hashable = $args->getObject();

        if (!$hashable instanceof Hashable) {
            return;
        }

        if ($hashable->getPassword() === null) {
            $this->hash($hashable);
        }
    }
}
