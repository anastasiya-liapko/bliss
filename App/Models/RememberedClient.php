<?php

namespace App\Models;

use App\Helper;
use App\Token;
use Core\Model;
use PDO;

/**
 * Class RememberedClient.
 *
 * @package App\Models
 */
class RememberedClient extends Model
{
    /**
     * Error messages.
     *
     * @var array
     */
    private $errors = [];

    /**
     * The client phone
     *
     * @var string|null
     */
    private $phone;

    /**
     * Is verified.
     *
     * @var int
     */
    private $is_verified = 0;

    /**
     * The shop id.
     *
     * @var int|null
     */
    private $shop_id;

    /**
     * The order id.
     *
     * Here an order id is a string, because shops may have id in a string type.
     *
     * @var string|null
     */
    private $order_id;

    /**
     * The order price.
     *
     * @var float|null
     */
    private $order_price;

    /**
     * The callback url.
     *
     * @var string|null
     */
    private $callback_url;

    /**
     * Is the loan postponed.
     *
     * @var int
     */
    private $is_loan_postponed = 0;

    /**
     * The goods.
     *
     * @var string|null
     */
    private $goods;

    /**
     * Is the test mode enabled.
     *
     * @var int
     */
    private $is_test_mode_enabled = 0;

    /**
     * The signature.
     *
     * @var string|null
     */
    private $signature;

    /**
     * The token.
     *
     * @var string|null
     */
    private $token;

    /**
     * The token hash.
     *
     * @var string|null
     */
    private $token_hash;

    /**
     * The expiry token timestamp.
     *
     * @var string|null
     */
    private $token_expires_at;

    /**
     * The sms-code.
     *
     * @var string|null
     */
    private $sms_code;

    /**
     * The sms-code sends at.
     *
     * @var string|null
     */
    private $sms_code_sends_at;

    /**
     * The expiry sms-code timestamp.
     *
     * @var string|null
     */
    private $sms_code_expires_at;

