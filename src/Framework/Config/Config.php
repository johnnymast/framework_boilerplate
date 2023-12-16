<?php

namespace App\Framework\Config;

class Config extends IterableArray
{
    /**
     * File the configuration file.
     *
     * @param array<string> $parts parts of the given key.
     *
     * @return \App\Framework\Config\ConfigFile
     */
    private function resolveFile(array $parts = []): ConfigFile
    {
        $result = [];
        $sections = [];
        $file = '';
        foreach ($parts as $part) {
            $file .= DIRECTORY_SEPARATOR;
            $file .= $part;

            $path = config_path($file . '.php');

            if (file_exists($path) === true && is_file($path) === true) {
                $result[] = $path;
            } else {
                $sections[] = $part;
            }
        }

        $result = array_reverse($result);

        return new ConfigFile(current($result), $sections);
    }

    /**
     * Load a configuration file.
     *
     * @param string $key The file to load.
     *
     * @return mixed
     */
    public function load(string $key = ''): mixed
    {
        $parts = explode('.', $key);
        $configFile = $this->resolveFile($parts);

        $data = include $configFile->getFile();

        $result = [];
        foreach ($configFile->getSections() as $section) {
            if (isset($data[$section]) === true) {
                $result = $data[$section];
            }
        }


        if (!is_array($result)) {
            return $result;
        }


        $this->setData($result);

        return $result;
    }
}