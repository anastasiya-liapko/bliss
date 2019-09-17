<?php

namespace Tests\App\Controllers;

use App\Controllers\Error;
use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class ErrorTest.
 *
 * @package Tests\App\Controllers
 */
class ErrorTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new Error([], new Session(), new Request()));
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
        $controller = new Error([], new Session(new MockArraySessionStorage()), new Request());
        $this->assertInstanceOf(Response::class, $controller->indexAction());
    }
}
