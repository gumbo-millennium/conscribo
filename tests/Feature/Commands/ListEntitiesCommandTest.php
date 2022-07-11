<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Tests\Feature\Commands;

use Gumbo\Conscribo\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class ListEntitiesCommandTest extends TestCase
{
    /**
     * @before
     */
    public function configureFakeCredentialsBefore(): void
    {
        $this->afterApplicationCreated(fn () => Config::set([
            'conscribo.account' => 'test',
        ]));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_valid_fetch()
    {
        Http::fake([
            'https://secure.conscribo.nl/test/request.json' => Http::fakeSequence()
                ->pushFile(test_path('responses/auth-ok.json'))
                ->pushFile(test_path('responses/list-entity-fields-ok.json')),
        ])->preventStrayRequests();

        $this->artisan('conscribo:list-entities')
            ->assertSuccessful()
            ->expectsTable(['Type', 'Singular name', 'Plural name'], [
                [
                    'persoon',
                    'persoon',
                    'personen',
                ],[
                    'organisatie',
                    'organisatie',
                    'Organisaties',
                ],
            ])
            ->run();
    }
}
