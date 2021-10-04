<?php

namespace idoit\Module\Lfischer_commander\Task;

use idoit\Module\Lfischercommander\Model\Ci;

class Logout extends AbstractTask
{
    /**
     * @inherit
     */
    public function applicable(string $input): bool
    {
        return strtolower($input) === 'logout';
    }

    /**
     * @inherit
     */
    public function execute(string $input): string
    {
        return 'document.location.href = window.www_dir + "?logout=1"';
    }

    /**
     * @inherit
     */
    public function getName(): string
    {
        return 'Loggt den aktuellen Nutzer aus';
    }
}
