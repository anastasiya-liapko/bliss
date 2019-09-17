<?php

namespace Tests\App\Controllers\AdminPanel;

use App\Controllers\AdminPanel\AdminPanel;
use App\Controllers\AdminPanel\DocumentTemplates;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class DocumentTemplatesTest.
 *
 * @package Tests\App\Controllers\AdminPanel
 */
class DocumentTemplatesTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(AdminPanel::class, new DocumentTemplates([], new Session(), new Request()));
    }

    /**
     * Tests the downloadDocumentQuestionnaireForEntrepreneurAction method.
     *
     * @return void
     */
    public function testDownloadDocumentQuestionnaireForEntrepreneurAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'downloadDocumentQuestionnaireForEntrepreneurAction'
        ));
    }

    /**
     * Tests the downloadDocumentQuestionnaireForLlcAction method.
     *
     * @return void
     */
    public function testDownloadDocumentQuestionnaireForLlcAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'downloadDocumentQuestionnaireForLlcAction'
        ));
    }

    /**
     * Tests the downloadDocumentContractAction method.
     *
     * @return void
     */
    public function testDownloadDocumentContractAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'downloadDocumentContractAction'
        ));
    }

    /**
     * Tests the downloadDocumentJoiningApplicationForEntrepreneurAction method.
     *
     * @return void
     */
    public function testDownloadDocumentJoiningApplicationForEntrepreneurAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'downloadDocumentJoiningApplicationForEntrepreneurAction'
        ));
    }

    /**
     * Tests the downloadDocumentJoiningApplicationForLlc method.
     *
     * @return void
     */
    public function testDownloadDocumentJoiningApplicationForLlcAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'downloadDocumentJoiningApplicationForLlcAction'
        ));
    }

    /**
     * Tests the uploadDocumentQuestionnaireForEntrepreneurAction method.
     *
     * @return void
     */
    public function testUploadDocumentQuestionnaireForEntrepreneurAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'uploadDocumentQuestionnaireForEntrepreneurAction'
        ));
    }

    /**
     * Tests the uploadDocumentQuestionnaireForLlcAction method.
     *
     * @return void
     */
    public function testUploadDocumentQuestionnaireForLlcAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'uploadDocumentQuestionnaireForLlcAction'
        ));
    }

    /**
     * Tests the uploadDocumentContractAction method.
     *
     * @return void
     */
    public function testUploadDocumentContractAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'uploadDocumentContractAction'
        ));
    }

    /**
     * Tests the uploadDocumentJoiningApplicationForEntrepreneurAction method.
     *
     * @return void
     */
    public function testUploadDocumentJoiningApplicationForEntrepreneurAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'uploadDocumentJoiningApplicationForEntrepreneurAction'
        ));
    }

    /**
     * Tests the uploadDocumentJoiningApplicationForLlcAction method.
     *
     * @return void
     */
    public function testUploadDocumentJoiningApplicationForLlcAction(): void
    {
        $this->assertTrue(method_exists(
            DocumentTemplates::class,
            'uploadDocumentJoiningApplicationForLlcAction'
        ));
    }

    /**
     * Tests the uploadDocumentJoiningApplicationForLlcAction method.
     *
     * @return void
     */
    public function testValidateFile(): void
    {
        $this->assertTrue(method_exists(DocumentTemplates::class, 'validateFile'));
    }
}
