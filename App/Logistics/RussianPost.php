<?php

namespace App\Logistics;

use App\Logging;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Meng\AsyncSoap\Guzzle\Factory;
use Exception;

/**
 * Class RussianPost.
 *
 * @package App
 */
class RussianPost
{
    /**
     * The soap client.
     *
     * @var string
     */
    private $soap_client;

    /**
     * The API URL.
     *
     * @var string
     */
    private $api_url;

    /**
     * The login.
     *
     * @var string
     */
    private $login;

    /**
     * The password.
     *
     * @var string
     */
    private $password;

    /**
     * The language.
     *
     * @var string
     */
    private $language;

    /**
     * RussianPost constructor.
     *
     * @param bool $is_packet_mode (optional) Is the packet mode.
     * @param HandlerStack $handler (optional) The handler stack.
     *
     * @throws Exception
     */
    public function __construct(bool $is_packet_mode = false, $handler = null)
    {
        if ($is_packet_mode) {
            $this->api_url = 'https://tracking.russianpost.ru/fc?wsdl';
            $soap_version  = SOAP_1_1;
        } else {
            $this->api_url = 'https://tracking.russianpost.ru/rtm34?wsdl';
            $soap_version  = SOAP_1_2;
        }

        $this->login    = 'zGbHxpnOZvjXmQ';
        $this->password = 'FL4HtmXWvJXw';
        $this->language = 'RUS';

        $factory = new Factory();

        $this->soap_client = $factory->create(new Client([
            'handler' => $handler ?? Logging::createLoggingHandlerStack(
                'RussianPost',
                'russian-post/' . date('Y-m-d') . '.log'
            ),
        ]), $this->api_url, ['soap_version' => $soap_version]);
    }

    /**
     * Gets the ticket.
     *
     * @param array $tracking_codes Tracking codes.
     *
     * @return mixed The ticket if success, false otherwise.
     */
    public function getTicket(array $tracking_codes)
    {
        $request = [];

        foreach ($tracking_codes as $code) {
            $request['Item']['Barcode'] = $code;
        }

        try {
            $response = $this->soap_client->call('getTicket', [
                'pos:ticketRequest' => [
                    'request'  => $request,
                    'login'    => $this->login,
                    'password' => $this->password,
                    'language' => $this->language,
                ]
            ]);

            return $response->value ?? false;
        } catch (Exception $e) {
            // I'm busy doing nothing.
        }

        return false;
    }

    /**
     * Gets the response by the ticket.
     *
     * @param string $ticket The ticket.
     *
     * @return mixed
     */
    public function getResponseByTicket(string $ticket)
    {
        try {
            $response = $this->soap_client->call('getResponseByTicket', [
                'pos:answerByTicketRequest' => [
                    'ticket'   => $ticket,
                    'login'    => $this->login,
                    'password' => $this->password,
                ]
            ]);

            return $response->value ?? false;
        } catch (Exception $e) {
            // I'm busy doing nothing.
        }

        return false;
    }

    /**
     * Gets the item status.
     *
     * @param string $tracking_code
     *
     * @return mixed
     */
    public function getItemStatus(string $tracking_code)
    {
        $history = $this->getOperationHistory($tracking_code);

        if ($history) {
            $history = is_array($history) ? array_pop($history) : $history;

            $operation_id        = $history->OperationParameters->OperType->Id ?? null;
            $operation_attribute = $history->OperationParameters->OperAttr->Id ?? null;

            if (! empty($operation_id) && ! empty($operation_attribute)) {
                if ($operation_id === 2 && in_array($operation_attribute, [1, 3, 5, 6, 8, 10, 11, 12, 13])) {
                    return [
                        'status' => 'issued',
                        'date'   => $history->OperationParameters->OperDate ?? null
                    ];
                }

                if ($operation_id === 3 && in_array($operation_attribute, [4])) {
                    return [
                        'status' => 'canceled_by_client_upon_receipt',
                        'date'   => $history->OperationParameters->OperDate ?? null
                    ];
                }
            }
        }

        return false;
    }

    /**
     * Gets the operation history.
     *
     * @param string $tracking_code The tracking code.
     *
     * @return mixed
     */
    public function getOperationHistory(string $tracking_code)
    {
        try {
            $response = $this->soap_client->call('getOperationHistory', [
                'oper:getOperationHistory' => [
                    'OperationHistoryRequest' => [
                        'Barcode'     => $tracking_code,
                        'MessageType' => 0,
                        'Language'    => $this->language,
                    ],
                    'AuthorizationHeader'     => [
                        'login'    => $this->login,
                        'password' => $this->password,
                    ],
                ],
            ]);

            return $response->OperationHistoryData->historyRecord ?? false;
        } catch (Exception $e) {
            // I'm busy doing nothing.
        }

        return false;
    }
}