    /**
     * The RememberedClient constructor.
     *
     * @param array $data (optional) Initial property values.
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Finds an model by the token.
     *
     * @param string $token_string The token.
     *
     * @return mixed The phone object if found, false otherwise.
     * @throws \Exception
     */
    public static function findByToken(string $token_string)
    {
        $token      = new Token($token_string);
        $token_hash = $token->getHash();

        $sql = 'SELECT * FROM remembered_clients WHERE token_hash = :token_hash LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Deletes expired records.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function deleteExpiredRecords(): bool
    {
        $sql = 'DELETE FROM remembered_clients WHERE :now > token_expires_at';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':now', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Creates the record.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public function create(): bool
    {
        $this->check();

        if (! empty($this->getErrors())) {
            return false;
        }

        $this->maybeApplyDiscount();

        $token                  = new Token();
        $this->token_hash       = $token->getHash();
        $this->token            = $token->getToken();
        $this->token_expires_at = date('Y-m-d H:i:s', time() + 60 * 60 * 24);
        $this->is_verified      = 0;

        $sql = 'INSERT INTO remembered_clients (token_hash, is_verified, shop_id, order_id, order_price, goods, 
                                callback_url, signature, is_test_mode_enabled, is_loan_postponed, token_expires_at ) 
                                VALUES (:token_hash, :is_verified, :shop_id, :order_id, :order_price, :goods, 
                                        :callback_url, :signature, :is_test_mode_enabled, :is_loan_postponed, 
                                        :token_expires_at)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);
        $stmt->bindValue(':is_verified', $this->getIsVerified(), PDO::PARAM_INT);
        $stmt->bindValue(':shop_id', $this->getShopId(), PDO::PARAM_INT);
        $stmt->bindValue(':order_id', (string)$this->getOrderId(), PDO::PARAM_STR);
        $stmt->bindValue(':order_price', $this->getOrderPrice(), PDO::PARAM_STR);
        $stmt->bindValue(':goods', $this->getGoods(), PDO::PARAM_STR);
        $stmt->bindValue(':callback_url', $this->getCallbackUrl(), PDO::PARAM_STR);
        $stmt->bindValue(':signature', $this->getSignature(), PDO::PARAM_STR);
        $stmt->bindValue(':is_test_mode_enabled', $this->getIsTestModeEnabled(), PDO::PARAM_INT);
        $stmt->bindValue(':is_loan_postponed', $this->getIsLoanPostponed(), PDO::PARAM_INT);
        $stmt->bindValue(':token_expires_at', $this->getTokenExpiresAt(), PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось сохранить данные, попробуйте ещё раз.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Saves the phone number.
     *
     * @param string $phone The phone number.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public function savePhone(string $phone): bool
    {
        $this->phone               = preg_replace('/[-)+(\s]/', '', $phone);
        $this->sms_code            = Helper::generateSmsCode();
        $this->sms_code_expires_at = date('Y-m-d H:i:s', time() + 60 * 30);
        $this->sms_code_sends_at   = date('Y-m-d H:i:s');

        $sql = 'UPDATE remembered_clients SET phone = :phone, sms_code = :sms_code, 
                              sms_code_expires_at = :sms_code_expires_at, sms_code_sends_at = :sms_code_sends_at 
                    WHERE token_hash = :token_hash';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':phone', $this->getPhone(), PDO::PARAM_STR);
        $stmt->bindValue(':sms_code', $this->getSmsCode(), PDO::PARAM_STR);
        $stmt->bindValue(':sms_code_expires_at', $this->getSmsCodeExpiresAt(), PDO::PARAM_STR);
        $stmt->bindValue(':sms_code_sends_at', $this->getSmsCodeSendsAt(), PDO::PARAM_STR);
        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }

        $this->errors[] = 'Не удалось сохранить телефон, попробуйте ещё раз.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Regenerates the sms-code.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public function regenerateSmsCode(): bool
    {
        $sms_code                  = Helper::generateSmsCode();
        $this->sms_code_expires_at = date('Y-m-d H:i:s', time() + 60 * 30);
        $this->sms_code_sends_at   = date('Y-m-d H:i:s');

        $sql = 'UPDATE remembered_clients 
                SET sms_code = :sms_code, sms_code_sends_at = :sms_code_sends_at, 
                    sms_code_expires_at = :sms_code_expires_at
		        WHERE token_hash = :token_hash AND :now > ADDDATE(sms_code_sends_at, INTERVAL 3 MINUTE)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);
        $stmt->bindValue(':sms_code', $sms_code, PDO::PARAM_STR);
        $stmt->bindValue(':sms_code_expires_at', $this->getSmsCodeExpiresAt(), PDO::PARAM_STR);
        $stmt->bindValue(':sms_code_sends_at', $this->getSmsCodeSendsAt(), PDO::PARAM_STR);
        $stmt->bindValue(':now', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $this->sms_code = $sms_code; // @codeCoverageIgnore
            $this->resetWrongInputsNumber(); // @codeCoverageIgnore

            return true; // @codeCoverageIgnore
        }

        return false;
    }

    /**
     * Checks the sms-code.
     *
     * @param string $code The code.
     *
     * @return bool True if a code is correct, false otherwise.
     * @throws \Exception
     */
    public function checkCode(string $code): bool
    {
        $this->checkTotalWrongInputsNumber();

        if (! empty($this->getErrors())) {
            return false;
        }

        $this->checkWrongInputsNumber();

        if (! empty($this->getErrors())) {
            $this->incrementTotalWrongInputsNumber();

            return false;
        }

        $sql = 'SELECT * FROM remembered_clients 
            WHERE token_hash = :token_hash AND sms_code = :sms_code AND sms_code_expires_at >= :now LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);
        $stmt->bindValue(':sms_code', $code, PDO::PARAM_STR);
        $stmt->bindValue(':now', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->fetch()) {
            $this->resetWrongInputsNumber();

            return true;
        }

        $this->incrementWrongInputsNumber();
        $this->incrementTotalWrongInputsNumber();

        $this->errors[] = 'Код не подходит.';

        return false;
    }

    /**
     * Verifies the phone.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public function verifyPhone(): bool
    {
        $db = static::getDB();

        $this->is_verified = 1;

        $sql = 'UPDATE remembered_clients SET is_verified = 1 WHERE token_hash = :token_hash';

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось подтвердить номер телефона.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Is the token expired.
     *
     * @return bool True if expired, false otherwise.
     * @throws \Exception
     */
    public function isTokenExpired(): bool
    {
        return time() > strtotime($this->getTokenExpiresAt());
    }

