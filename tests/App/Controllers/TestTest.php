<?php

namespace Tests\App\Controllers;

use App\Controllers\Test;
use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class TestTest.
 *
 * @package Tests\App\Controllers
 */
class TestTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new Test([], new Session(), new Request()));
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
        $controller = new Test([], new Session(new MockArraySessionStorage()), new Request());
        $this->assertInstanceOf(Response::class, $controller->indexAction());
    }
}
