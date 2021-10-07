<?php

namespace idoit\Module\Lfischer_commander\Task;

use isys_component_database;
use isys_component_template_language_manager;

interface TaskInterface
{
    /**
     * @param isys_component_database                  $database
     * @param isys_component_template_language_manager $language
     */
    public function __construct(isys_component_database $database, isys_component_template_language_manager $language);

    /**
     * @param string $input
     *
     * @return bool
     */
    public function applicable(string $input): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $input
     *
     * @return string
     */
    public function execute(string $input): string;
}
