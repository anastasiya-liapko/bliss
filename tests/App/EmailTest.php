<?php

namespace Tests\App;

use App\Email;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailTest.
 *
 * @package Tests\App
 */
class EmailTest extends TestCase
{
    /**
     * Tests the sendAboutAdminConfirmedOrganizationDocuments method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testSendAboutAdminConfirmedOrganizationDocuments(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":0,"message":"Successfully sent email to test@mail.ru","timestamp":1555494504}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(Email::sendAboutAdminConfirmedOrganizationDocuments('test@mail.ru', 1, $handler));
    }

    /**
     * Tests the sendAuthData method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testSendAuthData(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":0,"message":"Successfully sent email to test@mail.ru","timestamp":1555494504}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(Email::sendAuthData('test@mail.ru', '123456', $handler));
    }

    /**
     * Tests the sendAboutOrganizationUploadedSignedDocuments method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testSendAboutOrganizationUploadedSignedDocuments(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":0,"message":"Successfully sent email to test@mail.ru","timestamp":1555494504}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(Email::sendAboutOrganizationUploadedSignedDocuments('test@mail.ru', 1, $handler));
    }

    /**
     * Tests the sendEmailAboutNewOrganization method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testSendAboutNewOrganization(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":0,"message":"Successfully sent email to test@mail.ru","timestamp":1555494504}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(Email::sendAboutNewOrganization('test@mail.ru', 1, 1, $handler));
    }

    /**
     * Tests the sendClientConfirmedLoan method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testSendClientConfirmedLoan(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":0,"message":"Successfully sent email to test@mail.ru","timestamp":1555494504}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(Email::sendClientConfirmedLoan(
            'test@mail.ru',
            1,
            'Петров Пётр Петрович',
            '71111111111',
            $handler
        ));
    }

    /**
     * Tests the sendOrderLink method.
     *
     * @return void
     * @throws \Exception
     */
    public function testSendOrderLink(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":0,"message":"Successfully sent email to test@mail.ru","timestamp":1555494504}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(Email::sendOrderLink(
            'test@mail.ru',
            'ИП Петров Пётр Петрович',
            'afa41e7cd87df67e1718c781bf613521',
            $handler
        ));
    }
}