    /**
     * Gets errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Gets the phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Gets the shop id.
     *
     * @return int|null
     */
    public function getShopId(): ?int
    {
        return $this->shop_id;
    }

    /**
     * Gets is verified.
     *
     * @return int
     */
    public function getIsVerified(): int
    {
        return $this->is_verified;
    }

    /**
     * Gets the order id.
     *
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    /**
     * Gets the order price.
     *
     * @return float|null
     */
    public function getOrderPrice(): ?float
    {
        return $this->order_price;
    }

    /**
     * Gets the callback url.
     *
     * @return string|null
     */
    public function getCallbackUrl(): ?string
    {
        return $this->callback_url;
    }

    /**
     * Get is the loan postponed.
     *
     * @return int
     */
    public function getIsLoanPostponed(): int
    {
        return $this->is_loan_postponed;
    }

    /**
     * Gets the goods.
     *
     * @return string|null
     */
    public function getGoods(): ?string
    {
        return $this->goods;
    }

    /**
     * Gets is the test mode enabled.
     *
     * @return int
     */
    public function getIsTestModeEnabled(): int
    {
        return $this->is_test_mode_enabled;
    }

    /**
     * Get the signature.
     *
     * @return string|null
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * Gets the token.
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Gets the token hash.
     *
     * @return string|null
     */
    public function getTokenHash(): ?string
    {
        return $this->token_hash;
    }

    /**
     * Gets the token expire.
     *
     * @return string|null
     */
    public function getTokenExpiresAt(): ?string
    {
        return $this->token_expires_at;
    }

    /**
     * Gets the sms-code.
     *
     * @return string|null
     */
    public function getSmsCode(): ?string
    {
        return $this->sms_code;
    }

    /**
     * Gets the sms-code sends at.
     *
     * @return string|null
     */
    public function getSmsCodeSendsAt(): ?string
    {
        return $this->sms_code_sends_at;
    }

    /**
     * Gets the sms-code expire.
     *
     * @return string|null
     */
    public function getSmsCodeExpiresAt(): ?string
    {
        return $this->sms_code_expires_at;
    }

    /**
     * Resets the wrong input.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    private function resetWrongInputsNumber(): bool
    {
        $sql = 'UPDATE remembered_clients SET sms_code_wrong_inputs_number = 0 WHERE token_hash = :token_hash';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Checks total wrong inputs number.
     *
     * @return void
     * @throws \Exception
     */
    private function checkTotalWrongInputsNumber(): void
    {
        $sql = 'SELECT * FROM remembered_clients WHERE token_hash = :token_hash LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch();

        if ($result['sms_code_total_wrong_inputs_number'] >= 10) {
            LockedPhone::lock($result['phone']);

            $this->errors[] = '10 или более раз введён неверный код. Ваш номер заблокирован на сутки.';
        }
    }

    /**
     * Increments total wrong inputs number.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    private function incrementTotalWrongInputsNumber(): bool
    {
        $sql = 'UPDATE remembered_clients 
                    SET sms_code_total_wrong_inputs_number = sms_code_total_wrong_inputs_number + 1 
                WHERE token_hash = :token_hash';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Checks the locks number.
     *
     * @return void
     * @throws \Exception
     */
    private function checkWrongInputsNumber(): void
    {
        $sql = 'SELECT * FROM remembered_clients 
                WHERE sms_code_wrong_inputs_number < 3 AND token_hash = :token_hash LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);

        $stmt->execute();

