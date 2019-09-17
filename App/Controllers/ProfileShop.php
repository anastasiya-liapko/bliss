<?php

namespace App\Controllers;

use App\Config;
use App\Dadata;
use App\DateRule;
use App\Email;
use App\Helper;
use App\Models\Organization;
use App\Models\Shop;
use App\Models\ShopAdmin;
use App\PlainRule;
use App\SiteInfo;
use App\TelegramOrganizationBot;
use Core\Controller;
use Exception;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

/**
 * Class ProfileShop.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers
 */
class ProfileShop extends Controller
{
    /**
     * Errors.
     *
     * @var array
     */
    private $errors;

    /**
     * The organization.
     *
     * @var Organization
     */
    private $organization;

    /**
     * The shop.
     *
     * @var Shop
     */
    private $shop;

    /**
     * The shop admin.
     *
     * @var ShopAdmin
     */
    private $shop_admin;

    /**
     * Finds the organization.
     *
     * @return void
     */
    public function before(): void
    {
        parent::before();

        $this->findOrganization();
    }

    /**
     * Shows the index page.
     *
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(): Response
    {
        if (! empty($this->organization)) {
            return $this->redirect($this->getAbsUrl('/profile-shop/second-step'));
        }

        return $this->render('ProfileShop/index.twig', [
            'title'               => 'Анкета магазина',
            'body_class'          => 'body_profile-shop',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'form_action'         => $this->getAbsUrl('/profile-shop/create-account'),
            'categories'          => Organization::getOrganizationCategories(),
        ]);
    }

    /**
     * Shows the second step page.
     *
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws Exception
     */
    public function secondStepAction(): Response
    {
        $this->deleteUserFromSession();

        if (empty($this->organization)) {
            throw new Exception('Forbidden.', 403);
        }

        return $this->render('ProfileShop/second_step.twig', [
            'title'               => 'Документы',
            'body_class'          => 'body_profile-shop',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'form_action'         => $this->getAbsUrl('/profile-shop/upload-documents'),
            'offer_link'          => $this->getAbsUrl('/documents/offer.pdf'),
        ]);
    }

