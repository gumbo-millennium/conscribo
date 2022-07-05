<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Commands;

use Illuminate\Console\Command;

class ConscriboApiCommand extends Command
{
    public $signature = 'conscribo-api';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
