<?php

namespace App\Services;

use App\Repositories\Contracts\AuditRepositoryContract;
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

    public function create(array $body)
    {
        $body = array_merge($body, $this->makeDefaultSettings());

        $this->auditRepository->create($body);

        return true;
    }

    private function makeDefaultSettings()
    {
        return array(
            'url' => UrlResolver::resolve(),
            'ip_address' => IpAddressResolver::resolve(),
            'user_agent' => UserAgentResolver::resolve(),
        );
    }
}