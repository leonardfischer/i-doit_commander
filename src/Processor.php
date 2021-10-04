<?php

namespace idoit\Module\Lfischer_commander;

use idoit\Exception\JsonException;
use isys_component_database;
use isys_format_json as JSON;
use isys_module_lfischer_commander;

/**
 * Class Activate
 *
 * @package   idoit\Module\Lfischer_commander\Processor
 * @copyright lfischer
 * @license   
 */
abstract class Processor
{
    /**
     * @var isys_component_database
     */
    protected $database;

    /**
     * Activate constructor.
     *
     * @param isys_component_database $tenantDatabase
     */
    public function __construct(isys_component_database $tenantDatabase)
    {
        $this->database = $tenantDatabase;
    }

    /**
     * @param string $file
     *
     * @return array
     * @throws JsonException
     */
    protected function getDataFileContent(string $file): array
    {
        $filePath = isys_module_lfischer_commander::getPath() . '/data/' . $file;

        if (!file_exists($filePath)) {
            return [];
        }

        $rawData = file_get_contents($filePath);

        if (!JSON::is_json_array($rawData)) {
            return [];
        }

        return JSON::decode($rawData);
    }

    /**
     * @param string $target
     * @param string $source
     *
     * @return bool
     */
    protected function copyDataFiles(string $target, string $source): bool
    {
        $files = glob(isys_module_lfischer_commander::getPath() . '/data/' . trim($target, '/') . '/*');
        $source = rtrim($source, '/') . '/';

        if (\count($files) === 0) {
            return true;
        }

        if (!is_dir($source) || !is_writable($source)) {
            return false;
        }

        foreach ($files as $file) {
            copy($file, $source . \basename($file));
        }

        return true;
    }

    /**
     * @return mixed
     */
    abstract public function process();
}
