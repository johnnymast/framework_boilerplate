<?php

declare(strict_types=1);

namespace App\Framework\Validation\ValidationTypes;

use App\Framework\Validation\Attributes\ValidationRule;
use App\Framework\Validation\ValidationContext;

class TypeDefinitions
{
    #[ValidationRule('boolean')]
    #[ValidationRule('bool')]
    public function isBoolean(ValidationContext $context): bool
    {
        return ($context->keyExists() && is_bool($context->getValue()))
            or $context->addError("Field {$context->getKey()} is not of type boolean.");
    }

    #[ValidationRule('string')]
    public function string(ValidationContext $context): bool
    {
        return ($context->keyExists() && is_string($context->getValue()))
            or $context->addError("Field {$context->getKey()} is not of type string.");
    }

    #[ValidationRule('integer')]
    #[ValidationRule('int')]
    public function integer(ValidationContext $context): bool
    {
        return ($context->keyExists() && is_integer($context->getValue()))
            or $context->addError("Field {$context->getKey()} is not of type integer.");
    }
}
