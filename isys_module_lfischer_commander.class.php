<?php

use idoit\AddOn\ActivatableInterface;
use idoit\AddOn\ExtensionProviderInterface;
use idoit\AddOn\InstallableInterface;
use idoit\Exception\JsonException;
use idoit\Module\Lfischer_commander\CommanderExtension;
use idoit\Module\Lfischer_commander\Processor;
use idoit\Module\Lfischer_commander\Processor\Activate;
use idoit\Module\Lfischer_commander\Processor\Deactivate;
use idoit\Module\Lfischer_commander\Processor\Install;
use idoit\Module\Lfischer_commander\Processor\Uninstall;
use idoit\Module\Lfischer_commander\Processor\Update;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * i-doit
 *
 * Add-on lfischer_commander module class.
 *
 * @package   lfischer_commander
 * @copyright Leonard Fischer
 * @license
 */
class isys_module_lfischer_commander extends isys_module implements ActivatableInterface, ExtensionProviderInterface, InstallableInterface
{
    // Define, if this module shall be displayed in the named menus.
    const DISPLAY_IN_MAIN_MENU   = false;
    const DISPLAY_IN_SYSTEM_MENU = false;
    const MAIN_MENU_REWRITE_LINK = false;

    /**
     * @var bool
     */
    protected static $m_licenced = true;

    /**
     * @return void
     */
    public function start()
    {
        if (!self::is_licenced()) {
            global $g_error;

            $g_error = $message = $this->language->get('LC__LICENCE__NO_MODULE_LICENCE', [$this->language->get('Commander')]);
            isys_notify::error($message, ['sticky' => true]);

            return;
        }
    }

    /**
     * Initializes the module.
     *
     * @param isys_module_request $p_req
     */
    public function init(isys_module_request $p_req)
    {
    }

    /**
     * Returns the module's container extension.
     *
     * @return ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new CommanderExtension();
    }

    /**
     * Checks if a add-on is installed.
     *
     * @return int|bool
     */
    public static function isInstalled()
    {
        return isys_module_manager::instance()
            ->is_installed('lfischer_commander');
    }

    /**
     * Basic installation process for all mandators.
     *
     * @param isys_component_database $tenantDatabase
     * @param isys_component_database $systemDatabase
     * @param int                     $moduleId
     * @param string                  $type
     * @param int                     $tenantId
     *
     * @return bool
     * @throws JsonException
     * @throws isys_exception_dao
     * @throws isys_exception_database
     */
    public static function install($tenantDatabase, $systemDatabase, $moduleId, $type, $tenantId)
    {
        // Dummy autoloader for the admin-center.
        if (!class_exists(Processor::class)) {
            include_once __DIR__ . '/src/Processor.php';
        }

        if ($type === 'install') {
            if (!class_exists(Install::class)) {
                include_once __DIR__ . '/src/Processor/Install.php';
            }

            (new Install($tenantDatabase, (int)$tenantId))->process();
        }

        if (!class_exists(Update::class)) {
            include_once __DIR__ . '/src/Processor/Update.php';
        }

        (new Update($tenantDatabase, (int)$tenantId))->process();

        return true;
    }

    /**
     * Uninstall add-on for all mandators.
     *
     * @param isys_component_database $tenantDatabase
     *
     * @return boolean
     * @throws JsonException
     * @throws isys_exception_dao
     */
    public static function uninstall($tenantDatabase)
    {
        // Dummy autoloader for the admin-center.
        if (!class_exists(Processor::class)) {
            include_once __DIR__ . '/src/Processor.php';
        }

        if (!class_exists(Uninstall::class)) {
            include_once __DIR__ . '/src/Processor/Uninstall.php';
        }

        (new Uninstall($tenantDatabase))->process();

        return true;
    }

    /**
     * Checks if a add-on is active.
     *
     * @return integer|bool
     */
    public static function isActive()
    {
        return isys_module_manager::instance()
            ->is_installed('lfischer_commander', true);
    }

    /**
     * Method that is called after clicking "activate" in admin center for specific mandator.
     *
     * @param isys_component_database $tenantDatabase
     *
     * @return boolean
     * @throws JsonException
     * @throws isys_exception_dao
     */
    public static function activate($tenantDatabase)
    {
        // Dummy autoloader for the admin-center.
        if (!class_exists(Processor::class)) {
            include_once __DIR__ . '/src/Processor.php';
        }

        if (!class_exists(Activate::class)) {
            include_once __DIR__ . '/src/Processor/Activate.php';
        }

        (new Activate($tenantDatabase))->process();

        return true;
    }

    /**
     * Method that is called after clicking "deactivate" in admin center for specific mandator.
     *
     * @param isys_component_database $tenantDatabase
     *
     * @return boolean
     * @throws isys_exception_dao
     * @throws JsonException
     */
    public static function deactivate($tenantDatabase)
    {
        // Dummy autoloader for the admin-center.
        if (!class_exists(Processor::class)) {
            include_once __DIR__ . '/src/Processor.php';
        }

        if (!class_exists(Deactivate::class)) {
            include_once __DIR__ . '/src/Processor/Deactivate.php';
        }

        (new Deactivate($tenantDatabase))->process();

        return true;
    }
}
