<?php

declare(strict_types=1);

namespace App\Framework\Validation;

class ValidationContext
{
    /**
     * Reference to the validator
     * class.
     *
     * @var Validator
     */
    protected Validator $validator;

    /**
     * The value to validate.
     *
     * @var mixed|string
     */
    public mixed $value = '';

    /**
     * The key in the target
     * array.
     *
     * @var string
     */
    public string $key = '';

    /**
     * Reference to the target
     * array.
     *
     * @var array
     */
    protected array $target = [];

    /**
     * Indicator to see if
     * the validation passed.
     *
     * @var bool
     */
    protected bool $passes = true;

    /**
     * ValidationContext constructor.
     *
     * @param string $name   The name of the validation type.
     * @param mixed  $method The method to call to validate the value.
     */
    public function __construct(public string $name, protected mixed $method)
    {
    }

    /**
     * Check if the key exists on the target
     * array.
     *
     * @return bool
     */
    public function keyExists(): bool
    {
        return isset($this->target[$this->key]);
    }

    /**
     * Returns true if the
     * test has failed.
     *
     * @return bool
     */
    public function fails(): bool
    {
        return ($this->passes === false);
    }

    /**
     * Returns true if the
     * test was successful.
     *
     * @return bool
     */
    public function passes(): bool
    {
        return $this->passes;
    }

    /**
     * Return the value of the
     * key in the target array.
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Return the name of the
     * key in the target array.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Add an error in the validator.
     *
     * @param string $error The error string.
     *
     * @return bool
     */
    public function addError(string $error): bool
    {
        if ($this->validator) {
            $this->validator->addError($this->key, $error);
        }
        return false;
    }

    /**
     * Run the test rule.
     *
     * @param string    $key       The key from the target array.
     * @param mixed     $value     The value from the target array.
     * @param array     $target    The target array.
     * @param Validator $validator The validator.
     *
     * @return $this
     */
    public function run(string $key, mixed $value, array $target, Validator $validator): ValidationContext
    {
        $this->validator = $validator;
        $this->key = $key;
        $this->value = $value;
        $this->target = $target;

        $this->passes = call_user_func($this->method, $this);
        return $this;
    }
}
