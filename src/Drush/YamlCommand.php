<?php

namespace Drush\YamlCommands\Drush;

use Drush\Boot\DrupalBootLevels;
use Drush\Drush;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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
            if (is_string($task)) {
                // Syntax sugar for shell commands.
                $task = ['task' => 'exec', 'exec' => $task];
            }
            $task += $this->taskDefaults();

            Drush::logger()->debug('YamlCommands: Executing ' . json_encode($task));

            // @todo Convert to a plugin system
            switch ($task['task']) {
                case 'exec':
                    $process = Process::fromShellCommandline($task['exec']);
                    break;
                default:
                    throw new \InvalidArgumentException("Unknown task type '{$task['task']}'");
            }

            $process->run();
            if ($process->getExitCode()) {
                $output->writeln(trim($process->getErrorOutput()));
                return self::FAILURE;
            }

            $result = trim($process->getOutput());
            if ($result && $task['output']) {
                $output->writeln(trim($process->getOutput()));
            }
        }
        return self::SUCCESS;
    }

    protected function taskDefaults(): array
    {
        return [
            'output' => true,
            // @todo Does the Drupal boostrap concept apply for such commands?
            'bootstrap' => DrupalBootLevels::NONE,
        ];
    }
}
