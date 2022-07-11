<?php

declare(strict_types=1);

namespace Gumbo\Conscribo;

use DateTimeInterface;
use Generator;
use Gumbo\Conscribo\Objects\ConscriboEntity;
use Gumbo\Conscribo\Objects\ConscriboEntityDescription;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class ConscriboApi
{
    protected ConscriboClient $client;
    protected array $objectDefinitions;

    public function __construct(ConscriboClient $client)
    {
        $this->client = $client;
        $this->objectDefinitions = Config::get('conscribo.objects');

        // Validate objects
        foreach (['user', 'group'] as $requiredGroup) {
            throw_unless(
                Arr::has($this->objectDefinitions, $requiredGroup),
                InvalidArgumentException::class,
                "The Conscribo object defintions is missing the key [$requiredGroup]"
            );
        }
    }

    public function findUser(int $id): ?ConscriboEntity
    {
        return $this->searchUser([
            'id' => $id,
        ])->first();
    }

    /**
     * Find a set of users
     * @param array $params
     * @return Collection<ConscriboEntity>
     */
    public function searchUser(array $params): Collection
    {
        return $this->searchEntity($this->objectDefinitions['user'], $params);
    }

    /**
     * Search for an entity using the given description and parameters
     * @param ConscriboEntityDescription $entityDescription
     * @param array $params
     * @return Collection|ConscriboEntity[]
     * @throws InvalidArgumentException
     */
    public function searchEntity(ConscriboEntityDescription $entityDescription, array $params): Collection
    {
        if (! $entityDescription->get('resource', 'fields')) {
            throw new \InvalidArgumentException('Missing resource name and/or fields in entity description');
        }

        $result = $this->client->runCommand(ConscriboClient::COMMAND_LIST_ENTITIES, [
            'entityType' => $entityDescription->resource,
            'requestedFields' => $entityDescription->fields,
            'filters' => $this->buildSearchFilterArray($params),
        ]);

        $entities = Collection::make();
        foreach ($result->relations as $relation) {
            $entities->push(ConscriboEntity::make($relation));
        }

        return $entities;
    }

    protected function buildSearchFilterArray(array $filters): Generator
    {
        // Convert an associative array of filters to an array of arrays
        if (! array_is_list($filters)) {
            $filters = $this->convertAssociativeFiltersToArray($filters);
        }

        foreach ($filters as $index => $filter) {
            if (! is_array($filter) || ! count($filter) != 3) {
                throw new InvalidArgumentException("Filter on index {$index} must be an array of length 3");
            }

            // Map filter to the three items
            [$field, $operator, $value] = $filter;

            // Convert common values
            if (is_bool($value)) {
                $value = (int) $value;
            } elseif (is_null($value)) {
                $operator = '-';
                $value = '';
            }

            if ($operator == '<=') {
                if ($value instanceof DateTimeInterface) {
                    $value = ['stop' => $value->format('Y-m-d H:i:s')];
                } elseif (is_int($value)) {
                    $operator = '=';
                    $value = "<{$value}|{$value}";
                } else {
                    throw new InvalidArgumentException("Expected either dates or integers on $field for <= operator");
                }
            }

            if ($operator == '>=') {
                if ($value instanceof DateTimeInterface) {
                    $value = ['start' => $value->format('Y-m-d H:i:s')];
                } elseif (is_int($value)) {
                    $operator = '=';
                    $value = ">{$value}|{$value}";
                } else {
                    throw new InvalidArgumentException("Expected either dates or integers on $field for >= operator");
                }
            }

            if ($operator == '><') {
                throw_unless(is_array($value) && count($value) == 2, InvalidArgumentException::class, "Expected a two-value array for between operator on $field");
                [$start, $stop] = $value;

                if ($start instanceof DateTimeInterface && $stop instanceof DateTimeInterface) {
                    $value = ['start' => $start->format('Y-m-d H:i:s'), 'stop' => $stop->format('Y-m-d H:i:s')];
                } elseif (is_int($start) && is_int($stop)) {
                    $operator = '=';
                    $value = ">$start&<$stop";
                } else {
                    throw new InvalidArgumentException("Expected either dates or integers on $field");
                }
            }

            if (! is_scalar($value)) {
                throw new InvalidArgumentException("Failed to determine proper value for $field with operator $operator");
            }

            yield [
                'fieldName' => $field,
                'operator' => $operator,
                'value' => $value,
            ];
        }
    }

    private function convertAssociativeFiltersToArray(array $filters): Generator
    {
        foreach ($filters as $key => $value) {
            // Scalar values are usually an '='
            if (is_scalar($value) || is_null($value)) {
                yield [$key, '=', $value];

                continue;
            }

            if (! is_array($value)) {
                throw new InvalidArgumentException("Got invalid value for filter key [$key]");
            }

            if (count($value) !== 2 || ! array_is_list($value)) {
                throw new InvalidArgumentException("Expected two-key list for filter key [$key]");
            }

            if (
                ($value[0] instanceof DateTimeInterface && $value[1] instanceof DateTimeInterface) ||
                (is_int($value[0]) && is_int($value[1]))
            ) {
                yield [$key, '><', $value];

                continue;
            }

            throw new InvalidArgumentException("Expected either dates or integers on $key, or a single value.");
        }
    }
}
