<?php

namespace Tests\App\Controllers;

use App\Controllers\Home;
use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class HomeTest.
 *
 * @package Tests\App\Controllers
 */
class HomeTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new Home([], new Session(), new Request()));
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
        $controller = new Home([], new Session(), new Request());
        $this->assertInstanceOf(Response::class, $controller->indexAction());
    }
}
