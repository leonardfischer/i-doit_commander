<?php

namespace idoit\Module\Lfischer_commander\Task;

class CreateObject extends AbstractTask
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
        return 'console.log("TODO create new object");';
    }

    /**
     * @inherit
     */
    public function getName(): string
    {
        return 'Erstellt ein neues Objekt';
    }

    /**
     * @return string[]
     */
    private function getKeywords(): array
    {
        return [
            'create',
            'new',
            'neu(e|er|es)?',
        ];
    }

    /**
     * @return string[]
     */
    private function getOptions(): array
    {
        return ['object'];

        /*
        $result = Ci::instance($this->database)->getObjectTypes();

        while ($row = $result->get_row()) {
            // $options[] = strtr(['/' => ''], strtolower($this->language->get($row['title'])));
        }

        return $options;
        */
    }
}
