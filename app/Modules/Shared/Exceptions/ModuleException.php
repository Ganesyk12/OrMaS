<?php

namespace Modules\Shared\Exceptions;

use Exception;

class ModuleException extends Exception
{
    public static function notFound(string $module, string $identifier): self
    {
        return new self(sprintf('%s dengan identifier "%s" tidak ditemukan.', $module, $identifier));
    }

    public static function alreadyExists(string $module, string $field, string $value): self
    {
        return new self(sprintf('%s dengan %s "%s" sudah tersedia.', $module, $field, $value));
    }

    public static function invalidAction(string $action, string $reason): self
    {
        return new self(sprintf('Aksi "%s" tidak valid: %s', $action, $reason));
    }
}
