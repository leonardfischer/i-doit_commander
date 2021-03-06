<?php

namespace idoit\Module\Lfischercommander\Controller;

use idoit\Module\Lfischer_commander\Task\TaskInterface;
use isys_format_json as JSON;
use isys_register as Register;

/**
 *
 */
class Ajax extends Main
{
    /**
     * @var array
     */
    private $response;

    /**
     * Pre method gets called by the framework.
     */
    public function pre()
    {
        header('Content-Type: application/json');

        $this->response = [
            'success' => true,
            'data'    => null,
            'message' => null
        ];
    }

    /**
     * @param Register $request
     *
     * @throws \Exception
     */
    public function force(Register $request)
    {
        // Get the query.
        $query = trim($request->get('POST')->get('query'));
        $taskClass = trim($request->get('POST')->get('class'));

        if (!class_exists($taskClass) || !is_a($taskClass, TaskInterface::class, true)) {
            $this->response['success'] = false;
            $this->response['message'] = 'Task "' . $taskClass . '" not found :(';

            return;
        }

        /** @var TaskInterface $taskInstance */
        $taskInstance = (new $taskClass($this->getDi()->get('database'), $this->getDi()->get('language')));

        $this->response['data'] = [
            'executed' => true,
            'name' => $taskInstance->getName(),
            'code' => $taskInstance->execute($query)
        ];
    }

    /**
     * @param Register $request
     *
     * @throws \Exception
     */
    public function query(Register $request)
    {
        // Get the query.
        $query = trim($request->get('POST')->get('query'));

        $this->response['data'] = $this->getDi()
            ->get('lfischer_commander')
            ->handleQuery($query);
    }

    /**
     * @param Register $request
     *
     * @throws \Exception
     */
    public function suggest(Register $request)
    {
        // Specific handling for i-doits 'Autocomplete' feature.
        header('Content-Type: text/html');

        // Get the query.
        $query = trim($request->get('POST')->get('query'));

        $tasks = [];
        $result = $this->getDi()
            ->get('lfischer_commander')
            ->handleQuery($query);

        if (isset($result['tasks']) && is_array($result['tasks']) && count($result['tasks'])) {
            $tasks = array_map(function ($task) {
                return '<li data-class="' . $task['class'] . '">' . $task['name'] . '</li>';
            }, $result['tasks']);
        }

        echo '<ul>' . implode('', $tasks) . '</ul>';
        die;
    }

    /**
     * Post method gets called by the framework.
     */
    public function post()
    {
        echo JSON::encode($this->response);
        die;
    }
}
