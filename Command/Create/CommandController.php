<?php

declare(strict_types=1);

namespace librarianphp\Create;

use Minicli\Command\CommandController as MiniCliCommandController;
use Minicli\Input;

class CommandController extends MiniCliCommandController
{
    public function handle(): void
    {
        $input = new Input(' ');

        $this->info('Command: ');
        $command = $input->read();

        $this->createCommandFile($this->buildCommandPath($command), $command);
    }

    private function buildCommandPath(string $command): array
    {
        $commandsPath = realpath($this->config->app_path[0]);
        $commandArray = explode(' ', $command);
        $commandPartsCount = count($commandArray);
        if ($commandPartsCount > 2) {
            $this->error('Command name must be one or two words.');

            return [];
        }

        $commandPath = [];

        do {
            $commandPart = array_shift($commandArray);
            $commandPart = ucfirst(strtolower($commandPart));
            $commandPath[] = $commandPart;

            if (count($commandArray) === 0 && $commandPartsCount > 1) {
                break;
            }

            $dir = "{$commandsPath}/" . implode('/', $commandPath);
            if (! is_dir($dir)) {
                mkdir($dir);
            }
        } while (count($commandArray) > 0);

        return array_map(fn ($item) => ucfirst(strtolower($item)), $commandPath);
    }

    private function createCommandFile(array $commandPath, string $command): void
    {
        if ($commandPath === []) {
            return;
        }
        $commandsPath = realpath($this->config->app_path[0]);
        $commandName = count($commandPath) > 1 ? array_pop($commandPath) : 'Default';
        $commandClass = "{$commandName}Controller";
        $commandFilePath = realpath("{$commandsPath}/" . implode('/', $commandPath)) . "/{$commandClass}.php";

        if (file_exists($commandFilePath)) {
            $this->error("Command file already exists at {$commandFilePath}");

            return;
        }

        $commandNamespace = 'namespace App\Command' . '\\' . implode('\\', $commandPath);
        $commandFileContent = file_get_contents(__DIR__ . '/../../stubs/command.stub');
        $commandFileContent = str_replace(
            ['{{command_namespace}}', '{{command_class}}', '{{command_name}}'],
            [$commandNamespace, $commandClass, $command],
            $commandFileContent
        );

        file_put_contents($commandFilePath, $commandFileContent);

        $this->success("{$command} command created!");
    }
}
