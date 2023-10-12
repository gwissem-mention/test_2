<?php

declare(strict_types=1);

namespace App\Security;

class SSOProfileRoleMapper
{
    /**
     * @return array<string>
     */
    public function getRoles(string $profile): array
    {
        $roles = [];

        if (false !== stristr($profile, 'b')) { // If header contains 'b' or 'B', user is supervisor
            $roles[] = 'ROLE_SUPERVISOR';
        }

        return $roles;
    }
}
