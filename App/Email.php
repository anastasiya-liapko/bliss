<?php

namespace App;

use App\Models\Organization;
use Core\View;
use GuzzleHttp\HandlerStack;

/**
 * Class Email.
 *
 * @package App\Models
 */
class Email
{
    /**
     * Sends the email with a link on the order.
     *
     * @param string $email The email.
     * @param string $shop_name The shop name.
     * @param string $order_token The order token.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function sendOrderLink(
        string $email,
        string $shop_name,
        string $order_token,
        $handler = null
    ): bool {
        $args = [
            'shop_name' => $shop_name,
            'link'      => SiteInfo::getSchemeAndHttpHost() . '/process-order?token=' . $order_token,
        ];

        $text = View::getTemplate('EmailTemplates/order_link.txt', $args);
        $html = View::getTemplate('EmailTemplates/order_link.twig', $args);

        $mail = new MailMan($handler);

        return $mail->send($email, 'Ссылка на заказ', $text, $html);
    }

    /**
     * Sends the email to the shop with an loan info.
     *
     * @param string $email The email.
     * @param int $order_id The order id.
     * @param string $client_name The client name.
     * @param string $client_phone The client phone.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool True if success, false otherwise.
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public static function sendClientConfirmedLoan(
        string $email,
        int $order_id,
        string $client_name,
        string $client_phone,
        $handler = null
    ): bool {
        $args = [
            'order_id'     => $order_id,
            'client_name'  => $client_name,
            'client_phone' => $client_phone,
        ];

        $text = View::getTemplate('EmailTemplates/client_confirmed_loan.txt', $args);
        $html = View::getTemplate('EmailTemplates/client_confirmed_loan.twig', $args);

        $mail = new MailMan($handler);

        return $mail->send($email, 'Покупатель подтвердил заявку на кредит', $text, $html);
    }

    /**
     * Sends the email about new organization.
     *
     * @param string $email The email.
     * @param int $organization_id The organization id.
     * @param int $shop_id The shop id.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public static function sendAboutNewOrganization(
        string $email,
        int $organization_id,
        int $shop_id,
        $handler = null
    ): bool {
        $args = [
            'organization_id' => $organization_id,
            'shop_id'         => $shop_id,
        ];

        $text = View::getTemplate('EmailTemplates/new_organization.txt', $args);
        $html = View::getTemplate('EmailTemplates/new_organization.twig', $args);

        $mail = new MailMan($handler);

        return $mail->send($email, 'В системе зарегистрировалась организация', $text, $html);
    }

    /**
     * Sends the email about the organization uploaded sign documents.
     *
     * @param string $email The email.
     * @param int $organization_id The organization id.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public static function sendAboutOrganizationUploadedSignedDocuments(
        string $email,
        int $organization_id,
        $handler = null
    ): bool {
        $args = [
            'organization_id' => $organization_id,
        ];

        $text = View::getTemplate('EmailTemplates/organization_uploaded_signed_documents.txt', $args);
        $html = View::getTemplate('EmailTemplates/organization_uploaded_signed_documents.twig', $args);

        $mail = new MailMan($handler);

        return $mail->send($email, 'Организация загрузила подписанные документы', $text, $html);
    }

    /**
     * Sends the email to the admin with an auth info.
     *
     * @param string $email The email.
     * @param string $password The password.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public static function sendAuthData(string $email, string $password, $handler = null): bool
    {
        $args = [
            'email'    => $email,
            'password' => $password,
        ];

        $text = View::getTemplate('EmailTemplates/shop_admin_auth_data.txt', $args);
        $html = View::getTemplate('EmailTemplates/shop_admin_auth_data.twig', $args);

        $mail = new MailMan($handler);

        return $mail->send($email, 'Данные для авторизации в системе ' . SiteInfo::NAME, $text, $html);
    }

    /**
     * Sends the email about the organization uploaded sign documents.
     *
     * @param string $email The email.
     * @param int $organization_id The organization id.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public static function sendAboutAdminConfirmedOrganizationDocuments(
        string $email,
        int $organization_id,
        $handler = null
    ): bool {
        /** @var  $organization Organization */
        $organization = Organization::findById($organization_id);

        $args = [
            'organization_id'   => $organization_id,
            'organization_name' => $organization->getOrganizationName(),
        ];

        $text = View::getTemplate('EmailTemplates/admin_confirmed_organization_documents.txt', $args);
        $html = View::getTemplate('EmailTemplates/admin_confirmed_organization_documents.twig', $args);

        $mail = new MailMan($handler);

        return $mail->send($email, 'Документы организации подтверждены', $text, $html);
    }
}
