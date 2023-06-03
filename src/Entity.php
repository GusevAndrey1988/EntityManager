<?php

declare(strict_types=1);

namespace Andrey\EntityManager;

interface Entity
{
    public function entityId(): ?string;
    public function isUpdated(): bool;
}