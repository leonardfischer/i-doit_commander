<?php

namespace idoit\Module\Lfischer_commander\Task;

use idoit\Module\Lfischercommander\Model\Ci;

class OpenAdministration extends AbstractTask
{
    /**
     * @inherit
     */
    public function applicable(string $input): bool
    {
        $keywords = '(' . implode('|', self::getKeywords()) . ')';

        $regex = "/{$keywords}/i";

        return (bool)preg_match($regex, $input, $matches);
    }

    /**
     * @inherit
     */
    public function execute(string $input): string
    {
        return 'document.location.href = window.www_dir + "?' . C__GET__MODULE_ID . '=' . C__MODULE__SYSTEM . '&what=system_settings"';
    }

    /**
     * @inherit
     */
    public function getName(): string
    {
        return 'Öffnet die Administration';
    }

    /**
     * @return string[]
     */
    private function getKeywords(): array
    {
        return [
            'admin(istration|istrator)?',
            'verwaltung',
        ];
    }
}
