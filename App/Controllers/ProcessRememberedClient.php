<?php

namespace App\Controllers;

use App\Helper;
use App\Models\RememberedClient;
use App\SiteInfo;
use Core\Controller;
use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProcessRememberedClient.
 *
 * @package App\Controllers
 */
class ProcessRememberedClient extends Controller
{
    /**
     * Shows the index page.
     *
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws Exception
     */
    public function indexAction(): Response
    {
        $token = $this->http_request->query->get('token');

        if (empty($token)) {
            throw new Exception('No route matched.', 404);
        }

        if (! $remembered_client = $this->getRememberedClient($token)) {
            throw new Exception('No route matched.', 404);
        }

        $response = new Response();
        $response->headers->setCookie(Cookie::create(
            'remembered_client',
            $token,
            $remembered_client->getTokenExpiresAt(),
            '/'
        ));

        return $this->render('ProcessRememberedClient/index.twig', [
            'title'               => 'Обработка заявки',
            'body_class'          => 'body_process_remembered_client',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'message'             => 'Подождите, заявка обрабатывается...',
            'redirect_link'       => $this->getAbsUrl('/success'),
        ], $response);
    }

    /**
     * Gets the remembered client.
     *
     * @param string $token The token.
     *
     * @return RememberedClient|false
     * @throws Exception
     */
    protected function getRememberedClient(string $token)
    {
        return RememberedClient::findByToken($token);
    }
}
