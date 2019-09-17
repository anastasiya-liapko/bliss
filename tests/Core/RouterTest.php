<?php

namespace Tests\Core;

use App\Helper;
use App\SiteInfo;
use Core\Router;
use Core\View;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class RouterTest.
 *
 * @package Tests\Core
 */
class RouterTest extends TestCase
{
    /**
     * Tests the getNamespace method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testGetNamespace(): void
    {
        $router = new Router();
        $router->add('admin', ['namespace' => 'Admin']);
        $router->match('admin');

        $test_method = static::getNotPublicMethod($router, 'getNamespace');

        $this->assertEquals('App\\Controllers\\Admin\\', $test_method->invoke($router));
    }

    /**
     * Tests the removeQueryStringVariables method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testRemoveQueryStringVariables(): void
    {
        $router      = new Router();
        $test_method = static::getNotPublicMethod($router, 'removeQueryStringVariables');

        $this->assertEquals('', $test_method->invoke($router, 'http://bliss.local/posts/index?page=1'));
        $this->assertEquals(
            'http://bliss.local/posts/index',
            $test_method->invoke($router, 'http://bliss.local/posts/index')
        );
    }

    /**
     * Tests the convertToCamelCase method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testConvertToCamelCase(): void
    {
        $router      = new Router();
        $test_method = static::getNotPublicMethod($router, 'convertToCamelCase');
        $this->assertEquals('addNew', $test_method->invoke($router, 'Add-new'));
    }

    /**
     * Tests the convertToStudlyCaps method.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testConvertToStudlyCaps(): void
    {
        $router      = new Router();
        $test_method = static::getNotPublicMethod($router, 'convertToStudlyCaps');
        $this->assertEquals('PostAuthors', $test_method->invoke($router, 'post-authors'));
    }

    /**
     * Tests the dispatch method.
     *
     * @return void
     * @throws Exception
     */
    public function testDispatch(): void
    {
        global $session, $http_request;

        $session      = new Session();
        $http_request = new Request();

        $router = new Router();
        $router->add('', ['controller' => 'Home', 'action' => 'index']);
        $router->dispatch('');

        $this->expectOutputString(View::getTemplate('Home/index.twig', [
            'title'               => SiteInfo::NAME,
            'body_class'          => 'body_home',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
        ]));

        $this->expectException(Exception::class);
        $router->dispatch('not-exist');
    }

    /**
     * Tests the getParams method.
     *
     * @return void
     */
    public function testGetParams(): void
    {
        $router = new Router();
        $router->add('admin', ['namespace' => 'Admin', 'controller' => 'Admin', 'action' => 'index']);
        $router->match('admin');

        $this->assertEquals(
            ['namespace' => 'Admin', 'controller' => 'Admin', 'action' => 'index'],
            $router->getParams()
        );
    }

    /**
     * Tests the match method.
     *
     * @return void
     */
    public function testMatch(): void
    {
        $router = new Router();
        $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

        $this->assertFalse($router->match('not-exist'));
        $this->assertTrue($router->match('admin/home/index'));
    }

    /**
     * Tests the getRoutes method.
     *
     * @return void
     */
    public function testGetRoutes(): void
    {
        $router = new Router();

        $this->assertIsArray($router->getRoutes());
    }

    /**
     * Tests the add method.
     *
     * @return void
     */
    public function testAdd(): void
    {
        $router = new Router();

        $router->add('', ['controller' => 'Home', 'action' => 'index']);
        $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

        $this->assertNotEmpty($router->getRoutes());
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
