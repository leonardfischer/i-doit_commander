<?php

namespace idoit\Module\Lfischer_commander\Processor;

use idoit\Module\Cmdb\Model\CiTypeCategoryAssigner;
use idoit\Module\Lfischer_commander\Processor;
use isys_application as Application;
use isys_cmdb_dao as CmdbDao;
use isys_cmdb_dao_dialog as DialogDao;
use isys_component_database;
use isys_tenantsettings as TenantSetting;

/**
 * Class Update
 *
 * @package   idoit\Module\Lfischer_commander\Processor
 * @copyright lfischer
 * @license
 */
class Update extends Processor
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
     * @throws \idoit\Exception\JsonException
     * @throws \isys_exception_dao
     * @throws \isys_exception_database
     */
    public function process()
    {
        // Nothing to do yet...
    }
}
