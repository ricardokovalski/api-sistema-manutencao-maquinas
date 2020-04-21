<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PeacesRepositoryContract
 * @package App\Repositories\Contracts
 */
interface PeaceRepositoryContract extends RepositoryInterface
{
    public function findPiece($id);
}
