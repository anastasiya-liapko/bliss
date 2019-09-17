<?php

namespace Tests\App\Controllers;

use App\Controllers\ProfileShop;
use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ProfileShopTest.
 *
 * @package Tests\App\Controllers
 */
class ProfileShopTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new ProfileShop([], new Session(), new Request()));
    }

    /**
     * Tests the maybeSendAboutOrganizationUploadedDocuments method.
     *
     * @return void
     */
    public function testMaybeSendAboutOrganizationUploadedDocuments(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'maybeSendAboutOrganizationUploadedDocuments'));
    }

    /**
     * Tests the maybeSendAboutOrganizationDownloadTemplates method.
     *
     * @return void
     */
    public function testMaybeSendAboutOrganizationDownloadTemplates(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'maybeSendAboutOrganizationDownloadTemplates'));
    }

    /**
     * Tests the maybeSendAboutOrganizationCreatedAccount method.
     *
     * @return void
     */
    public function testMaybeSendAboutOrganizationCreatedAccount(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'maybeSendAboutOrganizationCreatedAccount'));
    }

    /**
     * Tests the validateFormOnUploadDocuments method.
     *
     * @return void
     */
    public function testValidateFormOnUploadDocuments(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'validateFormOnUploadDocuments'));
    }

    /**
     * Tests the validateFormOnCreate method.
     *
     * @return void
     */
    public function testValidateFormOnCreate(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'validateFormOnCreate'));
    }

    /**
     * Tests the checkOrganization method.
     *
     * @return void
     */
    public function testCheckOrganization(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'checkOrganization'));
    }

    /**
     * Tests the findOrganization method.
     *
     * @return void
     */
    public function testFindOrganization(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'findOrganization'));
    }

    /**
     * Tests the deleteUserFromSession method.
     *
     * @return void
     */
    public function testDeleteUserFromSession(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'deleteUserFromSession'));
    }

    /**
     * Tests the deleteOrganizationIdFromSession method.
     *
     * @return void
     */
    public function testDeleteOrganizationIdFromSession(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'deleteOrganizationIdFromSession'));
    }

    /**
     * Tests the saveOrganizationIdToSession method.
     *
     * @return void
     */
    public function testSaveOrganizationIdToSession(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'saveOrganizationIdToSession'));
    }

    /**
     * Tests the createAdmin method.
     *
     * @return void
     */
    public function testCreateAdmin(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'createAdmin'));
    }

    /**
     * Tests the createShop method.
     *
     * @return void
     */
    public function testCreateShop(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'createShop'));
    }

    /**
     * Tests the createOrganization method.
     *
     * @return void
     */
    public function testCreateOrganization(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'createOrganization'));
    }

    /**
     * Tests the cleanAddressAction method.
     *
     * @return void
     */
    public function testCleanAddressAction(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'cleanAddressAction'));
    }

    /**
     * Tests the uploadDocumentsAction method.
     *
     * @return void
     */
    public function testUploadDocumentsAction(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'uploadDocumentsAction'));
    }

    /**
     * Tests the downloadDocumentsAction method.
     *
     * @return void
     */
    public function testDownloadDocumentsAction(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'downloadDocumentsAction'));
    }

    /**
     * Tests the createAccountAction method.
     *
     * @return void
     */
    public function testCreateAccountAction(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'createAccountAction'));
    }

    /**
     * Tests the secondStepAction method.
     *
     * @return void
     */
    public function testSecondStepAction(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'secondStepAction'));
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     */
    public function testIndexAction(): void
    {
        $this->assertTrue(method_exists(ProfileShop::class, 'indexAction'));
    }
}
