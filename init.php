<?php
/**
 * i-doit
 *
 * Add-on lfischer_commander init.php
 *
 * @package     lfischer_commander add-on
 * @copyright   lfischer
 * @license
 */

use idoit\Psr4AutoloaderClass;

if (isys_module_manager::instance()->is_active('lfischer_commander')) {
    Psr4AutoloaderClass::factory()->addNamespace('idoit\Module\Lfischer_commander', __DIR__ . '/src/');
    Psr4AutoloaderClass::factory()->addNamespace('idoit\Module\Lfischercommander', __DIR__ . '/src/');

    $template = isys_application::instance()->container->get('template');

    $template->appendInlineJavascript($template->fetch(__DIR__ . '/assets/js/observer.js'));

    isys_application::instance()->container->get('signals')
        ->connect('mod.css.attachStylesheet', function () {
            return __DIR__ . '/assets/css/commander.css';
        });
}
