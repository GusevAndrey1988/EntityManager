<?php

namespace Andrey\EntityManager;

class EntityManager
{
    private $loadedEntities = [];
    private $deletedEntities = [];

    /**
     * @throw EntityManagerException
     */
    public function add(Entity $entity): void
    {
        $entityClass = get_class($entity);
        $entityObjectHash = spl_object_hash($entity);

        if ($this->isLoaded($entityClass, $entityObjectHash)) {
            return;
        }

        if ($this->isDeleted($entityClass, $entityObjectHash)) {
            throw new EntityManagerException('Entity is deleted.');
        }

        $this->loadedEntities[$entityClass][$entityObjectHash] = $entity;
    }

    public function delete(Entity $entity): void
    {
        $entityClass = get_class($entity);
        $entityObjectHash = spl_object_hash($entity);

        if ($this->isDeleted($entityClass, $entityObjectHash)) {
            return;
        }

        if ($this->isLoaded($entityClass, $entityObjectHash)) {
            if (!is_null($entity->entityId())) {
                $this->deletedEntities[$entityClass][$entityObjectHash] = $entity;
            }
            unset($this->loadedEntities[$entityClass][$entityObjectHash]);
        }
    }

    private function isLoaded(string $classString, string $objectHash): bool
    {
        if (!isset($this->loadedEntities[$classString])) {
            return false;
        }
        return isset($this->loadedEntities[$classString][$objectHash]);
    }

    private function isDeleted(string $classString, string $objectHash): bool
    {
        if (!isset($this->deletedEntities[$classString])) {
            return false;
        }
        return isset($this->deletedEntities[$classString][$objectHash]);
    }
}
