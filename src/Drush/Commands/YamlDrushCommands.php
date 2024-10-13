<?php

declare(strict_types=1);

namespace Drush\YamlCommands\Drush\Commands;

use Drush\Commands\DrushCommands;
use Drush\Runtime\DependencyInjection;
use Drush\YamlCommands\Drush\YamlCommand;
use Robo\Robo;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class YamlDrushCommands extends DrushCommands
{
    public function __construct()
    {
        parent::__construct();

        $commands = [];
        foreach ($this->discoverYamlCommandFiles() as $file) {
            $commands += Yaml::parse(file_get_contents($file))['commands'] ?? [];
        }

        $application = Robo::application();
        foreach ($commands as $name => $definition) {
            $command = new YamlCommand($name);
            $command
                ->setAliases($definition['aliases'] ?? [])
                ->setDescription($definition['description'] ?? '')
                ->setHelp($definition['help'] ?? '')
                ->setTasks($definition['tasks'] ?? []);
            $application->add($command);
        }
    }

    protected function discoverYamlCommandFiles(): array
    {
        $classLoader = Robo::getContainer()->get(DependencyInjection::LOADER);

        $namespace = 'Drush\YamlCommands';
        $relativePath = str_replace("\\", '/', trim($namespace, '\\'));

        $files = [];
        foreach ($classLoader->getPrefixesPsr4() as $directories) {
            $directories = array_filter(
                array_map(
                    function (string $directory) use ($relativePath): string {
                        return "$directory/$relativePath";
                    }, $directories),
                'is_dir',
            );

            if ($directories) {
                $found = (new Finder())->files()->name('/.*\.ya?ml$/')->in($directories);
                foreach ($found as $file) {
                    $files[] = $file->getRealPath();
                }
            }
        }

        return $files;
    }
}
