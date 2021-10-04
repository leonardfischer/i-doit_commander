<?php

namespace idoit\Module\Lfischer_commander\Task;

use idoit\Module\Lfischercommander\Model\Ci;

class OpenObject extends AbstractTask
{
    /**
     * @inherit
     */
    public function applicable(string $input): bool
    {
        $keywords = '(' . implode('|', self::getKeywords()) . ')';
        $options = '(' . implode('|', self::getOptions()) . ')';

        $regex = "/{$keywords}\s+{$options}/i";

        return (bool)preg_match($regex, $input, $matches);
    }

    /**
     * @inherit
     */
    public function execute(string $input): string
    {
        $keywords = '(' . implode('|', self::getKeywords()) . ')';
        $options = '(' . implode('|', self::getOptions()) . ')';

        $regex = "/{$keywords}\s+{$options}/i";

        preg_match($regex, $input, $matches);

        if (strpos($matches[3], '#') === 0) {
            $objectId = (int)substr($matches[3], 1);
        } else {
            $search = trim(trim($matches[3], '"\''));
            $objectId = Ci::instance($this->database)->findByObjectName($search);

            if (!$objectId) {
                return 'idoit.Notify.warning("' . $this->language->get('LC__MODULE__LFISCHER_COMMANDER__COULD_NOT_FIND_OBJ_NAME', $search) . '", {life: 10});';
            }
        }

        return 'document.location.href = window.www_dir + "?' . C__CMDB__GET__OBJECT . '=' . $objectId . '"';
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
            'open',
            'navigate',
            'go(to)?',
            'öffne',
            'navigiere',
            'gehe',
        ];
    }

    /**
     * @return string[]
     */
    private function getOptions(): array
    {
        return [
            '#(\d+)',
            '".*?"',
        ];
    }
}
