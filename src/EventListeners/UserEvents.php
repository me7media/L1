<?php

namespace App\EventListeners;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserEvents
{
    /**
     * @param User $user
     * @param LifecycleEventArgs $args
     */
    public function prePersist(User $user, LifecycleEventArgs $args)
    {
//        $entity = $args->getEntity();
//        if (!$entity instanceof User) {
//            return;
//        }
//        $this->setCreatedAt($entity);
        return true;
    }

    /**
     * @param User $entity
     */
    private function setCreatedAt(User $entity)
    {
        $entity->setCreatedAt((new DateTime)->now());
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }
}
