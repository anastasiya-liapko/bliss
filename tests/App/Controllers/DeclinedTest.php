<?php

namespace Tests\App\Controllers;

use App\Controllers\Declined;
use App\Models\RememberedClient;
use App\Models\Request;
use App\Models\Shop;
use Core\Controller;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class DeclinedTest.
 *
 * @package Tests\App\Controllers
 */
class DeclinedTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new Declined([], new Session(), new HttpRequest()));
    }

    /**
     * Tests the getShop method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetShop(): void
    {
        $controller  = new Declined([], new Session(new MockArraySessionStorage()), new HttpRequest());
        $test_method = static::getNotPublicMethod($controller, 'getShop');
        $this->assertFalse($test_method->invoke($controller, 0));
    }

    /**
     * Tests the getRequest method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetRequest(): void
    {
        $controller  = new Declined([], new Session(new MockArraySessionStorage()), new HttpRequest());
        $test_method = static::getNotPublicMethod($controller, 'getRequest');
        $this->assertFalse($test_method->invoke($controller, 0, 0));
    }

    /**
     * Tests the getRememberedClient method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetRememberedClient(): void
    {
        $controller  = new Declined([], new Session(new MockArraySessionStorage()), new HttpRequest());
        $test_method = static::getNotPublicMethod($controller, 'getRememberedClient');
        $this->assertFalse($test_method->invoke($controller, 'not-exist'));
    }

    /**
     * Tests the getRememberedClientToken method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetRememberedClientToken(): void
    {
        $controller  = new Declined([], new Session(new MockArraySessionStorage()), new HttpRequest());
        $test_method = static::getNotPublicMethod($controller, 'getRememberedClientToken');
        $this->assertEquals('', $test_method->invoke($controller));

        $http_request = new HttpRequest();
        $http_request->cookies->set('remembered_client', 'test');
        $controller  = new Declined([], new Session(new MockArraySessionStorage()), $http_request);
        $test_method = static::getNotPublicMethod($controller, 'getRememberedClientToken');
        $this->assertEquals('test', $test_method->invoke($controller));
    }

    /**
     * Tests the getCallbackUrl method.
     *
     * @depends testBefore
     *
     * @param Declined|MockObject $stub
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetCallbackUrl($stub): void
    {
        $test_method = static::getNotPublicMethod($stub, 'getCallbackUrl');
        $this->assertIsString($test_method->invoke($stub, 'declined', '1', 'test', 'http://example.com', 0));
    }

    /**
     * Tests the before method.
     *
     * @return Declined|MockObject
     * @throws \ReflectionException
     */
    public function testBefore()
    {
        /** @var RememberedClient|MockObject $remembered_client_stub */
        $remembered_client_stub = $this->getMockBuilder(RememberedClient::class)
                                       ->setConstructorArgs([[]])
                                       ->onlyMethods([
                                           'getIsVerified',
                                           'isTokenExpired',
                                           'getShopId',
                                           'getOrderId',
                                           'getCallbackUrl'
                                       ])
                                       ->getMock();

        $remembered_client_stub->method('getIsVerified')
                               ->willReturn(1);
        $remembered_client_stub->method('isTokenExpired')
                               ->willReturn(false);
        $remembered_client_stub->method('getShopId')
                               ->willReturn(1);
        $remembered_client_stub->method('getOrderId')
                               ->willReturn('1');
        $remembered_client_stub->method('getCallbackUrl')
                               ->willReturn('http://example.com');

        /** @var Request|MockObject $stub_request */
        $stub_request = $this->getMockBuilder(Request::class)
                             ->setConstructorArgs([])
                             ->onlyMethods(['getStatus'])
                             ->getMock();

        $stub_request->method('getStatus')
                     ->willReturn('declined');

        /** @var Shop|MockObject $stub_shop */
        $stub_shop = $this->getMockBuilder(Shop::class)
                          ->setConstructorArgs([])
                          ->onlyMethods(['getSecretKey'])
                          ->getMock();

        $stub_shop->method('getSecretKey')
                  ->willReturn('test');

        /** @var Declined|MockObject $stub */
        $stub = $this->getMockBuilder(Declined::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), new HttpRequest()])
                     ->onlyMethods([
                         'getRememberedClientToken',
                         'getRememberedClient',
                         'getRequest',
                         'getShop'
                     ])
                     ->getMock();

        $stub->method('getRememberedClientToken')
             ->willReturn('test');
        $stub->method('getRememberedClient')
             ->willReturn($remembered_client_stub);
        $stub->method('getRequest')
             ->willReturn($stub_request);
        $stub->method('getShop')
             ->willReturn($stub_shop);

        $test_method = static::getNotPublicMethod($stub, 'before');
        $this->assertTrue($test_method->invoke($stub));

        return $stub;
    }

    /**
     * Tests the before method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testBeforeFirstError(): void
    {
        /** @var Declined|MockObject $stub */
        $stub = $this->getMockBuilder(Declined::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), new HttpRequest()])
                     ->onlyMethods(['getRememberedClientToken', 'getRememberedClient'])
                     ->getMock();

        $stub->method('getRememberedClientToken')
             ->willReturn('not-exist');
        $stub->method('getRememberedClient')
             ->willReturn(false);

        $test_method = static::getNotPublicMethod($stub, 'before');
        ob_start();
        $this->assertFalse($test_method->invoke($stub));
        ob_end_clean();
    }

    /**
     * Tests the before method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testBeforeSecondError(): void
    {
        /** @var RememberedClient|MockObject $remembered_client_stub */
        $remembered_client_stub = $this->getMockBuilder(RememberedClient::class)
                                       ->setConstructorArgs([[]])
                                       ->onlyMethods(['getIsVerified', 'isTokenExpired'])
                                       ->getMock();

        $remembered_client_stub->method('getIsVerified')
                               ->willReturn(0);
        $remembered_client_stub->method('isTokenExpired')
                               ->willReturn(false);

        /** @var Declined|MockObject $stub */
        $stub = $this->getMockBuilder(Declined::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), new HttpRequest()])
                     ->onlyMethods(['getRememberedClientToken', 'getRememberedClient'])
                     ->getMock();

        $stub->method('getRememberedClientToken')
             ->willReturn('not-exist');
        $stub->method('getRememberedClient')
             ->willReturn($remembered_client_stub);

        $test_method = static::getNotPublicMethod($stub, 'before');
        ob_start();
        $this->assertFalse($test_method->invoke($stub));
        ob_end_clean();

        /** @var RememberedClient|MockObject $remembered_client_stub */
        $remembered_client_stub = $this->getMockBuilder(RememberedClient::class)
                                       ->setConstructorArgs([[]])
                                       ->onlyMethods(['getIsVerified', 'isTokenExpired'])
                                       ->getMock();

        $remembered_client_stub->method('getIsVerified')
                               ->willReturn(1);
        $remembered_client_stub->method('isTokenExpired')
                               ->willReturn(true);

        /** @var Declined|MockObject $stub */
        $stub = $this->getMockBuilder(Declined::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), new HttpRequest()])
                     ->onlyMethods(['getRememberedClientToken', 'getRememberedClient'])
                     ->getMock();

        $stub->method('getRememberedClientToken')
             ->willReturn('not-exist');
        $stub->method('getRememberedClient')
             ->willReturn($remembered_client_stub);

        $test_method = static::getNotPublicMethod($stub, 'before');
        ob_start();
        $this->assertFalse($test_method->invoke($stub));
        ob_end_clean();
    }

    /**
     * Tests the before method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testBeforeThirdError(): void
    {
        /** @var RememberedClient|MockObject $remembered_client_stub */
        $remembered_client_stub = $this->getMockBuilder(RememberedClient::class)
                                       ->setConstructorArgs([[]])
                                       ->onlyMethods(['getIsVerified', 'isTokenExpired', 'getShopId', 'getOrderId'])
                                       ->getMock();

        $remembered_client_stub->method('getIsVerified')
                               ->willReturn(1);
        $remembered_client_stub->method('isTokenExpired')
                               ->willReturn(false);
        $remembered_client_stub->method('getShopId')
                               ->willReturn(1);
        $remembered_client_stub->method('getOrderId')
                               ->willReturn('1');

        /** @var Request|MockObject $stub_request */
        $stub_request = $this->getMockBuilder(Request::class)
                             ->setConstructorArgs([])
                             ->onlyMethods(['getStatus'])
                             ->getMock();

        $stub_request->method('getStatus')
                     ->willReturn('issued');

        /** @var Declined|MockObject $stub */
        $stub = $this->getMockBuilder(Declined::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), new HttpRequest()])
                     ->onlyMethods(['getRememberedClient', 'getRequest'])
                     ->getMock();

        $stub->method('getRememberedClient')
             ->willReturn($remembered_client_stub);
        $stub->method('getRequest')
             ->willReturn($stub_request);

        $test_method = static::getNotPublicMethod($stub, 'before');
        ob_start();
        $this->assertFalse($test_method->invoke($stub));
        ob_end_clean();
    }

    /**
     * Tests the indexAction method.
     *
     * @depends testBefore
     *
     * @param Declined|MockObject $stub
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testIndexAction($stub): void
    {
        $this->assertInstanceOf(Response::class, $stub->indexAction());
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
