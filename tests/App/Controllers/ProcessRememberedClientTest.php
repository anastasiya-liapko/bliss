<?php

namespace Tests\App\Controllers;

use App\Controllers\ProcessRememberedClient;
use App\Models\RememberedClient;
use Core\Controller;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class ProcessRememberedClientTest.
 *
 * @package Tests\App\Controllers
 */
class ProcessRememberedClientTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new ProcessRememberedClient([], new Session(), new Request()));
    }

    /**
     * Tests the getShop method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetShop(): void
    {
        $controller  = new ProcessRememberedClient([], new Session(new MockArraySessionStorage()), new Request());
        $test_method = static::getNotPublicMethod($controller, 'getRememberedClient');
        $this->assertFalse($test_method->invoke($controller, 'not-exist'));
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testIndexAction(): void
    {
        /** @var RememberedClient|MockObject $remembered_client_stub */
        $remembered_client_stub = $this->getMockBuilder(RememberedClient::class)
                                       ->setConstructorArgs([[]])
                                       ->onlyMethods(['getTokenExpiresAt'])
                                       ->getMock();
        $remembered_client_stub->method('getTokenExpiresAt')
                               ->willReturn('12.09.2019');

        $http_request = new Request();
        $http_request->query->set('token', 'test');

        /** @var ProcessRememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(ProcessRememberedClient::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), $http_request])
                     ->onlyMethods(['getRememberedClient'])
                     ->getMock();
        $stub->method('getRememberedClient')
             ->willReturn($remembered_client_stub);

        $this->assertInstanceOf(Response::class, $stub->indexAction());
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testIndexActionFirstException(): void
    {
        $controller = new ProcessRememberedClient([], new Session(new MockArraySessionStorage()), new Request());
        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('No route matched.');
        $controller->indexAction();
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testIndexActionSecondException(): void
    {
        $http_request = new Request();
        $http_request->query->set('token', 'test');

        /** @var ProcessRememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(ProcessRememberedClient::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), $http_request])
                     ->onlyMethods(['getRememberedClient'])
                     ->getMock();

        $stub->method('getRememberedClient')
             ->willReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('No route matched.');
        $stub->indexAction();
    }

    /**
     * Gets a not public method.
     *
     * @param $object
     * @param string $method_name The method name.
     *
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    protected static function getNotPublicMethod($object, string $method_name)
    {
        $class  = new ReflectionClass($object);
        $method = $class->getMethod($method_name);
        $method->setAccessible(true);

        return $method;
    }
}
