<?php

declare(strict_types=1);

namespace Drush\YamlCommands\Tests;

use Composer\InstalledVersions;
use Drush\TestTraits\DrushTestTrait;
use PHPUnit\Framework\TestCase;

class YamlDrushCommandsTest extends TestCase
{
    use DrushTestTrait;

    /**
     * @covers \Drush\YamlCommands\Drush\YamlCommand
     */
    public function testYamlCommands(): void
    {
        $this->drush('say-hello', [], ['help' => null]);
        $this->assertStringContainsString(
            'Greetings, creates a file and displays the current directory',
            $this->getOutput()
        );
        $this->assertStringContainsString('This command performs the following:', $this->getOutput());
        $this->assertStringContainsString('- Sends you greetings', $this->getOutput());
        $this->assertStringContainsString('- Creates a file.txt file', $this->getOutput());
        $this->assertStringContainsString('- Displays the current directory', $this->getOutput());

        $this->drush('sayh');
        $this->assertStringContainsString('Hello World!', $this->getOutput());
        $this->assertFileExists('/tmp/file.txt');
        $projectDir = realpath(InstalledVersions::getInstallPath('claudiu-cristea/yaml-drush-commands'));
        $this->assertStringContainsString($projectDir, $this->getOutput());
        $this->assertStringNotContainsString('Bye!', $this->getOutput());
    }
}
