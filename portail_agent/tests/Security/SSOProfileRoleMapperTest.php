<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Security\SSOProfileRoleMapper;
use PHPUnit\Framework\TestCase;

class SSOProfileRoleMapperTest extends TestCase
{
    public function testGetRolesWithLowercaseSupervisorProfile(): void
    {
        $ssoProfileRoleMapper = new SSOProfileRoleMapper();
        $roles = $ssoProfileRoleMapper->getRoles('b');

        $this->assertSame(['ROLE_SUPERVISOR'], $roles);
    }

    public function testGetRolesWithUppercaseSupervisorProfile(): void
    {
        $ssoProfileRoleMapper = new SSOProfileRoleMapper();
        $roles = $ssoProfileRoleMapper->getRoles('B');

        $this->assertSame(['ROLE_SUPERVISOR'], $roles);
    }

    public function testGetRolesWithLowercaseFsiProfile(): void
    {
        $ssoProfileRoleMapper = new SSOProfileRoleMapper();
        $roles = $ssoProfileRoleMapper->getRoles('a');

        $this->assertSame([], $roles);
    }

    public function testGetRolesWithUppercaseFsiProfile(): void
    {
        $ssoProfileRoleMapper = new SSOProfileRoleMapper();
        $roles = $ssoProfileRoleMapper->getRoles('A');

        $this->assertSame([], $roles);
    }
}
