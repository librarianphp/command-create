<?php

declare(strict_types=1);

namespace librarianphp\Create;

use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $this->info('./librarian create [subcommand]', true);
        $this->info('Run "./librarian create content" to create a content file based on a template.');
    }
}
