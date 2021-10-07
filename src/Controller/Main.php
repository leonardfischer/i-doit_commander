<?php

namespace idoit\Module\Lfischercommander\Controller;

use idoit;
use idoit\Controller\Base;
use isys_application;
use isys_component_tree;
use isys_controller;
use isys_module;
use isys_register;

class Main extends Base implements isys_controller
{
    /**
     * @param isys_application $p_application
     *
     * @return idoit\Model\Dao\Base|void
     */
    public function dao(isys_application $p_application)
    {
        // Nothing to do.
    }

    /**
     * @param isys_register    $p_request
     * @param isys_application $p_application
     *
     * @return null
     */
    public function handle(isys_register $p_request, isys_application $p_application)
    {
        return null;
    }

    /**
     * @param isys_register       $p_request
     * @param isys_application    $p_application
     * @param isys_component_tree $p_tree
     *
     * @return idoit\Tree\Node|void
     */
    public function tree(isys_register $p_request, isys_application $p_application, isys_component_tree $p_tree)
    {
        // Nothing to do.
    }

    /**
     * @param isys_module $p_module
     */
    public function __construct(isys_module $p_module)
    {
        // Nothing to do.
    }
}
