<?php

namespace TryOn\Repositories\Interfaces;

interface TryOnRepositoryInterface
{
    public function tryOn(int $userId, array $parameters);
}
