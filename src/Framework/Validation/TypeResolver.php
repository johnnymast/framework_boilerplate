<?php

declare(strict_types=1);

namespace App\Framework\Validation;

use App\Framework\Validation\Attributes\ValidationRule;

class TypeResolver
{
    /**
     * Filter validation types defined by the given attribute.
     *
     * @param string $class Find Validation types in this class.
     * @param string $attr  The name of the attribute to find.
     *
     * @throws \ReflectionException
     * @return array
     */
    private function resolve(string $class, string $attr): array
    {
        $reflection = new \ReflectionClass($class);
        $callables = [];

        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes($attr);
            $instance = $reflection->newInstance();

            foreach ($attributes as $attribute) {
                $type = $attribute->newInstance();
                $callables[$type->name] = new ValidationContext($type->name, [$instance, $method->name]);
            }
        }

        return $callables;
    }

    /**
     * @throws \ReflectionException
     */
    public static function isValidClosure(callable $closure): bool
    {
        $function = new \ReflectionFunction($closure);
        $parameters = $function->getParameters();
        $returns = $function->getReturnType();

        if (!$returns or $returns->getName() !== "bool") {
            return false;
        }

        if (count($parameters) == 0) {
            return false;
        }

        foreach ($parameters as $index => $parameter) {
            if ($index == 0) {
                if ($parameter->getType()->getName() !== ValidationContext::class) {
                    return false;
                };
            }
        }

        return true;
    }

    /**
     * Read the validation types from a class.
     *
     * @param array $classes an array of classes to resolve attributes from/
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function resolveTypes(array $classes): array
    {
        $instance = new static();
        $result = [];

        if (count($classes) > 0) {
            foreach ($classes as $class) {
                $resolved = $instance->resolve($class, ValidationRule::class);
                if ($resolved) {
                    $result += $resolved;
                }
            }
        }

        return $result;
    }
}
