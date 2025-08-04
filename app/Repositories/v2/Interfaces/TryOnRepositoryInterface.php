<?php

namespace TryOn\Repositories\v2\Interfaces;

interface TryOnRepositoryInterface
{
    public function tryOn(int $userId, array $parameters);
}
