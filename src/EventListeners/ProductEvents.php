<?php

namespace App\EventListeners;


use App\Entity\Product;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class ProductEvents
{
    public function postUpdate(Product $product, LifecycleEventArgs $event)
    {
        return true;
    }

    public function prePersist(Product $product, LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Product) {
            return;
        }
        $this->setCreatedAt($entity);
        return true;
    }

    /**
     * @param Product $entity
     */
    private function setCreatedAt(Product $entity)
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
        return ['prePersist', 'postUpdate'];
    }

}
