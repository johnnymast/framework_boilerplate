<?php

namespace App\Framework\Config;

class ConfigFile
{
    /**
     * @var string
     */
    protected string $file = '';

    /**
     * @var array<int, mixed>
     */
    protected array $sections = [];

    /**
     * @param string            $file     The configuration file.
     * @param array<int, mixed> $sections The sections the file.
     */
    public function __construct(string $file, array $sections = [])
    {
        $this->file = $file;
        $this->sections = $sections;
    }

    /**
     * Return the configuration file.
     *
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Return the configuration sections.
     *
     * @return array<int, mixed>
     */
    public function getSections(): array
    {
        return $this->sections;
    }
}