<?php

declare(strict_types=1);

namespace Drush\YamlCommands\Drush\Commands;

use Drush\Attributes\Bootstrap;
use Drush\Boot\DrupalBootLevels;
use Drush\Commands\AutowireTrait;
use Drush\Commands\DrushCommands;
use Drush\Runtime\DependencyInjection;
use Drush\YamlCommands\Drush\YamlCommand;
use League\Container\DefinitionContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

#[Bootstrap(level: DrupalBootLevels::NONE)]
class YamlDrushCommands extends DrushCommands
{
    use AutowireTrait;

    private const string APP = 'application';
    private const string CONTAINER = 'container';

    public function __construct(
        #[Autowire(service: self::CONTAINER)]
        protected DefinitionContainerInterface $container,
    ) {
        parent::__construct();

        $commands = [];
        foreach ($this->discoverYamlCommandFiles() as $file) {
            $commands += Yaml::parse(file_get_contents($file))['commands'] ?? [];
        }

        foreach ($commands as $name => $definition) {
            $command = new YamlCommand($name);
            $command
                ->setAliases($definition['aliases'] ?? [])
                ->setDescription($definition['description'] ?? '')
                ->setHelp($definition['help'] ?? '')
                ->setTasks($definition['tasks'] ?? []);
            $this->container->get(self::APP)->add($command);
        }
    }

    /**
     * @return string[]
     */
    protected function discoverYamlCommandFiles(): array
    {
        $files = [];
        $relativePath = 'Drush/YamlCommands';

        $classLoader = $this->container->get(DependencyInjection::LOADER);
        foreach ($classLoader->getPrefixesPsr4() as $directories) {
            $directories = array_filter(
                array_map(
                    function (string $directory) use ($relativePath): string {
                        return "$directory/$relativePath";
                    },
                    $directories,
                ),
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
