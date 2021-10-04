<?php

namespace idoit\Module\Lfischer_commander\Task;

use isys_component_database;
use isys_component_template_language_manager;

abstract class AbstractTask implements TaskInterface
{
    /**
     * @var isys_component_database
     */
    protected $database;

    /**
     * @var isys_component_template_language_manager
     */
    protected $language;

    /**
     * @param isys_component_database $database
     */
    public function __construct(isys_component_database $database, isys_component_template_language_manager $language)
    {
        $this->database = $database;
        $this->language = $language;
    }
}
