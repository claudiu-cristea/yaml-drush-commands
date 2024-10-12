<?php

namespace Drush\YamlCommands\Drush;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class YamlCommand extends Command
{
    protected array $tasks = [];

    public function setTasks(array $tasks): self
    {
        $this->tasks = $tasks;
        return $this;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->tasks as $task) {
            exec($task);
        }
        return self::SUCCESS;
    }
}
