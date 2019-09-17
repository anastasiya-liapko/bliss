<?php

namespace App\MFI;

use App\Helper;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use App\Models\Client;

/**
 * Class Webbankir.
 *
 * @package App\MFI
 */
class Webbankir extends MFI
{
    /**
     * The token.
     *
     * @var string
     */
    private $token;

    /**
     * The expiration.
     *
     * @var string
     */
    private $expiration;

    /**
     * The merchant id.
     *
     * @var int
     */
    private $merchant_id;

    /**
     * The shop id in the mfi.
     *
     * @var int
     */
    private $mfi_shop_id;

    /**
     * The password.
     *
     * @var string
     */
    private $password;

    /**
     * The customer limit.
     *
     * @var float
     */
    protected $customer_limit;

    /**
     * Webbankir constructor.
     *
     * @param array $request_params The crediting data.
     * @param array $api_params The api parameters.
     * @param array $loan_params (optional) The loan data.
     * @param HandlerStack $handler (optional) The handler stack.
     * @param HandlerStack $handler_telegram_bot (optional) The telegram bot handler stack.
     *
     * @throws Exception
     */
    public function __construct(
        array $request_params,
        array $api_params,
        array $loan_params = [],
        $handler = null,
        $handler_telegram_bot = null
    ) {
        $this->request_params = $request_params;

        $this->mfi_name = 'WEBBANKIR';
        $this->mfi_slug = 'webbankir';

        $this->api_url     = 'https://webbankir.partners/api-ishop/v1/';
        $this->merchant_id = $api_params['merchantId'] ?? null;
        $this->mfi_shop_id = $api_params['shopId'] ?? null;
        $this->password    = $api_params['password'] ?? null;

        if ($this->getRequestParam('is_test_mode_enabled') == 1) {
            $this->api_url = 'https://demo.webbankir.partners/api-ishop/v1/';
        }

        $this->status                  = $loan_params['status'] ?? null;
        $this->customer_id             = $loan_params['customer_id'] ?? null;
        $this->contract_id             = $loan_params['contract_id'] ?? null;
        $this->loan_id                 = $loan_params['loan_id'] ?? null;
        $this->loan_body               = $loan_params['loan_body'] ?? null;
        $this->loan_cost               = $loan_params['loan_cost'] ?? null;
        $this->loan_period             = $loan_params['loan_period'] ?? 180;
        $this->loan_daily_percent_rate = $loan_params['loan_daily_percent_rate'] ?? null;
        $this->loan_terms_link         = $loan_params['loan_terms_link'] ?? null;

        $this->handler_telegram_bot = $handler_telegram_bot;

        $this->http_client = new HttpClient([
            'base_uri' => $this->api_url,
            'headers'  => [
                'Content-Type' => 'application/json',
            ],
            'handler'  => $handler ?? $this->getLoggingHandlerStack(),
        ]);
    }

