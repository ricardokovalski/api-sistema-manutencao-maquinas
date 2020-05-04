<?php

namespace App\Services;

use App\Exceptions\AuditException;
use App\Repositories\Contracts\AuditRepositoryContract;
use Illuminate\Http\Response;
use OwenIt\Auditing\Resolvers\{UrlResolver, IpAddressResolver, UserAgentResolver};

class AuditService
{
    /**
     * @var AuditRepositoryContract
     */
    private $auditRepository;

    /**
     * AuditService constructor.
     * @param AuditRepositoryContract $auditRepository
     */
    public function __construct(AuditRepositoryContract $auditRepository)
    {
        $this->auditRepository = $auditRepository;
    }

    /**
     * @param array $body
     * @return bool
     * @throws \Exception
     */
    public function create(array $body)
    {
        if (! $body) {
            throw new AuditException('Necessário informar um corpo!', Response::HTTP_NOT_FOUND);
        }

        $body = array_merge($body, $this->makeDefaultSettings());

        if (! $this->auditRepository->create($body)) {
            throw new AuditException('Não foi possível salvar a auditoria!', Response::HTTP_NOT_FOUND);
        }

        return true;
    }

    /**
     * @return array
     */
    private function makeDefaultSettings()
    {
        return array(
            'url' => UrlResolver::resolve(),
            'ip_address' => IpAddressResolver::resolve(),
            'user_agent' => UserAgentResolver::resolve(),
        );
    }
}