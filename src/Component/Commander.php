<?php

namespace idoit\Module\Lfischercommander\Component;

use idoit;
use idoit\Module\Lfischer_commander\Task\TaskInterface;
use isys_component_database;
use isys_component_template_language_manager;
use isys_module_lfischer_commander;

class Commander
{
    /**
     * @var isys_component_template_language_manager
     */
    private $language;

    /**
     * @var array
     */
    private $tasks = [];

    public function __construct(isys_component_database $database, isys_component_template_language_manager $language)
    {
        $this->language = $language;

        // Find all runnable tasks.
        $tasks = glob(isys_module_lfischer_commander::getPath() . 'src/Task/*.php');

        foreach ($tasks as $task) {
            if (strpos($task, 'Interface') !== false || strpos($task, 'Abstract') !== false) {
                continue;
            }

            $className = 'idoit\\Module\\Lfischer_commander\\Task\\' . basename($task, '.php');

            if (class_exists($className) && is_a($className, TaskInterface::class, true)) {
                $this->tasks[] = new $className($database, $language);
            }
        }
    }

    public function registerTask(TaskInterface $task): void
    {
        $this->tasks[] = $task;
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public function handleQuery(string $query): array
    {
        $applicableTasks = [];

        foreach ($this->tasks as $task) {
            if ($task->applicable($query)) {
                $applicableTasks[] = $task;
            }
        }

        if (count($applicableTasks) === 0) {
            return [
                'executed' => false,
                'message' => ' ### no matches'
            ];
        }

        // Exactly one match - execute!
        if (count($applicableTasks) === 1) {
            return [
                'executed' => true,
                'name' => $applicableTasks[0]->getName(),
                'code' => $applicableTasks[0]->execute($query)
            ];
        }

        // Multiple matches found, return a selection.
        return [
            'executed' => false,
            'message' => $this->language->get('LC__MODULE__LFISCHER_COMMANDER__DID_YOU_MEAN'),
            'query' => $query,
            'tasks' => array_map(function (TaskInterface $task) { return [
                'name' => $task->getName(),
                'class' => get_class($task)
            ]; }, $applicableTasks)
        ];
    }
}
