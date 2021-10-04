<?php

namespace idoit\Module\Lfischer_commander\Task;

interface TaskInterface
{
    /**
     * @param string $input
     *
     * @return bool
     */
    public function applicable(string $input): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $input
     *
     * @return string
     */
    public function execute(string $input): string;
}
