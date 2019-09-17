<?php

namespace App\MFI;

use App\Config;
use App\Logging;
use App\SiteInfo;
use App\Telegram;
use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

/**
 * Class MFI.
 *
 * @package Core
 */
abstract class MFI
{
    /**
     * The telegram bot handler.
     *
     * @var HandlerStack
     */
    protected $handler_telegram_bot;

    /**
     * HTTP Client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http_client;

    /**
     * API URL.
     *
     * @var string
     */
    protected $api_url;

    /**
     * The mfi name.
     *
     * @var string
     */
    protected $mfi_name;

    /**
     * The mfi slug.
     *
     * @var string
     */
    protected $mfi_slug;

    /**
     * The request parameters.
     *
     * @var array
     */
    protected $request_params;

    /**
     * The customer ID.
     *
     * @var mixed
     */
    protected $customer_id;

    /**
     * The contract id.
     *
     * @var mixed
     */
    protected $contract_id;

    /**
     * The loan ID.
     *
     * @var mixed
     */
    protected $loan_id;

    /**
     * The loan body.
     *
     * @var mixed
     */
    protected $loan_body;

    /**
     * The loan cost.
     *
     * @var mixed
     */
    protected $loan_cost;

    /**
     * The loan period in days.
     *
     * @var int
     */
    protected $loan_period;

    /**
     * The terms link.
     *
     * @var string|null
     */
    protected $loan_terms_link;

    /**
     * The loan daily percent rate.
     *
     * @var mixed
     */
    protected $loan_daily_percent_rate;

    /**
     * The status.
     *
     * Statuses: waiting_limit, approved, issued, issued_postponed, wrong_sms_code, data_not_found, deleted.
     *
     * @var string
     */
    protected $status;

    /**
     * Starts.
     *
     * @return array|false The array of response, false otherwise.
     */
    abstract public function start();

    /**
     * Confirms a loan by a client.
     *
     * @param string $sms_code The sms-code.
     *
     * @return array|false The array of response, false otherwise.
     */
    abstract public function confirmLoanByClient(string $sms_code);

    /**
     * Confirms a loan by a shop.
     *
     * @param string $datetime The datetime.
     * @param string $tracking_code (optional) The tracking code.
     * @param string $service_name (optional) The service name.
     *
     * @return array|false The array of response, false otherwise.
     */
    abstract public function confirmLoanByShop(string $datetime, string $tracking_code = '', string $service_name = '');

    /**
     * Cancels the loan by a client.
     *
     * @return array|false The array of response, false otherwise.
     */
    abstract public function cancelLoanByClient();

    /**
     * Decline a loan by a shop.
     *
     * @return array|false The array of response, false otherwise.
     */
    abstract public function declineLoanByShop();

    /**
     * Sends a code to confirm a loan.
     *
     * @return array|false The array of response, false otherwise.
     */
    abstract public function sendConfirmLoanCode();

    /**
     * Gets the request parameter by name.
     *
     * @param string $name The name.
     * @param string $default (optional) The default value.
     *
     * @return mixed
     */
    protected function getRequestParam(string $name, $default = '')
    {
        return $this->request_params[$name] ?? $default;
    }

    /**
     * Gets the response.
     *
     * @return array
     */
    protected function getResponse(): array
    {
        return [
            'status'                  => $this->status,
            'customer_id'             => $this->customer_id,
            'contract_id'             => $this->contract_id,
            'loan_id'                 => $this->loan_id,
            'loan_body'               => $this->loan_body,
            'loan_cost'               => $this->loan_cost,
            'loan_period'             => $this->loan_period,
            'loan_daily_percent_rate' => $this->loan_daily_percent_rate,
            'loan_terms_link'         => $this->loan_terms_link,
        ];
    }

    /**
     * Set Logging Handler Stack.
     *
     * @return HandlerStack
     * @throws Exception
     */
    protected function getLoggingHandlerStack(): HandlerStack
    {
        return Logging::createLoggingHandlerStack(
            $this->mfi_slug,
            'mfi/' . date('Y-m-d') . '/client-id-' . $this->getRequestParam('client_id') . '/'
            . $this->mfi_slug . '.log'
        );
    }

    /**
     * Sends the alarm by the Telegram bot.
     *
     * @param Exception $exception
     *
     * @return void
     * @throws Exception
     */
    protected function sendAlarmByTelegramBot(Exception $exception): void
    {
        if ($exception instanceof RequestException
            && $exception->hasResponse()
            && in_array($exception->getResponse()->getStatusCode(), [500, 501, 504])
        ) {
            $message = '<b>MFI "' . $this->mfi_name . '" gave an error.' . PHP_EOL . '</b>';
            $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
            $message .= 'Status code: ' . $exception->getResponse()->getStatusCode() . PHP_EOL;
            $message .= 'Reason phrase: ' . $exception->getResponse()->getReasonPhrase() . PHP_EOL;
            $message .= 'Client id: ' . $this->getRequestParam('client_id') . PHP_EOL;
            $message .= 'Request id: ' . $this->getRequestParam('request_id') . PHP_EOL;
        }

        if (isset($message)) {
            $telegram = new Telegram(
                Config::TELEGRAM_DEV_TOKEN,
                Config::TELEGRAM_DEV_CHAT_ID,
                $this->handler_telegram_bot
            );
            $telegram->sendMessage($message);
        }
    }
}