    /**
     * Creates the shop account and the admin account (Ajax).
     *
     * @return JsonResponse
     * @throws \Rakit\Validation\RuleQuashException
     * @throws Exception
     */
    public function createAccountAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateFormOnCreate()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        if (! $this->createOrganization()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        if (! $this->createShop()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        if (! $this->createAdmin()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        $this->saveOrganizationIdToSession();

        Email::sendAuthData($this->shop_admin->getEmail(), $this->shop_admin->getPassword());

        $this->maybeSendAboutOrganizationCreatedAccount();

        return $this->sendJsonResponse(['redirect' => ['url' => $this->getAbsUrl('/profile-shop/second-step')]]);
    }

    /**
     * Download documents.
     *
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function downloadDocumentsAction(): BinaryFileResponse
    {
        $this->checkOrganization();

        $zip = new ZipArchive();

        $path     = SiteInfo::getDocumentRoot() . '/documents/unsigned/organizations/tin-'
            . $this->organization->getTin();
        $path_zip = SiteInfo::getDocumentRoot() . '/tmp/shablony-dokumentov.zip';

        $this->maybeSendAboutOrganizationDownloadTemplates();

        Helper::compressDir($path, $path_zip, $zip);

        return $this->sendBinaryFileResponse($path_zip, 200, [], false);
    }

    /**
     * Uploads documents (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function uploadDocumentsAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateFormOnUploadDocuments()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        $this->checkOrganization();

        $this->organization->saveSignedDocuments(
            $this->http_request->files->get('document_contract'),
            $this->http_request->files->get('document_questionnaire_fl_115'),
            $this->http_request->files->get('document_joining_application')
        );

        $this->deleteOrganizationIdFromSession();

        $this->maybeSendAboutOrganizationUploadedDocuments();

        return $this->sendJsonResponse([
            'message'  => [
                'body' => 'Сейчас вы попадёте на страницу авторизации. Реквизиты отправлены на ваш email.',
                'type' => 'success',
            ],
            'redirect' => [
                'url'     => $this->getAbsUrl('/admin-shops'),
                'timeout' => 5,
            ]
        ]);
    }

    /**
     * Clean the address (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function cleanAddressAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->http_request->request->has('address')) {
            throw new Exception('Invalid request.', 400);
        }

        $dadata = new Dadata();

        if (! $result = $dadata->cleanAddress($this->http_request->request->get('address'))) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        return $this->sendJsonResponse(['data' => ['success' => true, 'address' => $result]]);
    }

    /**
     * Creates the organization account.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     * @throws Exception
     */
    private function createOrganization(): bool
    {
        $this->organization = new Organization([
            'type'                        => trim($this->http_request->request->get('type')),
            'vat'                         => trim($this->http_request->request->get('vat')),
            'legal_name'                  => trim($this->http_request->request->get('legal_name')),
            'tin'                         => trim($this->http_request->request->get('tin')),
            'cio'                         => trim($this->http_request->request->get('cio')),
            'bin'                         => trim($this->http_request->request->get('bin')),
            'is_licensed_activity'        => $this->http_request->request->get('is_licensed_activity') ? 1 : 0,
            'license_type'                => trim($this->http_request->request->get('license_type')),
            'license_number'              => trim($this->http_request->request->get('license_number')),
            'category_id'                 => trim($this->http_request->request->get('category_id')),
            'legal_address'               => trim($this->http_request->request->get('legal_address')),
            'registration_address'        => trim($this->http_request->request->get('registration_address')),
            'fact_address'                => trim($this->http_request->request->get('fact_address')),
            'bik'                         => trim($this->http_request->request->get('bik')),
            'bank_name'                   => trim($this->http_request->request->get('bank_name')),
            'correspondent_account'       => trim($this->http_request->request->get('correspondent_account')),
            'settlement_account'          => trim($this->http_request->request->get('settlement_account')),
            'boss_full_name'              => trim($this->http_request->request->get('boss_full_name')),
            'boss_position'               => trim($this->http_request->request->get('boss_position')),
            'boss_basis_acts'             => trim($this->http_request->request->get('boss_basis_acts')),
            'boss_basis_acts_number'      => trim($this->http_request->request->get('boss_basis_acts_number')),
            'boss_basis_acts_issued_date' => trim($this->http_request->request->get('boss_basis_acts_issued_date')),
            'boss_passport_number'        => trim($this->http_request->request->get('boss_passport_number')),
            'boss_passport_issued_date'   => trim($this->http_request->request->get('boss_passport_issued_date')),
            'boss_passport_division_code' => trim($this->http_request->request->get('boss_passport_division_code')),
            'boss_passport_issued_by'     => trim($this->http_request->request->get('boss_passport_issued_by')),
            'boss_birth_date'             => trim($this->http_request->request->get('boss_birth_date')),
            'boss_birth_place'            => trim($this->http_request->request->get('boss_birth_place')),
            'email'                       => trim($this->http_request->request->get('email')),
            'phone'                       => trim($this->http_request->request->get('phone')),
        ], [
            'document_snils'                      => $this->http_request->files->get('document_snils'),
            'document_passport'                   => $this->http_request->files->get('document_passport'),
            'document_statute_with_tax_mark'      => $this->http_request->files->get('document_statute_with_tax_mark'),
            'document_participants_decision'      => $this->http_request->files->get('document_participants_decision'),
            'document_bin'                        => $this->http_request->files->get('document_bin'),
            'document_order_on_appointment'       => $this->http_request->files->get('document_order_on_appointment'),
            'document_statute_of_current_edition' =>
                $this->http_request->files->get('document_statute_of_current_edition'),
        ]);

        if (! $this->organization->create()) {
            $this->errors = $this->organization->getErrors();

            return false;
        }

        return true;
    }

    /**
     * Creates the shop account.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     * @throws Exception
     */
    private function createShop(): bool
    {
        $this->shop = new Shop([
            'name'            => $this->organization->getOrganizationName(),
            'email'           => $this->organization->getEmail(),
            'organization_id' => $this->organization->getId(),
        ]);

        if (! $this->shop->create()) {
            $this->errors = $this->shop->getErrors();

            return false;
        }

        return true;
    }

    /**
     * Creates the admin account.
     *
     * @return bool
     * @throws Exception
     */
    private function createAdmin(): bool
    {
        $this->shop_admin = new ShopAdmin([
            'name'         => $this->organization->getBossFullName(),
            'email'        => $this->organization->getEmail(),
            'shop_id'      => $this->shop->getId(),
            'role'         => 'admin',
            'is_activated' => 1,
        ]);

        if (! $this->shop_admin->create()) {
            $this->errors = $this->shop_admin->getErrors();

            return false;
        }

        return true;
    }

    /**
     * Saves an organization id to the session.
     *
     * @return void
     */
    private function saveOrganizationIdToSession(): void
    {
        $this->session->set('organization_id', $this->organization->getId());
    }

    /**
     * Deletes an organization id from the session.
     *
     * @return void
     */
    private function deleteOrganizationIdFromSession(): void
    {
        $this->session->remove('organization_id');
    }

    /**
     * Deletes an user from the session.
     *
     * If the client was previously authorized under the account, then when forwarding it will
     * see the panel of the old account. Therefore, it needs to remove from the session 'user'.
     *
     * @return void
     */
    private function deleteUserFromSession(): void
    {
        // Here you can not use HttpFoundation, because the session was start through the admin panel
        // by session_start() function.
        unset($_SESSION['user']);
    }

    /**
     * Finds the organization.
     *
     * @return void
     */
    private function findOrganization(): void
    {
        if ($this->session->has('organization_id')) {
            $this->organization = Organization::findById($this->session->get('organization_id'));
        }
    }

    /**
     * Check the organization.
     *
     * @return void
     * @throws Exception
     */
    private function checkOrganization(): void
    {
        if (empty($this->organization)) {
            throw new Exception('No route matched.', 404);
        }
    }

    /**
     * Sends the message by Telegram.
     *
     * @return void
     * @throws Exception
     */
    private function maybeSendAboutOrganizationCreatedAccount(): void
    {
        if (Config::isDevServer()) {
            return;
        }

        $telegram_organization_bot = new TelegramOrganizationBot();
        $telegram_organization_bot->organizationCreatedAccount($this->organization->getOrganizationName());

        Email::sendAboutNewOrganization(
            SiteInfo::getRegisteringEmail(),
            $this->organization->getId(),
            $this->shop->getId()
        );
    }

    /**
     * Sends the message by Telegram.
     *
     * @return void
     * @throws Exception
     */
    private function maybeSendAboutOrganizationDownloadTemplates(): void
    {
        if (Config::isDevServer()) {
            return;
        }

        $telegram_organization_bot = new TelegramOrganizationBot();
        $telegram_organization_bot->organizationDownloadTemplates($this->organization->getOrganizationName());
    }

    /**
     * Sends the message by Telegram.
     *
     * @return void
     * @throws Exception
     */
    private function maybeSendAboutOrganizationUploadedDocuments(): void
    {
        if (Config::isDevServer()) {
            return;
        }

        $telegram_organization_bot = new TelegramOrganizationBot();
        $telegram_organization_bot->organizationUploadedDocuments($this->organization->getOrganizationName());

        Email::sendAboutOrganizationUploadedSignedDocuments(
            SiteInfo::getRegisteringEmail(),
            $this->organization->getId()
        );
    }

    /**
     * Validates form.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateFormOnCreate(): bool
    {
        $validator = new Validator([
            'required'      => ':attribute - обязательное поле.',
            'required_if'   => ':attribute - обязательное поле.',
            'numeric'       => ':attribute - поле должно содержать только цифры.',
            'regex'         => ':attribute - поле имеет некорректный формат.',
            'uploaded_file' => ':attribute - тип файла и размер должны соответствовать требованиям',
        ]);

        $validator->addValidator('date_ru', new DateRule());
        $validator->addValidator('plain', new PlainRule());

        // TODO figure out how to replace $_FILES with Symfony\Component\HttpFoundation.
        $validation = $validator->make($this->http_request->request->all() + $_FILES, [
            'type'                                  => 'required|plain',
            'vat'                                   => 'required|numeric',
            'legal_name'                            => 'required_if:type,llc|plain',
            'tin'                                   => 'required|numeric',
            'cio'                                   => 'required_if:type,llc|regex:/^\d{9}$/',
            'bin'                                   => 'required|numeric',
            'is_licensed_activity'                  => 'default:0',
            'license_type'                          => 'required_if:is_licensed_activity,1,yes,on|plain',
            'license_number'                        => 'required_if:is_licensed_activity,1,yes,on|plain',
            'category_id'                           => 'required|numeric',
            'legal_address'                         => 'required_if:type,llc|plain',
            'registration_address'                  => 'required_if:type,entrepreneur|plain',
            'fact_address'                          => 'required|plain',
            'bik'                                   => 'required|regex:/^\d{9}$/',
            'bank_name'                             => 'required|plain',
            'correspondent_account'                 => 'required|regex:/^\d{20}$/',
            'settlement_account'                    => 'required|regex:/^\d{20}$/',
            'boss_full_name'                        => 'required|plain',
            'boss_position'                         => 'required_if:type,llc|plain',
            'boss_basis_acts'                       => 'required_if:type,llc|plain',
            'boss_basis_acts_number'                =>
                'required_if:boss_basis_acts,proxy,trust_management_agreement|plain',
            'boss_basis_acts_issued_date'           =>
                'required_if:boss_basis_acts,proxy,trust_management_agreement|date_ru',
            'boss_passport_number'                  => 'required|regex:/^\d{2}\s\d{2}\s\d{6}$/',
            'boss_passport_issued_date'             => 'required|date_ru',
            'boss_passport_division_code'           => 'required|regex:/^\d{3}-\d{3}$/',
            'boss_passport_issued_by'               => 'required|plain',
            'boss_birth_date'                       => 'required|date_ru',
            'boss_birth_place'                      => 'required|plain',
            'email'                                 => 'required|email',
            'phone'                                 => 'required|regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
            'document_snils.*'                      => 'uploaded_file:0,30M,pdf,jpeg,jpg',
            'document_passport.*'                   => 'uploaded_file:0,30M,pdf,jpeg,jpg',
            'document_statute_with_tax_mark.*'      => 'uploaded_file:0,30M,pdf,jpeg,jpg',
            'document_participants_decision.*'      => 'uploaded_file:0,30M,pdf,jpeg,jpg',
            'document_bin.*'                        => 'uploaded_file:0,30M,pdf,jpeg,jpg',
            'document_order_on_appointment.*'       => 'uploaded_file:0,30M,pdf,jpeg,jpg',
            'document_statute_of_current_edition.*' => 'uploaded_file:0,30M,pdf,jpeg,jpg',
        ]);

        $validation->setAliases([
            'type'                                  => 'Тип организации',
            'vat'                                   => 'НДС',
            'legal_name'                            => 'Юридическое наименование',
            'tin'                                   => 'ИНН',
            'cio'                                   => 'КПП',
            'bin'                                   => 'ОГРН',
            'is_licensed_activity'                  => 'Деятельность подлежит лицензированию',
            'license_type'                          => 'Тип лицензии',
            'license_number'                        => 'Номер лицензии',
            'category_id'                           => 'Категория',
            'legal_address'                         => 'Юридический адрес',
            'registration_address'                  => 'Адрес регистрации',
            'fact_address'                          => 'Фактический адрес',
            'bik'                                   => 'БИК',
            'bank_name'                             => 'Название банка',
            'correspondent_account'                 => 'Корреспондентский счёт',
            'settlement_account'                    => 'Расчётный счёт',
            'boss_full_name'                        => 'ФИО',
            'boss_position'                         => 'Должность',
            'boss_basis_acts'                       => 'Действует на основании',
            'boss_basis_acts_number'                => 'Серия и номер документа',
            'boss_basis_acts_issued_date'           => 'Дата выдачи документа',
            'boss_passport_number'                  => 'Серия и номер паспорта',
            'boss_passport_issued_date'             => 'Дата выдачи паспорта',
            'boss_passport_division_code'           => 'Код подразделения',
            'boss_passport_issued_by'               => 'Кем выдан паспорт',
            'boss_birth_date'                       => 'Дата рождения',
            'boss_birth_place'                      => 'Место рождения',
            'email'                                 => 'Email',
            'phone'                                 => 'Телефон',
            'document_snils.*'                      => 'СНИЛС',
            'document_passport.*'                   => 'Копия паспорта',
            'document_statute_with_tax_mark.*'      => 'Устав с отметкой налогового органа',
            'document_participants_decision.*'      => 'Протокол общего собрания участников',
            'document_bin.*'                        => 'ОГРН',
            'document_order_on_appointment.*'       => 'Приказ о назначении единоличного исполнительного органа',
            'document_statute_of_current_edition.*' => 'Устав действующей редакции',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();

            return false;
        }

        return true;
    }

    /**
     * Validates form.
     *
     * @return bool
     */
    private function validateFormOnUploadDocuments(): bool
    {
        $validator = new Validator([
            'required'      => ':attribute - обязательное поле.',
            'uploaded_file' => ':attribute - тип файла и размер должны соответствовать требованиям',
        ]);

        // TODO figure out how to replace $_FILES with Symfony\Component\HttpFoundation.
        $validation = $validator->make($_FILES, [
            'document_contract.*'             => 'uploaded_file:0,30M,pdf,jpeg,jpg',
            'document_joining_application.*'  => 'uploaded_file:0,30M,pdf,jpeg,jpg',
            'document_questionnaire_fl_115.*' => 'uploaded_file:0,30M,pdf,jpeg,jpg',
        ]);

        $validation->setAliases([
            'document_contract.*'             => 'Договор с Bliss',
            'document_joining_application.*'  => 'Заявление о присоединении',
            'document_questionnaire_fl_115.*' => 'Анкета-опросник по 115 ФЗ',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();

            return false;
        }

        return true;
    }
}
