<?php

namespace Tests\App\Models;

use App\Models\IntegrationPlugin;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class IntegrationPluginTest.
 *
 * @package Tests\App\Models
 */
class IntegrationPluginTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new IntegrationPlugin([]));
    }

    /**
     * Tests the getOrderby method.
     *
     * @return void
     */
    public function testGetOrderby(): void
    {
        /** @var IntegrationPlugin|MockObject $stub */
        $stub = $this->getMockBuilder(IntegrationPlugin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getOrderby());
    }

    /**
     * Tests the getUrl method.
     *
     * @return void
     */
    public function testGetUrl(): void
    {
        /** @var IntegrationPlugin|MockObject $stub */
        $stub = $this->getMockBuilder(IntegrationPlugin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getUrl());
    }

    /**
     * Tests the getImgUrl method.
     *
     * @return void
     */
    public function testGetImgUrl(): void
    {
        /** @var IntegrationPlugin|MockObject $stub */
        $stub = $this->getMockBuilder(IntegrationPlugin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getImgUrl());
    }

    /**
     * Tests the getName method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        /** @var IntegrationPlugin|MockObject $stub */
        $stub = $this->getMockBuilder(IntegrationPlugin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getName());
    }

    /**
     * Tests the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        /** @var IntegrationPlugin|MockObject $stub */
        $stub = $this->getMockBuilder(IntegrationPlugin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getId());
    }

    /**
     * Tests the getErrors method.
     *
     * @return void
     */
    public function testGetErrors(): void
    {
        /** @var IntegrationPlugin|MockObject $stub */
        $stub = $this->getMockBuilder(IntegrationPlugin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the create method.
     *
     * @return IntegrationPlugin $integration_plugin
     */
    public function testCreate(): IntegrationPlugin
    {
        $integration_plugin = new IntegrationPlugin($this->getConstructorData());
        $this->assertTrue($integration_plugin->create());

        return $integration_plugin;
    }

    /**
     * Tests the deleteById method.
     *
     * @param IntegrationPlugin $integration_plugin
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testDeleteById(IntegrationPlugin $integration_plugin)
    {
        $this->assertTrue(IntegrationPlugin::deleteById($integration_plugin->getId()));
    }

    /**
     * Tests the gerAll method.
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testGetAll(): void
    {
        $this->assertIsArray(IntegrationPlugin::getAll());
    }

    /**
     * Get the constructor data.
     *
     * @param array $data The data
     *
     * @return array The constructor data.
     */
    private function getConstructorData(array $data = []): array
    {
        return [
            'name'    => array_key_exists('name', $data) ? $data['name'] : 'Example',
            'img_url' => array_key_exists('img_url', $data) ? $data['img_url'] : 'http://example.com',
            'url'     => array_key_exists('url', $data) ? $data['url'] : 'http://example.com',
            'orderby' => array_key_exists('orderby', $data) ? $data['orderby'] : 0,
        ];
    }
}
