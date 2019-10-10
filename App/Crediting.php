<?php

namespace App;

use App\Models\DeliveryService;
use App\Models\Loan;
use App\Models\MFI;
use App\Models\Order;
use App\Models\OrderCallback;
use App\Models\RememberedClient;
use App\Models\Request;
use App\Models\Shop;
use Exception;

/**
 * Class Crediting.
 *
 * @package App
 */
class Crediting
{
    /**
     * The request model.
     *
     * @var Request
     */
    private $request;

    /**
     * The order model.
     *
     * @var Order
     */
    private $order;

    /**
     * The request parameters.
     *
     * @var array
     */
    private $request_params;

    /**
     * The total time limit in seconds.
     *
     * @var int
     */
    private $total_time_limit;

    /**
     * The end time.
     *
     * @var int
     */
    private $end_time;

    /**
     * The current time limit.
     *
     * @var int
     */
    private $current_time_limit;

    /**
     * Crediting constructor.
     *
     * @param int $request_id The request id.
     */
    public function __construct(int $request_id)
    {
        $this->request          = Request::findById($request_id);
        $this->order            = Order::findById($this->request->getOrderId());
        $this->request_params   = $this->request->getCreditingData();
        $this->total_time_limit = 0;
    }

    /**
     * Sends code.
     *
     * @return bool True if success, false otherwise.
     */
    public function sendCode(): bool
    {
        $mfi = MFI::getById($this->request->getApprovedMfiId());

        $connector_class = 'App\\MFI\\' . $mfi['slug'];

        if (class_exists($connector_class)) {
            $mfi_api_params = MFI::getApiParametersForShop($mfi['id'], $this->request->getShopId());
            $loan_params    = json_decode($this->request->getApprovedMfiResponse(), true);
            $mfi_handler    = new $connector_class($this->request_params, $mfi_api_params, $loan_params);

            if (method_exists($mfi_handler, 'sendConfirmLoanCode')) {
                $result = $mfi_handler->sendConfirmLoanCode();

                if (isset($result['status']) && $result['status'] === 'approved') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Confirms the loan by client.
     *
     * @param string $sms_code The sms code.
     *
     * @return bool|string The status if success, false otherwise.
     * @throws Exception
     */
    public function confirmByClient(string $sms_code)
    {
        $mfi             = MFI::getById($this->request->getApprovedMfiId());
        $connector_class = 'App\\MFI\\' . $mfi['slug'];

        if (class_exists($connector_class)) {
            $mfi_api_params = MFI::getApiParametersForShop($mfi['id'], $this->request->getShopId());
            $loan_params    = json_decode($this->request->getApprovedMfiResponse(), true);
            $mfi_handler    = new $connector_class($this->request_params, $mfi_api_params, $loan_params);

            if (method_exists($mfi_handler, 'confirmLoanByClient')) {
                $result = $mfi_handler->confirmLoanByClient($sms_code);

                if (isset($result['status'])) {
                    if (! in_array($result['status'], ['issued', 'issued_postponed'])) {
                        return $result['status'];
                    }

                    $this->request->updateStatus('confirmed');

                    if ($result['status'] === 'issued') {
                        $order_status = 'waiting_for_payment';
                        $loan_status  = 'issued';
                    } else {
                        $order_status = 'pending_by_shop';
                        $loan_status  = 'pending';
                    }

                    $this->order->updateStatus($order_status);

                    $loan = new Loan([
                        'request_id'              => $this->request->getId(),
                        'mfi_id'                  => $this->request->getApprovedMfiId(),
                        'shop_id'                 => $this->request->getShopId(),
                        'status'                  => $loan_status,
                        'customer_id'             => $result['customer_id'],
                        'contract_id'             => $result['contract_id'],
                        'loan_id'                 => $result['loan_id'],
                        'loan_body'               => $result['loan_body'],
                        'loan_cost'               => $result['loan_cost'],
                        'loan_period'             => $result['loan_period'],
                        'loan_daily_percent_rate' => $result['loan_daily_percent_rate'],
                        'loan_terms_link'         => $result['loan_terms_link'],
                    ]);

                    $loan->create();

                    $this->hookConfirmByClient();

                    return $result['status'];
                }
            }
        }

        return false;
    }

    /**
     * Cancels the request by the client.
     *
     * @return bool True if success, false otherwise.
     * @throws Exception
     */
    public function cancelByClient(): bool
    {
        $this->hookCancelByClient();

        return $this->order->updateStatus('canceled_by_client')
            && $this->request->updateStatus('canceled');
    }

    /**
     * Cancels the loan by the client.
     *
     * @return bool True if success, false otherwise.
     * @throws Exception
     */
    public function cancelByClientUponReceipt(): bool
    {
        $mfi             = MFI::getById($this->request->getApprovedMfiId());
        $connector_class = 'App\\MFI\\' . $mfi['slug'];

        if (class_exists($connector_class)) {
            $mfi_api_params = MFI::getApiParametersForShop($mfi['id'], $this->request->getShopId());

            /** @var Loan $loan */
            $loan = Loan::findByRequestId($this->request->getId());

            $mfi_handler = new $connector_class($this->request_params, $mfi_api_params, $loan->getLoan());

            if (method_exists($mfi_handler, 'cancelLoanByClient')) {
                if ($mfi_handler->cancelLoanByClient()) {
                    $this->order->updateStatus('canceled_by_client_upon_receipt');
                    $loan->updateStatus('canceled_by_client');

                    $this->hookCancelByClientUponReceipt();

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Confirms the loan by shop.
     *
     * @param string $date The date.
     * @param int $delivery_service_id (optional) The delivery service id.
     * @param string $tracking_code (optional) The tracking code.
     *
     * @return bool True if success, false otherwise.
     */
    public function confirmByShop(string $date, int $delivery_service_id = null, string $tracking_code = ''): bool
    {
        $mfi             = MFI::getById($this->request->getApprovedMfiId());
        $connector_class = 'App\\MFI\\' . $mfi['slug'];

        if (class_exists($connector_class)) {
            $mfi_api_params = MFI::getApiParametersForShop($mfi['id'], $this->request->getShopId());

            /** @var Loan $loan */
            $loan = Loan::findByRequestId($this->request->getId());

            $mfi_handler = new $connector_class($this->request_params, $mfi_api_params, $loan->getLoan());

            if (method_exists($mfi_handler, 'confirmLoanByShop')) {
                $delivery_service_name = $delivery_service_id ? DeliveryService::getName($delivery_service_id) : '';

                $result = $mfi_handler->confirmLoanByShop($date, $tracking_code, $delivery_service_name);

                if (isset($result['status']) && $result['status'] === 'issued') {
                    $this->order->updateStatus('waiting_for_payment');

                    if (! empty($delivery_service_id)) {
                        $this->order->updateDeliveryServiceId($delivery_service_id);
                    }

                    if (! empty($tracking_code)) {
                        $this->order->updateTrackingCode($tracking_code);
                    }

                    $loan->updateStatus('issued');

                    $this->hookConfirmByShop();

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Declines the loan by shop.
     *
     * @return bool True if success, false otherwise.
     */
    public function declineByShop(): bool
    {
        $mfi             = MFI::getById($this->request->getApprovedMfiId());
        $connector_class = 'App\\MFI\\' . $mfi['slug'];

        if (class_exists($connector_class)) {
            $mfi_api_params = MFI::getApiParametersForShop($mfi['id'], $this->request->getShopId());

            /** @var Loan $loan */
            $loan = Loan::findByRequestId($this->request->getId());

            $mfi_handler = new $connector_class($this->request_params, $mfi_api_params, $loan->getLoan());

            if (method_exists($mfi_handler, 'declineLoanByShop')) {
                if ($mfi_handler->declineLoanByShop()) {
                    $this->order->updateStatus('declined_by_shop');
                    $loan->updateStatus('declined_by_shop');

                    $this->hookDeclineByShop();

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Delivers the loan by shop.
     *
     * @param int $delivery_service_id The delivery service id.
     * @param string $tracking_code The tracking code.
     *
     * @return bool True if success, false otherwise.
     */
    public function deliverByShop(int $delivery_service_id, string $tracking_code): bool
    {
        /** @var Loan $loan */
        $loan = Loan::findByRequestId($this->request->getId());

        $this->hookDeliverByShop();

        return $this->order->updateStatus('waiting_for_delivery')
            && $this->order->updateDeliveryServiceId($delivery_service_id)
            && $this->order->updateTrackingCode($tracking_code)
            && $loan->updateStatus('waiting_for_delivery');
    }

    /**
     * Starts crediting.
     *
     * @return bool True if success, false otherwise.
     * @throws Exception
     */
    public function startCrediting(): bool
    {
        $mfi_list = MFI::getForShop(
            $this->request->getShopId(),
            $this->order->getOrderPrice(),
            $this->request->getIsLoanPostponed()
        );

        if (empty($mfi_list)) {
            $this->order->updateStatus('declined_by_mfi');
            $this->request->updateStatus('declined');

            $this->hookDeclinedByAllMFI();

            return false;
        }

        foreach ($mfi_list as $mfi) {
            $this->total_time_limit += $mfi['time_limit'];
        }

        $this->end_time = time() + $this->total_time_limit;

        foreach ($mfi_list as $mfi) {
            $this->current_time_limit = $mfi['time_limit'];

            if ($result = $this->sendRequests($mfi)) {
                $this->order->updateStatus('approved_by_mfi');
                $this->request->updateStatus('approved');
                $this->request->updateApprovedMfi($mfi['id'], json_encode($result));

                return true;
            }
        }

        if (MFI::getResponses($this->request->getId(), 'did_not_have_time')) {
            $this->order->updateStatus('mfi_did_not_answer');
            $this->request->updateStatus('manual');

            $this->hookDidNotHaveTime();
        } else {
            $this->order->updateStatus('declined_by_mfi');
            $this->request->updateStatus('declined');

            $this->hookDeclinedByAllMFI();
        }

        return false;
    }

    /**
     * Starts the waiting for a limit.
     *
     * @return bool
     * @throws Exception
     */
    public function startWaitingForLimit(): bool
    {
        $this->total_time_limit = 60 * 60 * 2;
        $this->end_time         = time() + $this->total_time_limit;

        do {
            $mfi_list = MFI::getDidNotAnswered($this->request->getId());

            if (empty($mfi_list)) {
                break;
            }

            foreach ($mfi_list as $mfi) {
                if ($result = $this->sendRequest($mfi)) {
                    $this->order->updateStatus('approved_by_mfi');
                    $this->request->updateStatus('approved');
                    $this->request->updateApprovedMfi($mfi['id'], json_encode($result));

                    $this->sendRememberedClientLink();

                    return true;
                }
            }

            $mfi_list = MFI::getDidNotAnswered($this->request->getId());

            if (empty($mfi_list)) {
                break;
            }

            $sleep_time = ($this->end_time - time()) >= 300 ? 300 : $this->end_time - time();

            sleep($sleep_time);
        } while (time() < $this->end_time);

        $this->order->updateStatus('declined_by_mfi');
        $this->request->updateStatus('declined');

        $this->hookDeclinedByAllMFI();

        return false;
    }

    /**
     * Gets the request parameter by name.
     *
     * @param string $name The name.
     * @param string $default (optional) The default value.
     *
     * @return mixed
     */
    private function getRequestParam(string $name, $default = '')
    {
        return $this->request_params[$name] ?? $default;
    }

    /**
     * Sends requests.
     *
     * @param array $mfi The array of mfi data.
     *
     * @return mixed Array of result if success, false otherwise.
     * @throws Exception
     */
    private function sendRequests(array $mfi)
    {
        $current_start_time = time();

        if (! class_exists($class_name = 'App\\MFI\\' . $mfi['slug'])) {
            return false;
        }

        $mfi_api_params = MFI::getApiParametersForShop($mfi['id'], $this->request->getShopId());

        $loan = new $class_name($this->request_params, $mfi_api_params);

        if (! method_exists($loan, 'start')) {
            return false;
        }

        $result = $loan->start();

        if ($result && $result['status'] === 'waiting_limit') {
            do {
                if (time() - $current_start_time >= $this->current_time_limit || time() >= $this->end_time) {
                    MFI::createResponse($mfi['id'], $this->request->getId(), 'did_not_have_time');

                    $this->hookDidNotHaveTimeForOneMFI($mfi);

                    return false;
                }

                $sleep_time = ($this->end_time - time()) >= 60 ? 60 : $this->end_time - time();

                sleep($sleep_time);

                $result = $loan->start();
            } while ($result && $result['status'] === 'waiting_limit');
        }

        if ($result && $result['status'] === 'approved') {
            MFI::createResponse($mfi['id'], $this->request->getId(), 'approved');

            $this->hookApprovedByOneMFI($mfi);

            return $result;
        }

        MFI::createResponse($mfi['id'], $this->request->getId(), 'declined');

        $this->hookDeclinedByOneMFI($mfi);

        return false;
    }

    /**
     * Sends the request.
     *
     * @param array $mfi The mfi data.
     *
     * @return mixed Array of result if success, false otherwise.
     * @throws Exception
     */
    private function sendRequest(array $mfi)
    {
        if (! class_exists($class_name = 'App\\MFI\\' . $mfi['slug'])) {
            return false;
        }

        $mfi_api_params = MFI::getApiParametersForShop($mfi['id'], $this->request->getShopId());

        $loan = new $class_name($this->request_params, $mfi_api_params);

        if (! method_exists($loan, 'start')) {
            return false;
        }

        if (! $result = $loan->start()) {
            MFI::updateResponse($mfi['id'], $this->request->getId(), 'declined');

            $this->hookDeclinedByOneMFI($mfi);

            return false;
        }

        if ($result['status'] === 'approved') {
            MFI::updateResponse($mfi['id'], $this->request->getId(), 'approved');

            $this->hookApprovedByOneMFI($mfi);

            return $result;
        }

        return false;
    }

    /**
     * Sends the remembered client link.
     *
     * @return void
     * @throws Exception
     */
    private function sendRememberedClientLink(): void
    {
        /* @var Shop $shop */
        $shop = Shop::findById($this->request->getShopId());

        $shop_id              = $this->request->getShopId();
        $order_id             = $this->order->getOrderIdInShop();
        $order_price          = $this->order->getOrderPrice();
        $goods                = $this->order->getGoods();
        $callback_url         = $this->getRequestParam('callback_url');
        $is_loan_postponed    = $this->request->getIsLoanPostponed();
        $is_test_mode_enabled = $this->request->getIsTestModeEnabled();

        $signature = Request::createRequestSignature(
            $shop_id,
            $order_id,
            $order_price,
            $goods,
            $callback_url,
            $is_loan_postponed,
            $is_test_mode_enabled,
            $shop->getSecretKey()
        );

        $remembered_client = new RememberedClient([
            'shop_id'              => $shop_id,
            'order_id'             => $order_id,
            'order_price'          => $order_price,
            'goods'                => $goods,
            'callback_url'         => $callback_url,
            'is_loan_postponed'    => $is_loan_postponed,
            'is_test_mode_enabled' => $is_test_mode_enabled,
            'signature'            => $signature,
        ]);

        if ($remembered_client->create()) {
            $remembered_client->savePhone($this->getRequestParam('client_phone'));
            $remembered_client->verifyPhone();

            do {
                $result = SMS::sendRememberedClientLink(
                    $this->getRequestParam('client_phone'),
                    $remembered_client->getToken()
                );

                sleep(30);
            } while (! $result);
        }
    }

    /**
     * Maybe sends the callback.
     *
     * @return void
     * @throws Exception
     */
    private function maybeSendCallback(): void
    {
        /* @var $order_callback OrderCallback */
        $order_callback = OrderCallback::findByOrderId($this->order->getId());

        if (! $order_callback) {
            return;
        }

        $file_name = 'callbacks/' . date('Y-m-d') . '.log';
        $handler   = Logging::createLoggingHandlerStack('Callback', $file_name);

        $http_client = new \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'handler' => $handler,
        ]);

        try {
            $response = $http_client->patch($order_callback->getCallbackUrl(), [
                'body' => json_encode(Order::getOrder($this->order->getId())),
            ]);

            if ($response->getStatusCode() === 204) {
                $order_callback->updateIsCallbackSent(1);
            }
        } catch (Exception $e) {
            // I'm busy doing nothing.
        }
    }

    /**
     * The hook for confirm by the client.
     *
     * @return void
     * @throws Exception
     */
    private function hookConfirmByClient(): void
    {
        if (! Config::isDevServer()) {
            $client_name = $this->getRequestParam('client_last_name')
                . ' ' . $this->getRequestParam('client_first_name')
                . ' ' . $this->getRequestParam('client_middle_name');

            /* @var Shop $shop */
            $shop = Shop::findById($this->request->getShopId());

            $telegram_client_bot = new TelegramClientBot();
            $telegram_client_bot->clientConfirmedLoan(
                $client_name,
                $this->getRequestParam('client_phone'),
                $shop->getName(),
                $this->order->getOrderPrice(),
                $this->request->getId()
            );
        }

        $this->maybeSendCallback();
    }

    /**
     * The hook for cancel by the client.
     *
     * @return void
     * @throws Exception
     */
    private function hookCancelByClient(): void
    {
        if (! Config::isDevServer()) {
            $client_name = $this->getRequestParam('client_last_name')
                . ' ' . $this->getRequestParam('client_first_name')
                . ' ' . $this->getRequestParam('client_middle_name');

            /* @var Shop $shop */
            $shop = Shop::findById($this->request->getShopId());

            $telegram_client_bot = new TelegramClientBot();
            $telegram_client_bot->clientCanceledRequest(
                $client_name,
                $this->getRequestParam('client_phone'),
                $shop->getName(),
                $this->order->getOrderPrice(),
                $this->request->getId()
            );
        }

        $this->maybeSendCallback();
    }

    /**
     * The hook for cancel by the client upon a receipt.
     *
     * @return void
     * @throws Exception
     */
    private function hookCancelByClientUponReceipt(): void
    {
        if (! Config::isDevServer()) {
            $client_name = $this->getRequestParam('client_last_name')
                . ' ' . $this->getRequestParam('client_first_name')
                . ' ' . $this->getRequestParam('client_middle_name');

            /* @var Shop $shop */
            $shop = Shop::findById($this->request->getShopId());

            $telegram_client_bot = new TelegramClientBot();
            $telegram_client_bot->clientCanceledLoanUponReceipt(
                $client_name,
                $this->getRequestParam('client_phone'),
                $shop->getName(),
                $this->order->getOrderPrice(),
                $this->request->getId()
            );
        }
    }

    /**
     * The hook for confirm by the shop.
     *
     * @return void
     */
    private function hookConfirmByShop(): void
    {
    }

    /**
     * The hook for decline by the shop.
     *
     * @return void
     */
    private function hookDeclineByShop(): void
    {
    }

    /**
     * The hook for deliver by the shop.
     *
     * @return void
     */
    private function hookDeliverByShop(): void
    {
    }

    /**
     * The hook for did not have time.
     *
     * @return void
     * @throws Exception
     */
    private function hookDidNotHaveTime(): void
    {
        if (! Config::isDevServer()) {
            $telegram_mfi_bot = new TelegramMFIBot();
            $telegram_mfi_bot->didNotHaveTime($this->request->getId());
        }
    }

    /**
     * The hook for did not have time for one mfi.
     *
     * @param array $mfi
     *
     * @return void
     * @throws Exception
     */
    private function hookDidNotHaveTimeForOneMFI(array $mfi): void
    {
        if (! Config::isDevServer()) {
            $telegram_mfi_bot = new TelegramMFIBot();
            $telegram_mfi_bot->mfiGaveResponse($mfi['name'], $this->request->getId(), 'МФО не успела дать ответ');
        }
    }

    /**
     * The hook for approved by one mfi.
     *
     * @param array $mfi
     *
     * @return void
     * @throws Exception
     */
    private function hookApprovedByOneMFI(array $mfi): void
    {
        if (! Config::isDevServer()) {
            $telegram_mfi_bot = new TelegramMFIBot();
            $telegram_mfi_bot->mfiGaveResponse($mfi['name'], $this->request->getId(), 'МФО одобрила кредит');
        }
    }

    /**
     * The hook for declined by one mfi.
     *
     * @param array $mfi
     *
     * @return void
     * @throws Exception
     */
    private function hookDeclinedByOneMFI(array $mfi): void
    {
        if (! Config::isDevServer()) {
            $telegram_mfi_bot = new TelegramMFIBot();
            $telegram_mfi_bot->mfiGaveResponse($mfi['name'], $this->request->getId(), 'МФО отказала в кредите');
        }
    }

    /**
     * The hook for declined by all mfi.
     *
     * @return void
     * @throws Exception
     */
    private function hookDeclinedByAllMFI(): void
    {
        if (! Config::isDevServer()) {
            $telegram_mfi_bot = new TelegramMFIBot();
            $telegram_mfi_bot->allMfiDeclined($this->request->getId());
        }

        $this->maybeSendCallback();
    }
}
