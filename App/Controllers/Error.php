<?php

namespace App\Controllers;

use App\Helper;
use App\SiteInfo;
use Core\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Error.
 *
 * @package App\Controllers
 */
class Error extends Controller
{
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
        return $this->render('Error/index.twig', [
            'title'               => 'Ошибка',
            'body_class'          => 'body_error',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'flash_bag'           => $this->session->getFlashBag()->get('error', []),
            'shop_back_url'       => $this->session->get('shop_back_url'),
        ]);
    }
}
