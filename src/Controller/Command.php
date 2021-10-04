<?php

namespace idoit\Module\Lfischercommander\Controller;

use idoit;
use idoit\Controller\Base;
use idoit\Module\Lfischer_commander\Task\TaskInterface;
use isys_application;
use isys_component_tree;
use isys_controller;
use isys_format_json as JSON;
use isys_module;
use isys_module_lfischer_commander;
use isys_register;

class Command extends Base implements isys_controller
{
    private $response;

    public function pre()
    {
        header('Content-Type: application/json');

        $this->response = [
            'success' => true,
            'data'    => null,
            'message' => null
        ];
    }

    public function dao(isys_application $p_application)
    {
        // TODO: Implement dao() method.
    }

    public function handle(isys_register $p_request, isys_application $p_application)
    {
        $availableTask = [];
        $database = $this->getDi()->get('database');
        $language = $this->getDi()->get('language');

        // Get the query.
        $query = trim($p_request->get('POST')->get('query'));

        // Find all runnable tasks.
        $tasks = glob(isys_module_lfischer_commander::getPath() . 'src/Task/*.php');

        foreach ($tasks as $task) {
            if (strpos($task, 'Interface') !== false || strpos($task, 'Abstract') !== false) {
                continue;
            }

            $className = 'idoit\\Module\\Lfischer_commander\\Task\\' . basename($task, '.php');

            if (class_exists($className)) {
                $taskInstance = new $className($database, $language);

                if ($taskInstance->applicable($query)) {
                    $availableTask[] = $taskInstance;
                }
            }
        }

        $this->response['data'] = array_map(function (TaskInterface $task) use ($query) {
            return [
                'name' => $task->getName(),
                'code' => $task->execute($query)
            ];
        }, $availableTask);
    }

    public function tree(isys_register $p_request, isys_application $p_application, isys_component_tree $p_tree)
    {
        // TODO: Implement tree() method.
    }

    public function __construct(isys_module $p_module) { }

    public function post()
    {
        echo JSON::encode($this->response);
        die;
    }
}
