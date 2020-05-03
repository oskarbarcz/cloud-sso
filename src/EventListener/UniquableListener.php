<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Uniquable;
use App\Service\RandomStringGenerator;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;

use function get_class;

class UniquableListener
{
    /**
     * @param LifecycleEventArgs $args
     * @throws Exception
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $uniquable = $args->getObject();

        if (!$uniquable instanceof Uniquable) {
            return;
        }

        $repository = $args->getObjectManager()->getRepository(get_class($uniquable));
        $keyset = RandomStringGenerator::LETTERS_SMALL.RandomStringGenerator::NUMBERS;


        do {
            $uid = RandomStringGenerator::generate($uniquable::UUID_LENGTH, $keyset);
        } while ($repository->count(['uuid' => $uid]) > 0);

        $uniquable->setUuid($uid);
    }
}
