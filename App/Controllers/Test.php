<?php

namespace App\Controllers;

use App\Helper;
use App\SiteInfo;
use Core\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Test.
 *
 * @package App\Controllers
 */
class Test extends Controller
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
        $shop_id              = 1;
        $order_id             = mt_rand();
        $order_price          = 3000;
        $callback_url         = $this->getAbsUrl('/test');
        $is_loan_postponed    = 1;
        $goods                = [
            [
                'name'          => 'Наушники внутриканальные Sony MDR-EX15LP Black',
                'price'         => 3000,
                'quantity'      => 1,
                'is_returnable' => 1,
            ],
        ];
        $goods_encoded        = json_encode($goods);
        $is_test_mode_enabled = 1;
        $secret_key           = 'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj';
        $signature            = hash('sha256', $shop_id . $order_id . $order_price . $callback_url
            . $is_loan_postponed . $goods_encoded . $is_test_mode_enabled . $secret_key);

        return $this->render('Test/index.twig', [
            'title'                => 'Тестовая страница',
            'body_class'           => 'body_test',
            'phone_number'         => SiteInfo::PHONE,
            'phone_link'           => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number'  => SiteInfo::SECOND_PHONE,
            'second_phone_link'    => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'            => SiteInfo::WORK_TIME,
            'form_action'          => $this->getAbsUrl('/phone-number'),
            'shop_id'              => $shop_id,
            'order_id'             => $order_id,
            'order_price'          => $order_price,
            'callback_url'         => $callback_url,
            'is_loan_postponed'    => $is_loan_postponed,
            'goods'                => $goods_encoded,
            'is_test_mode_enabled' => $is_test_mode_enabled,
            'signature'            => $signature,
        ]);
    }
}
