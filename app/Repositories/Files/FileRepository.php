<?php

namespace App\Repositories\Files;

use App\Entities\File;
use App\Repositories\Contracts\FileRepositoryContract;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class FileRepository
 * @package App\Repositories\Files
 */
class FileRepository extends BaseRepository implements FileRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return File::class;
    }

    public function findFile($id)
    {
        return $this->findByField('id', $id)->first();
    }

}
