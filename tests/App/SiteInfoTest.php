<?php

namespace Tests\App;

use App\SiteInfo;
use PHPUnit\Framework\TestCase;

/**
 * Class SiteInfoTest.
 *
 * @package Tests\App
 */
class SiteInfoTest extends TestCase
{
    /**
     * Tests the getRegisteringEmail method.
     *
     * @return void
     */
    public function testGetRegisteringEmail(): void
    {
        $this->assertIsString(SiteInfo::getRegisteringEmail());
    }

    /**
     * Tests the getCreditingEmail method.
     *
     * @return void
     */
    public function testGetCreditingEmail(): void
    {
        $this->assertIsString(SiteInfo::getCreditingEmail());
    }

    /**
     * Tests the getSchemeAndHttpHost method.
     *
     * @return void
     */
    public function testGetSchemeAndHttpHost(): void
    {
        $this->assertIsString(SiteInfo::getSchemeAndHttpHost());
    }

    /**
     * Tests the getDocumentRoot method.
     *
     * @return void
     */
    public function testGetDocumentRoot(): void
    {
        $this->assertIsString(SiteInfo::getDocumentRoot());
    }
}
