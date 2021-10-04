<?php

namespace idoit\Module\Lfischer_commander\Processor;

use idoit\Module\Lfischer_commander\Processor;
use isys_component_database;

/**
 * Class Install
 *
 * @package   idoit\Module\Lfischer_commander\Processor
 * @copyright lfischer
 * @license   
 */
class Install extends Processor
{
    /**
     * @var int
     */
    private $tenantId;

    /**
     * Activate constructor.
     *
     * @param isys_component_database $tenantDatabase
     * @param int                     $tenantId
     */
    public function __construct(isys_component_database $tenantDatabase, int $tenantId)
    {
        parent::__construct($tenantDatabase);

        $this->tenantId = $tenantId;
    }

    /**
     * @return mixed
     */
    public function process()
    {
    }
}
