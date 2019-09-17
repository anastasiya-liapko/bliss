<?php

namespace Tests\App\Controllers;

use App\Controllers\Result;
use App\Helper;
use App\Models\RememberedClient;
use App\SiteInfo;
use Core\Controller;
use Core\View;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class ResultTest.
 *
 * @package Tests\App\Controllers
 */
class ResultTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new Result([], new Session(), new Request()));
    }

    /**
     * Tests the getRememberedClient method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetRememberedClient(): void
    {
        $controller  = new Result([], new Session(new MockArraySessionStorage()), new Request());
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
        $request = new Request();
        $request->cookies->set('remembered_client', 'test');
        $controller  = new Result([], new Session(new MockArraySessionStorage()), $request);
        $test_method = static::getNotPublicMethod($controller, 'getRememberedClientToken');

        $this->assertEquals('test', $test_method->invoke($controller));

        $controller  = new Result([], new Session(new MockArraySessionStorage()), new Request());
        $test_method = static::getNotPublicMethod($controller, 'getRememberedClientToken');

        $this->assertEquals('', $test_method->invoke($controller));
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
        $request = new Request();
        $request->query->set('status', 'issued');
        $controller = new Result([], new Session(new MockArraySessionStorage()), $request);
        $content    = View::getTemplate('Result/index.twig', [
            'title'               => 'Результаты оплаты заказа',
            'body_class'          => 'body_result',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'message'             => 'Оплата прошла успешно.',
        ]);
        $result     = $controller->indexAction();
        $this->assertEquals($content, $result->getContent());

        $request = new Request();
        $request->query->set('status', 'issued_postponed');
        $controller = new Result([], new Session(new MockArraySessionStorage()), $request);
        $content    = View::getTemplate('Result/index.twig', [
            'title'               => 'Результаты оплаты заказа',
            'body_class'          => 'body_result',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'message'             => 'Оплата прошла успешно.',
        ]);
        $result     = $controller->indexAction();
        $this->assertEquals($content, $result->getContent());

        $request = new Request();
        $request->query->set('status', 'declined');
        $controller = new Result([], new Session(new MockArraySessionStorage()), $request);
        $content    = View::getTemplate('Result/index.twig', [
            'title'               => 'Результаты оплаты заказа',
            'body_class'          => 'body_result',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'message'             => 'Не удалось оплатить заказ.',
        ]);
        $result     = $controller->indexAction();
        $this->assertEquals($content, $result->getContent());

        $request = new Request();
        $request->query->set('status', 'canceled');
        $controller = new Result([], new Session(new MockArraySessionStorage()), $request);
        $content    = View::getTemplate('Result/index.twig', [
            'title'               => 'Результаты оплаты заказа',
            'body_class'          => 'body_result',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'message'             => 'Не удалось оплатить заказ.',
        ]);
        $result     = $controller->indexAction();
        $this->assertEquals($content, $result->getContent());

        $request = new Request();
        $request->query->set('status', 'manual');
        $controller = new Result([], new Session(new MockArraySessionStorage()), $request);
        $content    = View::getTemplate('Result/index.twig', [
            'title'               => 'Результаты оплаты заказа',
            'body_class'          => 'body_result',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'message'             => 'Не удалось оплатить заказ.',
        ]);
        $result     = $controller->indexAction();
        $this->assertEquals($content, $result->getContent());

        $request = new Request();
        $request->query->remove('status');
        $controller = new Result([], new Session(new MockArraySessionStorage()), $request);
        $content    = View::getTemplate('Result/index.twig', [
            'title'               => 'Результаты оплаты заказа',
            'body_class'          => 'body_result',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'message'             => 'Нет данных об оплате.',
        ]);
        $result     = $controller->indexAction();
        $this->assertEquals($content, $result->getContent());
    }

    /**
     * Tests the before method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testBefore(): void
    {
        /** @var RememberedClient|MockObject $remembered_client_stub */
        $remembered_client_stub = $this->getMockBuilder(RememberedClient::class)
                                       ->setConstructorArgs([[]])
                                       ->onlyMethods(['getIsVerified', 'isTokenExpired'])
                                       ->getMock();

        $remembered_client_stub->method('getIsVerified')
                               ->willReturn(1);
        $remembered_client_stub->method('isTokenExpired')
                               ->willReturn(false);

        /** @var Result|MockObject $stub */
        $stub = $this->getMockBuilder(Result::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), new Request()])
                     ->onlyMethods(['getRememberedClient'])
                     ->getMock();

        $stub->method('getRememberedClient')
             ->willReturn($remembered_client_stub);

        $test_method = static::getNotPublicMethod($stub, 'before');
        $this->assertTrue($test_method->invoke($stub));
    }

    /**
     * Tests the before method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testBeforeFirstException(): void
    {
        /** @var Result|MockObject $stub */
        $stub = $this->getMockBuilder(Result::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), new Request()])
                     ->onlyMethods(['getRememberedClient'])
                     ->getMock();

        $stub->method('getRememberedClient')
             ->willReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('No route matched.');

        $test_method = static::getNotPublicMethod($stub, 'before');
        $test_method->invoke($stub);
    }

    /**
     * Tests the before method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testBeforeSecondException(): void
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

        /** @var Result|MockObject $stub */
        $stub = $this->getMockBuilder(Result::class)
                     ->setConstructorArgs([[], new Session(new MockArraySessionStorage()), new Request()])
                     ->onlyMethods(['getRememberedClient'])
                     ->getMock();

        $stub->method('getRememberedClient')
             ->willReturn($remembered_client_stub);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('No route matched.');
        $test_method = static::getNotPublicMethod($stub, 'before');

        $test_method->invoke($stub);
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
