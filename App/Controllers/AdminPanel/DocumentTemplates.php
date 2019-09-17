<?php

namespace App\Controllers\AdminPanel;

use App\FileUploader;
use App\Models\Organization;
use App\SiteInfo;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DocumentTemplates.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\AdminPanel
 */
class DocumentTemplates extends AdminPanel
{
    /**
     * Download the questionnaire for an entrepreneur.
     *
     * @return Response
     */
    public function downloadDocumentQuestionnaireForEntrepreneurAction(): Response
    {
        return $this->redirect(
            $this->getAbsUrl(Organization::getTemplateUrl('anketa_fz_115_dlya_ip'))
        );
    }

    /**
     * Download the questionnaire for a llc.
     *
     * @return Response
     */
    public function downloadDocumentQuestionnaireForLlcAction(): Response
    {
        return $this->redirect(
            $this->getAbsUrl(Organization::getTemplateUrl('anketa_fz_115_dlya_ooo'))
        );
    }

    /**
     * Download the contract.
     *
     * @return Response
     */
    public function downloadDocumentContractAction(): Response
    {
        return $this->redirect(
            $this->getAbsUrl(Organization::getTemplateUrl('soglashenie'))
        );
    }

    /**
     * Download the joining application for an entrepreneur.
     *
     * @return Response
     */
    public function downloadDocumentJoiningApplicationForEntrepreneurAction(): Response
    {
        return $this->redirect(
            $this->getAbsUrl(Organization::getTemplateUrl('zayavlenie_o_prisoedinenii_dlya_ip'))
        );
    }

    /**
     * Download the joining application for a llc.
     *
     * @return Response
     */
    public function downloadDocumentJoiningApplicationForLlcAction(): Response
    {
        return $this->redirect(
            $this->getAbsUrl(Organization::getTemplateUrl('zayavlenie_o_prisoedinenii_dlya_ooo'))
        );
    }

    /**
     * Upload the questionnaire for an entrepreneur (Ajax).
     *
     * @return Response
     * @throws \Exception
     */
    public function uploadDocumentQuestionnaireForEntrepreneurAction(): Response
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateFile('document_questionnaire_fl_115_for_entrepreneur')) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $file_uploader = new FileUploader(
            SiteInfo::getDocumentRoot() . '/public/documents/edited-templates'
        );

        $file_uploader->upload(
            $this->http_request->files->get('document_questionnaire_fl_115_for_entrepreneur'),
            'anketa_fz_115_dlya_ip'
        );

        return $this->sendJsonResponse([
            'data' => [
                'success' => true,
            ],
        ]);
    }

    /**
     * Upload the questionnaire for a llc (Ajax).
     *
     * @return Response
     * @throws \Exception
     */
    public function uploadDocumentQuestionnaireForLlcAction(): Response
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateFile('document_questionnaire_fl_115_for_llc')) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $file_uploader = new FileUploader(
            SiteInfo::getDocumentRoot() . '/public/documents/edited-templates'
        );

        $file_uploader->upload(
            $this->http_request->files->get('document_questionnaire_fl_115_for_llc'),
            'anketa_fz_115_dlya_ooo'
        );

        return $this->sendJsonResponse([
            'data' => [
                'success' => true,
            ],
        ]);
    }

    /**
     * Upload the contract (Ajax).
     *
     * @return Response
     * @throws \Exception
     */
    public function uploadDocumentContractAction(): Response
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateFile('document_contract')) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $file_uploader = new FileUploader(
            SiteInfo::getDocumentRoot() . '/public/documents/edited-templates'
        );

        $file_uploader->upload(
            $this->http_request->files->get('document_contract'),
            'soglashenie'
        );

        return $this->sendJsonResponse([
            'data' => [
                'success' => true,
            ],
        ]);
    }

    /**
     * Upload the joining application for an entrepreneur (Ajax).
     *
     * @return Response
     * @throws \Exception
     */
    public function uploadDocumentJoiningApplicationForEntrepreneurAction(): Response
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateFile('document_joining_application_for_entrepreneur')) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $file_uploader = new FileUploader(
            SiteInfo::getDocumentRoot() . '/public/documents/edited-templates'
        );

        $file_uploader->upload(
            $this->http_request->files->get('document_joining_application_for_entrepreneur'),
            'zayavlenie_o_prisoedinenii_dlya_ip'
        );

        return $this->sendJsonResponse([
            'data' => [
                'success' => true,
            ],
        ]);
    }

    /**
     * Upload the joining application for a llc (Ajax).
     *
     * @return Response
     * @throws \Exception
     */
    public function uploadDocumentJoiningApplicationForLlcAction(): Response
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateFile('document_joining_application_for_llc')) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $file_uploader = new FileUploader(
            SiteInfo::getDocumentRoot() . '/public/documents/edited-templates'
        );

        $file_uploader->upload(
            $this->http_request->files->get('document_joining_application_for_llc'),
            'zayavlenie_o_prisoedinenii_dlya_ooo'
        );

        return $this->sendJsonResponse([
            'data' => [
                'success' => true,
            ],
        ]);
    }

    /**
     * Validates the file.
     *
     * @param string $name
     *
     * @return bool
     */
    private function validateFile(string $name): bool
    {
        $validator = new Validator;

        // TODO figure out how to replace $_FILES with Symfony\Component\HttpFoundation.
        $validation = $validator->make($_FILES, [
            $name => 'required|uploaded_file:0,30M,pdf',
        ]);

        $validation->validate();

        return $validation->fails();
    }
}