    /**
     * Starts.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    public function start()
    {
        if (empty($this->token) || time() > strtotime($this->expiration)) {
            if (! $this->receiveToken()) {
                return false;
            }
        }

        try {
            $response = $this->http_client->get("user?phone={$this->getRequestParam('client_phone')}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
            ]);

            // The client is in the system and has an unspent pre-approved limit.
            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                $this->customer_id    = $result->data->id;
                $this->customer_limit = $result->data->current_limit;

                return $this->createLoan();
            }

            // The client is available in the system, but has no pre-approved limit.
            if ($response->getStatusCode() === 204) {
                $this->status = 'waiting_limit';

                return $this->getResponse();
            }
        } catch (Exception $e) {
            if ($e instanceof RequestException) {
                // The client was not found in the system, you must send the client to the interfaces
                // for applying for a limit.
                if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 404) {
                    return $this->createCustomer();
                }
            }

            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Confirms a loan by a client.
     *
     * @param string $sms_code The sms-code.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    public function confirmLoanByClient(string $sms_code)
    {
        if (empty($this->token) || time() > strtotime($this->expiration)) {
            if (! $this->receiveToken()) {
                return false;
            }
        }

        try {
            $response = $this->http_client->patch("user/{$this->customer_id}/sale/{$this->loan_id}/code/{$sms_code}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
            ]);

            // The code is correct, the loan is signed.
            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                $this->status      = 'issued_postponed';
                $this->contract_id = $result->data->contract_number;
                $this->loan_id     = $result->data->id;

                return $this->getResponse();
            }
        } catch (Exception $e) {
            if ($e instanceof RequestException) {
                // The code is incorrect.
                if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 400) {
                    $this->status = 'wrong_sms_code';

                    return $this->getResponse();
                }

                // No customer or loan found.
                if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 404) {
                    $this->status = 'data_not_found';

                    return $this->getResponse();
                }
            }

            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Confirms a loan by a shop.
     *
     * @param string $datetime The datetime.
     * @param string $tracking_code (optional) The tracking code.
     * @param string $service_name (optional) The service name.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    public function confirmLoanByShop(string $datetime, string $tracking_code = '', string $service_name = '')
    {
        if (empty($this->token) || time() > strtotime($this->expiration)) {
            if (! $this->receiveToken()) {
                return false;
            }
        }

        try {
            $response = $this->http_client->post("user/{$this->customer_id}/sale/{$this->loan_id}/delivery", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
                'body'    => json_encode([
                    'tracking_number' => $tracking_code,
                    'service_name'    => $service_name,
                    'datetime'        => $datetime,
                ]),
            ]);

            // The loan is confirmed by shop.
            if ($response->getStatusCode() === 201) {
                $this->status = 'issued';

                return $this->getResponse();
            }
        } catch (Exception $e) {
            if ($e instanceof RequestException) {
                // No customer or loan found.
                if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 404) {
                    $this->status = 'data_not_found';

                    return $this->getResponse();
                }
            }

            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Cancels the loan by a client.
     *
     * Webbankir did not have a cancel action for a client, that is why the declineLoanByShop method is used here.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    public function cancelLoanByClient()
    {
        return $this->declineLoanByShop();
    }

    /**
     * Decline a loan by a shop.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    public function declineLoanByShop()
    {
        if (empty($this->token) || time() > strtotime($this->expiration)) {
            if (! $this->receiveToken()) {
                return false;
            }
        }

        try {
            $response = $this->http_client->delete("user/{$this->customer_id}/sale/{$this->loan_id}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
            ]);

            // The loan is canceled by shop.
            if ($response->getStatusCode() === 204) {
                $this->status = 'deleted';

                return $this->getResponse();
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Sends a code to confirm a loan.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    public function sendConfirmLoanCode()
    {
        if (empty($this->token) || time() > strtotime($this->expiration)) {
            if (! $this->receiveToken()) {
                return false;
            }
        }

        try {
            $response = $this->http_client->put("user/{$this->customer_id}/sale/{$this->loan_id}/code", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
            ]);

            // The code is sent to the client's phone.
            if ($response->getStatusCode() === 201) {
                $this->status = 'approved';

                $terms = $this->getTerms();

                if (is_array($terms)) {
                    if (isset($terms['amount']) && isset($terms['loan_body'])) {
                        $this->loan_cost = $terms['amount'] - $terms['loan_body'];
                    }

                    $this->loan_body               = $terms['loan_body'] ?? null;
                    $this->loan_daily_percent_rate = $terms['daily_percent_rate'] ?? null;
                }

                return $this->getResponse();
            }
        } catch (Exception $e) {
            if ($e instanceof RequestException) {
                // No customer or loan found.
                if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 404) {
                    $this->status = 'data_not_found';

                    return $this->getResponse();
                }
            }

            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Receives token.
     *
     * @return bool True if success, false otherwise.
     * @throws Exception
     */
    private function receiveToken(): bool
    {
        try {
            $response = $this->http_client->get(
                "merchant/{$this->merchant_id}/shop/{$this->mfi_shop_id}/token?password={$this->password}"
            );

            // The request was processed successfully.
            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                $this->token      = $result->data->token;
                $this->expiration = $result->data->expiration;

                return true;
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Creates a loan.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    private function createLoan()
    {
        try {
            $response = $this->http_client->post("user/{$this->customer_id}/sale", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                    'Content-Type'  => 'application/json',
                ],
                'body'    => json_encode([
                    'period'    => $this->loan_period / 30,
                    'own_funds' => 0,
                    'products'  => $this->getNormalizedGoods($this->getRequestParam('goods')),
                ]),
            ]);

            // The loan has been created, the loan code must be signed from the SMS message.
            if ($response->getStatusCode() === 201) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                $this->loan_id = $result->data->id;

                return $this->sendConfirmLoanCode();
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Creates a customer.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    private function createCustomer()
    {
        try {
            $response = $this->http_client->post("customer", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                    'Content-Type'  => 'application/json',
                ],
                'body'    => json_encode([
                    'first_name'   => $this->getRequestParam('client_first_name'),
                    'last_name'    => $this->getRequestParam('client_last_name'),
                    'middle_name'  => $this->getRequestParam('client_middle_name'),
                    'mobile_phone' => $this->getRequestParam('client_phone'),
                ])
            ]);

            // The parameters are correct, user created.
            // At the time of receiving this code, the user is sent an SMS message to his phone number.
            if ($response->getStatusCode() === 201) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                $this->customer_id = $result->data->id;

                return $this->confirmCustomer($this->getRequestParam('sms_code'));
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Confirms a customer.
     *
     * @param string $sms_code The sms-code.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    private function confirmCustomer(string $sms_code)
    {
        try {
            $response = $this->http_client->post("customer/{$this->customer_id}/code", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                    'Content-Type'  => 'application/json',
                ],
                'body'    => json_encode([
                    'sms_code' => $sms_code,
                ])
            ]);

            // The code is correct, you can proceed to a further step.
            if ($response->getStatusCode() === 204) {
                return $this->sendCustomerPassportData();
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Sends customer passport data.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    private function sendCustomerPassportData()
    {
        $passport_division_code = preg_replace('/-/', '', $this->getRequestParam('client_passport_division_code'));
        $passport_number        = preg_replace('/\s/', '', $this->getRequestParam('client_passport_number'));
        $birth_date             = $this->getRequestParam('client_birth_date');
        $passport_issued_date   = $this->getRequestParam('client_passport_issued_date');

        $passport_division_name = $this->getPassportDivisionName($passport_division_code);
        $address_fiases         = $this->getAddress($this->getRequestParam('client_reg_city') . ', '
            . $this->getRequestParam('client_reg_street') . ', ' . $this->getRequestParam('client_reg_building') . ', '
            . $this->getRequestParam('client_reg_apartment'));

        if ($passport_division_name !== false && $address_fiases !== false) {
            try {
                $response = $this->http_client->post("customer/{$this->customer_id}/passport", [
                    'headers' => [
                        'Authorization' => "Bearer {$this->token}",
                        'Content-Type'  => 'application/json',
                    ],
                    'body'    => json_encode([
                        'birth_day'         => $birth_date,
                        'birth_place'       => $this->getRequestParam('client_birth_place'),
                        'series_and_number' => $passport_number,
                        'division_code'     => $passport_division_code,
                        'issue_date'        => $passport_issued_date,
                        'issued_by'         => $passport_division_name,
                        'address'           => [
                            'postal_code'        => $address_fiases['data']['postal_code'],
                            'region'             => $address_fiases['data']['region_kladr_id'],
                            'city'               => $address_fiases['data']['city'],
                            'settlement'         => $address_fiases['data']['settlement'],
                            'street'             => $address_fiases['data']['street'],
                            'do_not_have_street' => empty($address_fiases['data']['street']) ? 1 : 0,
                            'house'              => $address_fiases['data']['house'],
                            'flat'               => $this->getRequestParam('client_reg_apartment'),
                        ],
                        'address_fiases'    => [
                            'value'              => $address_fiases['value'],
                            'region_fias_id'     => $address_fiases['data']['region_fias_id'],
                            'city_fias_id'       => $address_fiases['data']['city_fias_id'],
                            'settlement_fias_id' => $address_fiases['data']['settlement_fias_id'],
                            'street_fias_id'     => $address_fiases['data']['street_fias_id'],
                            'house_fias_id'      => $address_fiases['data']['house_fias_id'],
                        ],
                    ])
                ]);

                if ($response->getStatusCode() === 204) {
                    return $this->sendPhotoPassportMainSpread();
                }
            } catch (Exception $e) {
                $this->sendAlarmByTelegramBot($e);
            }
        }

        return false;
    }

    /**
     * Sends customer passport photo.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    private function sendPhotoPassportMainSpread()
    {
        $passport_photo_path = Client::getPathOfPhotoPassportMainSpread($this->getRequestParam('client_phone'));

        if (! file_exists($passport_photo_path)) {
            return $this->sendPhotoCustomerFaceWithPassportMainSpread();
        }

        try {
            $response = $this->http_client->post("customer/{$this->customer_id}/photo/passport", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                    'Content-Type'  => 'application/json',
                ],
                'body'    => json_encode([
                    'photo' => Helper::getFileInBase64($passport_photo_path),
                ])
            ]);

            if ($response->getStatusCode() === 204) {
                return $this->sendPhotoCustomerFaceWithPassportMainSpread();
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Sends customer with passport photo.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    private function sendPhotoCustomerFaceWithPassportMainSpread()
    {
        $passport_photo_path = Client::getPathOfPhotoClientFaceWithPassportMainSpread(
            $this->getRequestParam('client_phone')
        );

        if (! file_exists($passport_photo_path)) {
            return $this->sendCustomerSalaryData();
        }

        try {
            $response = $this->http_client->post("customer/{$this->customer_id}/photo/customer_and_passport", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                    'Content-Type'  => 'application/json',
                ],
                'body'    => json_encode([
                    'photo' => Helper::getFileInBase64($passport_photo_path),
                ])
            ]);

            if ($response->getStatusCode() === 204) {
                return $this->sendCustomerSalaryData();
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Sends customer salary data.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    private function sendCustomerSalaryData()
    {
        try {
            $response = $this->http_client->post("customer/{$this->customer_id}/beneficial-and-salary", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                    'Content-Type'  => 'application/json',
                ],
                'body'    => json_encode([
                    'salary'             => (int)$this->getRequestParam('client_salary'),
                    'inn'                => $this->getRequestParam('client_tin'),
                    'snils'              => preg_replace('/[-)+(\s]/', '', $this->getRequestParam('client_snils')),
                    'application_amount' => 30000,
                    'work_name'          => $this->getRequestParam('client_workplace'),
                    'beneficial'         => null,
                ])
            ]);

            if ($response->getStatusCode() === 204) {
                $this->status = 'waiting_limit';

                return $this->getResponse();
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Gets a schedule.
     *
     * @return array|false The array of response, false otherwise.
     * @throws Exception
     */
    private function getTerms()
    {
        try {
            $response = $this->http_client->patch("schedule", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
                'body'    => json_encode([
                    'period'   => $this->loan_period / 30,
                    'user_id'  => $this->customer_id,
                    'products' => $this->getNormalizedGoods($this->getRequestParam('goods')),
                ])
            ]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents(), true);

                return $result['data'];
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Gets an address.
     *
     * @param string $address The address.
     *
     * @return array|false The array of address, false otherwise.
     * @throws Exception
     */
    private function getAddress(string $address)
    {
        try {
            $response = $this->http_client->get("address?search=" . urldecode($address), [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents(), true);

                return $result['data']['suggestions'][0];
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Gets a passport division name.
     *
     * @param string $passport_division_code The passport division code.
     *
     * @return string|false The passport division name, false otherwise.
     * @throws Exception
     */
    private function getPassportDivisionName(string $passport_division_code)
    {
        try {
            $response = $this->http_client->get("passport/division_code/{$passport_division_code}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents(), true);

                return $result['data']['value'];
            }
        } catch (Exception $e) {
            $this->sendAlarmByTelegramBot($e);
        }

        return false;
    }

    /**
     * Gets normalized goods.
     *
     * @param string $goods
     * @return array
     */
    private function getNormalizedGoods(string $goods): array
    {
        $goods            = json_decode($goods, true);
        $goods_normalized = [];

        foreach ($goods as $item) {
            for ($i = 0; $i < $item['quantity']; $i++) {
                $goods_normalized[] = [
                    'name'           => $item['name'],
                    'price'          => $item['price'],
                    'is_returnable'  => (bool)$item['is_returnable'],
                    'operation_type' => 'loan',
                ];
            }
        }

        return $goods_normalized;
    }
}
