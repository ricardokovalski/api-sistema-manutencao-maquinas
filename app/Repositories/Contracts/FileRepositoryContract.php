<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FileRepositoryContract
 * @package App\Repositories\Contracts
 */
interface FileRepositoryContract extends RepositoryInterface
{
    public function findFile($id);
}