        if (! $stmt->fetch()) {
            $this->errors[] = '3 раза введён неверный код. Когда таймер дойдёт до нуля, нажмите ссылку '
                . '"Запросить повторную отправку SMS".';
        }
    }

    /**
     * Increments the wrong inputs number.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    private function incrementWrongInputsNumber(): bool
    {
        $sql = 'UPDATE remembered_clients 
            SET sms_code_wrong_inputs_number = sms_code_wrong_inputs_number + 1 WHERE token_hash = :token_hash';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Checks the initial data.
     *
     * @return void
     */
    private function check(): void
    {
        if (! $shop = Shop::findById($this->shop_id)) {
            $this->errors[] = 'Магазин не зарегистрирован на нашем сервисе, обратитесь к администратору магазина.';

            return;
        }

        if (! $shop->getIsActivated()) {
            $this->errors[] = 'К сожалению, работа с магазином ' . $shop->getName() . ' временно не доступна.';

            return;
        }

        $signature = Request::createRequestSignature(
            $this->shop_id,
            $this->order_id,
            $this->order_price,
            $this->goods,
            $this->callback_url,
            $this->is_loan_postponed,
            $this->is_test_mode_enabled,
            $shop->getSecretKey()
        );

        // TODO remove it after update all shops.
        $is_old_integration = $shop->getIsOldIntegration();

        // TODO remove it after update all shops.
        if ($is_old_integration) {
            // @codeCoverageIgnoreStart
            $signature_old = Request::createOldRequestSignature(
                $this->shop_id,
                $this->order_id,
                $this->order_price,
                $this->callback_url,
                $this->is_loan_postponed,
                $this->goods,
                $this->is_test_mode_enabled,
                $shop->getSecretKey()
            );

            if ($this->signature !== $signature && $this->signature !== $signature_old) {
                $this->errors[] = 'Неверная подпись магазина.';

                return;
            }

            // TODO remove it after update all shops.
            if (Helper::isSerialized($this->goods)) {
                $this->goods = $this->oldGetSerializedGoods();
            }
            // @codeCoverageIgnoreEnd
        }

        // TODO refactor it after update all shops.
        if (! $is_old_integration) {
            if ($this->signature !== $signature) {
                $this->errors[] = 'Неверная подпись магазина.';

                return;
            }
        }

        $goods_array = json_decode($this->goods, true);

        if (! is_array($goods_array) || empty($goods_array)) {
            $this->errors[] = 'Массив товаров пустой или имеет неверный формат.';

            return;
        }

        $goods_total_price = 0;

        foreach ($goods_array as $item) {
            if (! is_array($item) || empty($item)) {
                $this->errors[] = 'Массив товара пустой или имеет неверный формат.';

                return;
            }

            if (! array_key_exists('name', $item) || empty($item['name'])) {
                $this->errors[] = 'Название товара — обязательный параметр.';

                return;
            }

            if (! array_key_exists('price', $item) || empty($item['price'])) {
                $this->errors[] = 'Стоимость товара — обязательный параметр.';

                return;
            }

            if (! array_key_exists('quantity', $item) || empty($item['quantity'])) {
                $this->errors[] = 'Количество — обязательный параметр.';

                return;
            }

            if (! array_key_exists('is_returnable', $item) || is_null($item['is_returnable'])) {
                $this->errors[] = 'Можно ли вернуть товар — обязательный параметр.';

                return;
            }

            for ($i = 0; $i < $item['quantity']; $i++) {
                $goods_total_price += $item['price'];
            }
        }

        if ($this->order_price > $goods_total_price) {
            $this->errors[] = 'Общая стоимость товаров меньше стоимости заказа.';

            return;
        }
    }

    /**
     * Maybe apply a discount.
     *
     * @return void
     */
    private function maybeApplyDiscount()
    {
        $goods_array = json_decode($this->goods, true);

        $goods_total_price = 0;

        foreach ($goods_array as $item) {
            for ($i = 0; $i < $item['quantity']; $i++) {
                $goods_total_price += $item['price'];
            }
        }

        if ($goods_total_price > $this->order_price) {
            $delta = $goods_total_price - $this->order_price;

            foreach ($goods_array as &$item) {
                $item['price'] -= ($delta / $goods_total_price) * $item['price'];
            }
        }

        $this->goods = json_encode($goods_array);
    }

    /**
     * Gets serialized goods.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @todo delete it after update all plugins.
     *
     */
    private function oldGetSerializedGoods(): string
    {
        $goods_array = @unserialize(htmlspecialchars_decode($this->goods));

        if (is_array($goods_array)) {
            foreach ($goods_array as &$item) {
                if (! isset($item['quantity'])) {
                    $item['quantity'] = 1;
                }
            }
        }

        return json_encode($goods_array);
    }
}
