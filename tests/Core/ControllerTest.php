<?php

namespace Tests\Core;

use App\SiteInfo;
use Core\Controller;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ControllerTest.
 *
 * @package Tests\Core
 */
class ControllerTest extends TestCase
{
    /**
     * The stack.
     *
     * @var object
     */
    protected $stack;

    /**
     * Sets up.
     *
     * @return void
     */
    public function setUp(): void
    {
        global $http_request;

        $http_request = new Request();

        $this->stack = new class(['test' => 1], new Session(), $http_request) extends Controller {
            /**
             * Test action
             *
             * @return Response
             */
            public function testAction(): Response
            {
                return new Response('Test');
            }
        };
    }

    /**
     * Tests the sendBinaryFileResponse method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testSendBinaryFileResponse(): void
    {
        $test_method = static::getNotPublicMethod($this->stack, 'sendBinaryFileResponse');
        $this->assertInstanceOf(
            BinaryFileResponse::class,
            $test_method->invoke($this->stack, SiteInfo::getDocumentRoot() . '/public/assets/front/img/favicon.png')
        );
    }

    /**
     * Tests the sendJsonResponse method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testSendJsonResponse(): void
    {
        $test_method = static::getNotPublicMethod($this->stack, 'sendJsonResponse');
        $this->assertInstanceOf(JsonResponse::class, $test_method->invoke($this->stack));
    }

    /**
     * Tests the forbidNotXmlHttpRequest method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testForbidNotXmlHttpRequest(): void
    {
        /** @var Request $http_request */
        global $http_request;

        $http_request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $test_method = static::getNotPublicMethod($this->stack, 'forbidNotXmlHttpRequest');
        $test_method->invoke($this->stack);

        $http_request->headers->remove('X-Requested-With');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('No route matched.');

        $test_method->invoke($this->stack);
    }

    /**
     * Tests the redirect method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testRedirect(): void
    {
        /** @var Request $http_request */
        global $http_request;

        $http_request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $test_method = static::getNotPublicMethod($this->stack, 'redirect');
        $this->assertInstanceOf(JsonResponse::class, $test_method->invoke($this->stack, 'https://example.com'));

        $http_request->headers->remove('X-Requested-With');

        $this->assertInstanceOf(
            RedirectResponse::class,
            $test_method->invoke($this->stack, 'https://example.com')
        );
    }

    /**
     * Tests the render method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testRender(): void
    {
        $test_method = static::getNotPublicMethod($this->stack, 'render');
        $this->assertInstanceOf(Response::class, $test_method->invoke($this->stack, 'Home/index.twig'));
    }

    /**
     * Tests the getRouteParam method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetRouteParam(): void
    {
        $test_method = static::getNotPublicMethod($this->stack, 'getRouteParam');
        $this->assertEquals(1, $test_method->invoke($this->stack, 'test'));
        $this->assertEquals('', $test_method->invoke($this->stack, 'test2'));
    }

    /**
     * Tests the getAbsUrl method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetAbsUrl(): void
    {
        $test_method = static::getNotPublicMethod($this->stack, 'getAbsUrl');
        $this->assertEquals('http://:test', $test_method->invoke($this->stack, 'test'));
    }

    /**
     * Tests the after method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testBefore(): void
    {
        $test_method = static::getNotPublicMethod($this->stack, 'after');
        $this->assertNull($test_method->invoke($this->stack));
    }

    /**
     * Tests the before method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testAfter(): void
    {
        $test_method = static::getNotPublicMethod($this->stack, 'after');
        $this->assertNull($test_method->invoke($this->stack));
    }

    /**
     * Tests the __call method.
     *
     * @return void
     */
    public function testCall(): void
    {
        $this->expectOutputString('Test');

        call_user_func([$this->stack, 'test'], []);

        $this->expectException(Exception::class);

        call_user_func([$this->stack, 'notExistMethod']);
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
