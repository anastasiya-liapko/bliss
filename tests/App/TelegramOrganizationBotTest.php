<?php

namespace Tests\App;

use App\TelegramOrganizationBot;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class TelegramOrganizationBotTest.
 *
 * @package Tests\App
 */
class TelegramOrganizationBotTest extends TestCase
{
    /**
     * Tests the addedMfiToShop method.
     *
     * @return void
     * @throws \Exception
     */
    public function testAddedMfiToShop(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_organization_bot = new TelegramOrganizationBot($handler);
        $telegram_organization_bot->addedMfiToShop('ИП «Петров П.П.»', 'Веббанкир');

        $this->assertTrue(true);
    }

    /**
     * Tests the organizationUploadedDocuments method.
     *
     * @return void
     * @throws \Exception
     */
    public function testOrganizationUploadedDocuments(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_organization_bot = new TelegramOrganizationBot($handler);
        $telegram_organization_bot->organizationUploadedDocuments('ИП «Петров П.П.»');

        $this->assertTrue(true);
    }

    /**
     * Tests the organizationDownloadTemplates method.
     *
     * @return void
     * @throws \Exception
     */
    public function testOrganizationDownloadTemplates(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_organization_bot = new TelegramOrganizationBot($handler);
        $telegram_organization_bot->organizationDownloadTemplates('ИП «Петров П.П.»');

        $this->assertTrue(true);
    }

    /**
     * Tests the organizationCreatedAccount method.
     *
     * @return void
     * @throws \Exception
     */
    public function testOrganizationCreatedAccount(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_organization_bot = new TelegramOrganizationBot($handler);
        $telegram_organization_bot->organizationCreatedAccount('ИП «Петров П.П.»');

        $this->assertTrue(true);
    }
}
