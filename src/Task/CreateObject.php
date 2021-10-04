<?php

namespace idoit\Module\Lfischer_commander\Task;

use idoit\Module\Lfischercommander\Model\Ci;
use isys_helper_link;

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
        $keywords = '(' . implode('|', self::getKeywords()) . ')';
        $options = '(' . implode('|', self::getOptions()) . ')';

        $regex = "/{$keywords}\s+{$options}/i";

        preg_match($regex, $input, $matches);

        $objectType = trim(strtolower($matches[3]));

        $result = Ci::instance($this->database)->getObjectTypes();

        while ($row = $result->get_row()) {
            if ($objectType === str_replace('/', '\/', addslashes(strtolower($this->language->get($row['title']))))) {
                $url = isys_helper_link::create_url([
                    C__CMDB__GET__VIEWMODE => C__CMDB__VIEW__LIST_OBJECT,
                    C__CMDB__GET__OBJECTTYPE => $row['id']
                ]);

                return '$("isys_form").writeAttribute("action", "' . $url . '");' .
                    'document.isys_form.navMode.value = "' . C__NAVMODE__NEW . '";' .
                    'document.isys_form.submit();';
            }
        }

        return 'idoit.Notify.warning("' . $this->language->get('LC__MODULE__LFISCHER_COMMANDER__COULD_NOT_FIND_OBJ_TYPE_NAME', $objectType) . '", {life: 10});';
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
        $options = [];

        $result = Ci::instance($this->database)->getObjectTypes();

        while ($row = $result->get_row()) {
            $options[] = str_replace('/', '\/', addslashes(strtolower($this->language->get($row['title']))));
        }

        return $options;
    }
}
