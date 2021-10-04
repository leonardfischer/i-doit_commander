<?php

namespace idoit\Module\Lfischer_commander\Task;

use idoit\Module\Lfischercommander\Model\Ci;
use isys_application;
use isys_component_database;

class Search extends AbstractTask
{
    /**
     * @inherit
     */
    public function applicable(string $input): bool
    {
        $keywords = '(' . implode('|', self::getKeywords()) . ')';
        $options = '(' . implode('|', self::getOptions()) . ')';

        $regex = "/{$keywords}\s+{$options}/i";

        return (bool) preg_match($regex, $input, $matches);
    }

    /**
     * @inherit
     */
    public function execute(string $input): string {
        $keywords = '(' . implode('|', self::getKeywords()) . ')';
        $options = '(' . implode('|', self::getOptions()) . ')';

        $regex = "/{$keywords}\s+{$options}/i";

        preg_match($regex, $input, $matches);

        return 'document.location.href = window.www_dir + "search?q=' . trim(trim($matches[2], '"\'')) . '"';
    }

    /**
     * @inherit
     */
    public function getName(): string
    {
        return 'Öffnet ein existierendes Objekt';
    }

    /**
     * @return string[]
     */
    private function getKeywords(): array
    {
        return [
            'find',
            'search',
            'finde',
            'suche',
        ];
    }

    /**
     * @return string[]
     */
    private function getOptions(): array
    {
        return [
            '".*?"',
        ];
    }
}
