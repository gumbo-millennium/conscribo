<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Commands;

use Gumbo\Conscribo\ConscriboClient;
use Gumbo\Conscribo\Exceptions\ConscriboException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ListEntityFieldsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conscribo:list-entity-fields {entity : Name of the entity to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Produces a list of all fields on the given entity';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ConscriboClient $client)
    {
        try {
            $fieldTypes = $client->runCommand(ConscriboClient::COMMAND_LIST_ENTITY_FIELDS, [
                'entityType' => $this->argument('entity'),
            ]);
        } catch (ConscriboException $exception) {
            $this->line("Failed to fetch entity fields: {$exception->getMessage()}");

            return Command::FAILURE;
        }

        $fields = Collection::make();

        foreach ($fieldTypes->get('fields') as $entityType) {
            $fields[] = [
                $entityType['fieldName'],
                $entityType['type'],
                $entityType['label'],
            ];
        }

        $this->line("Found {$fields->count()} fields on {$this->argument('entity')}.");

        $this->table(
            ['Field name', 'Type', 'Description'],
            $fields
        );

        return Command::SUCCESS;
    }
}
