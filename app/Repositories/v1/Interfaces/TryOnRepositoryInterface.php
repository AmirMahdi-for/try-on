<?php

namespace TryOn\Repositories\v1\Interfaces;

interface TryOnRepositoryInterface
{
    public function tryOn(int $userId, array $parameters);
}
