<?php

namespace Tests\Core;

use App\Helper;
use App\SiteInfo;
use Core\View;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class ViewTest.
 *
 * @package Tests\Core
 */
class ViewTest extends TestCase
{
    /**
     * Test the renderTemplate method.
     *
     * @param string $template The template.
     *
     * @depends testGetTemplate
     *
     * @return void
     * @throws Exception
     */
    public function testRenderTemplate(string $template): void
    {
        View::renderTemplate('Home/index.twig', [
            'title'               => SiteInfo::NAME,
            'body_class'          => 'body_home',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
        ]);

        $this->expectOutputString($template);
    }

    /**
     * Test the getTemplate method.
     *
     * @return string $template The template.
     * @throws Exception
     */
    public function testGetTemplate(): string
    {
        $template = View::getTemplate('Home/index.twig', [
            'title'               => SiteInfo::NAME,
            'body_class'          => 'body_home',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
        ]);

        $this->assertIsString($template);

        return $template;
    }
}
