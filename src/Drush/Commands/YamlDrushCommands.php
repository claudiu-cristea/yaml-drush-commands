<?php

declare(strict_types=1);

namespace Drush\YamlCommands\Drush\Commands;

use Consolidation\AnnotatedCommand\AnnotationData;
use Consolidation\AnnotatedCommand\Hooks\HookManager;
use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;
use Drush\YamlCommands\Drush\YamlCommand;
use Robo\Robo;
use Symfony\Component\Yaml\Yaml;

class YamlDrushCommands extends DrushCommands
{
    #[CLI\Hook(type: HookManager::INITIALIZE, target: '*')]
    public function discovery($input, AnnotationData $annotationData): void
    {
        $application = Robo::application();
        $commands = Yaml::parse(file_get_contents('../cmds.yml'));
        foreach ($commands as $name => $definition) {
            $command = new YamlCommand($name);
            $command
                ->setDescription($definition['description'] ?? '')
                ->setTasks($definition['tasks'] ?? []);
            $application->add($command);
        }
    }
}
