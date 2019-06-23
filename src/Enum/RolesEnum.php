<?php


namespace App\Enum;


class RolesEnum
{
    const ROLE_CLIENT_KEY = "ROLE_CLIENT";
    const ROLE_RECIPIENT_KEY = "ROLE_RECIPIENT";
    const ROLE_ADMIN_KEY = "ROLE_ADMIN";

    const ROLE_CLIENT_VALUE = "client";
    const ROLE_RECIPIENT_VALUE = "prestataire";
    const ROLE_ADMIN_VALUE = "administrateur";

    public function getRoleEnum()
    {
        return [
            self::ROLE_CLIENT_KEY => self::ROLE_CLIENT_VALUE,
            self::ROLE_RECIPIENT_KEY => self::ROLE_RECIPIENT_VALUE,
            self::ROLE_ADMIN_KEY => self::ROLE_ADMIN_VALUE
        ];
    }
}
