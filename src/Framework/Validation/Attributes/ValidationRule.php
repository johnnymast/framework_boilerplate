<?php

declare(strict_types=1);

namespace App\Framework\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_ALL)]
class ValidationRule
{
    public function __construct(public $name)
    {
    }
}
