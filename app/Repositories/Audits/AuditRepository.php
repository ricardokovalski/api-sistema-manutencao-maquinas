<?php

namespace App\Repositories\Audits;

use App\Repositories\Contracts\AuditRepositoryContract;
use OwenIt\Auditing\Models\Audit;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AuditRepository
 * @package App\Repositories\Audit
 */
class AuditRepository extends BaseRepository implements AuditRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Audit::class;
    }
}
