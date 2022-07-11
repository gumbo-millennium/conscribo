<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Commands;

use Gumbo\Conscribo\ConscriboClient;
use Gumbo\Conscribo\Exceptions\ConscriboException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ListEntitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conscribo:list-entities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Produces a list of all entity types available in the Conscribo account';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ConscriboClient $client)
    {
        try {
            $fieldTypes = $client->runCommand(ConscriboClient::COMMAND_LIST_ENTITY_TYPES);
        } catch (ConscriboException $exception) {
            $this->line("Failed to fetch entity types: {$exception->getMessage()}");

            return Command::FAILURE;
        }

        $entities = Collection::make();

        foreach ($fieldTypes->get('entityTypes') as $entityType) {
            $entities[] = [
                $entityType['typeName'],
                $entityType['langSingular'],
                $entityType['langPlural'],
            ];
        }

        $this->line("Found {$entities->count()} entities.");

        $this->table(
            ['Type', 'Singular name', 'Plural name'],
            $entities
        );

        return Command::SUCCESS;
    }
}
