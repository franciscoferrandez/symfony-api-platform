<?php
declare(strict_types=1);

namespace App\Exception\Password;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PasswordException extends BadRequestException
{
    public static function invalidLength(): self
    {
        throw new self('Password length is invalid');
    }
}