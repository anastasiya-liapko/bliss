<?php

namespace Tests\App\Controllers;

use App\Controllers\ProcessOrder;
use App\Models\Order;
use App\Models\Shop;
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
 * Class ProcessOrderTest.
 *
 * @package Tests\App\Controllers
 */
class ProcessOrderTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new ProcessOrder([], new Session(), new Request()));
    }

    /**
     * Tests the getShop method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetShop(): void
    {
        $controller  = new ProcessOrder([], new Session(new MockArraySessionStorage()), new Request());
        $test_method = static::getNotPublicMethod($controller, 'getShop');
        $this->assertFalse($test_method->invoke($controller, 0));
    }

    /**
     * Tests the getOrder method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetOrder(): void
    {
        $controller  = new ProcessOrder([], new Session(new MockArraySessionStorage()), new Request());
        $test_method = static::getNotPublicMethod($controller, 'getOrder');
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
        /** @var Order|MockObject $order_stub */
        $order_stub = $this->getMockBuilder(Order::class)
                           ->setConstructorArgs([[]])
                           ->onlyMethods(['getShopId', 'getOrderIdInShop', 'getOrderPrice', 'getGoods'])
                           ->getMock();
        $order_stub->method('getShopId')
                   ->willReturn(1);
        $order_stub->method('getOrderIdInShop')
                   ->willReturn('1');
        $order_stub->method('getOrderPrice')
                   ->willReturn(3000.00);
        $order_stub->method('getGoods')
                   ->willReturn(json_encode([
                       [
                           'name'          => 'Наушники внутриканальные Sony MDR-EX15LP Black',
                           'price'         => 3000,
                           'quantity'      => 1,
                           'is_returnable' => 1,
                       ],
                   ]));

        /** @var Shop|MockObject $shop_stub */
        $shop_stub = $this->getMockBuilder(Shop::class)
                          ->setConstructorArgs([[]])
                          ->onlyMethods(['getSecretKey'])
                          ->getMock();
        $shop_stub->method('getSecretKey')
                  ->willReturn('test');

        $http_request = new Request();
        $http_request->query->set('token', 'test');

        /** @var ProcessOrder|MockObject $stub */
        $stub = $this->getMockBuilder(ProcessOrder::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), $http_request])
                     ->onlyMethods(['getOrder', 'getShop'])
                     ->getMock();
        $stub->method('getOrder')
             ->willReturn($order_stub);
        $stub->method('getShop')
             ->willReturn($shop_stub);
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
        $controller = new ProcessOrder([], new Session(new MockArraySessionStorage()), new Request());
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

        /** @var ProcessOrder|MockObject $stub */
        $stub = $this->getMockBuilder(ProcessOrder::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), $http_request])
                     ->onlyMethods(['getOrder'])
                     ->getMock();

        $stub->method('getOrder')
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
