<?php

namespace Tests\App\Models;

use App\Models\Order;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderTest.
 *
 * @package Tests\App\Models
 */
class OrderTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new Order());
    }

    /**
     * Tests the checkIsOrderExist method.
     *
     * @return void
     */
    public function testCheckIsOrderExist(): void
    {
        $this->assertTrue(method_exists(Order::class, 'checkIsOrderExist'));
    }

    /**
     * Tests the getTrackingCode method.
     *
     * @return void
     */
    public function testGetTrackingCode(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTrackingCode());
    }

    /**
     * Tests the getDeliveryServiceId method.
     *
     * @return void
     */
    public function testGetDeliveryServiceId(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDeliveryServiceId());
    }

    /**
     * Tests the getTimeOfCreation method.
     *
     * @return void
     */
    public function testGetTimeOfCreation(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTimeOfCreation());
    }

    /**
     * Tests the getStatus method.
     *
     * @return void
     */
    public function testGetStatus(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getStatus());
    }

    /**
     * Tests the getGoods method.
     *
     * @return void
     */
    public function testGetGoods(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getGoods());
    }

    /**
     * Tests the getOrderPrice method.
     *
     * @return void
     */
    public function testGetOrderPrice(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getOrderPrice());
    }

    /**
     * Tests the getOrderIdInShop method.
     *
     * @return void
     */
    public function testGetOrderIdInShop(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getOrderIdInShop());
    }

    /**
     * Tests the getShopId method.
     *
     * @return void
     */
    public function testGetShopId(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getShopId());
    }

    /**
     * Tests the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getId());
    }

    /**
     * Tests the getErrors method.
     *
     * @return void
     */
    public function testGetErrors(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the getStatusName method.
     *
     * @return void
     */
    public function testGetStatusName(): void
    {
        /** @var Order|MockObject $stub */
        $stub = $this->getMockBuilder(Order::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(['getStatus'])
                     ->getMock();

        $stub->method('getStatus')
             ->will($this->onConsecutiveCalls(
                 'waiting_for_registration',
                 'pending_by_mfi',
                 'declined_by_mfi',
                 'canceled_by_client',
                 'mfi_did_not_answer',
                 'approved_by_mfi',
                 'pending_by_shop',
                 'waiting_for_delivery',
                 'waiting_for_payment',
                 'paid',
                 'declined_by_shop',
                 'canceled_by_client_upon_receipt'
             ));

        $this->assertEquals('Ожидает заполнения кредитной заявки', $stub->getStatusName());
        $this->assertEquals('На рассмотрении в ФО', $stub->getStatusName());
        $this->assertEquals('Отклонён ФО', $stub->getStatusName());
        $this->assertEquals('Отклонён покупателем', $stub->getStatusName());
        $this->assertEquals('МФО не успели ответить', $stub->getStatusName());
        $this->assertEquals('Ожидает подтверждения покупателя', $stub->getStatusName());
        $this->assertEquals('Ожидает подтверждения магазина', $stub->getStatusName());
        $this->assertEquals('Ожидает доставки', $stub->getStatusName());
        $this->assertEquals('Ожидает оплаты', $stub->getStatusName());
        $this->assertEquals('Оплачен', $stub->getStatusName());
        $this->assertEquals('Отклонён магазином', $stub->getStatusName());
        $this->assertEquals('Отменён покупателем при получении', $stub->getStatusName());
    }

    /**
     * Tests the updateTrackingCode method.
     *
     * @depends testCreate
     *
     * @param Order $order The order.
     *
     * @return void
     */
    public function testUpdateTrackingCode(Order $order)
    {
        $this->assertTrue($order->updateTrackingCode('RA644000003RU'));
    }

    /**
     * Tests the updateDeliveryServiceId method.
     *
     * @depends testCreate
     *
     * @param Order $order The order.
     *
     * @return void
     */
    public function testUpdateDeliveryServiceId(Order $order)
    {
        $this->assertTrue($order->updateDeliveryServiceId(1));
    }

    /**
     * Tests the updateStatus method.
     *
     * @depends testCreate
     *
     * @param Order $order The order.
     *
     * @return void
     */
    public function testUpdateStatus(Order $order)
    {
        $this->assertTrue($order->updateStatus('pending_by_mfi'));
    }

    /**
     * Tests the create method.
     *
     * @return Order
     * @throws \Exception
     */
    public function testCreate(): Order
    {
        $order = new Order($this->getConstructorData(['order_id_in_shop' => 1]));
        $this->assertFalse($order->create());

        $order = new Order($this->getConstructorData());
        $this->assertTrue($order->create());

        return $order;
    }

    /**
     * Tests the isOrderBelongToShop method.
     *
     * @return void
     * @throws \Exception
     */
    public function testIsOrderBelongToShop(): void
    {
        $this->assertTrue(Order::isOrderBelongToShop(1, 1));
        $this->assertFalse(Order::isOrderBelongToShop(1, 2));
    }

    /**
     * Tests the getIncludeSql method.
     *
     * @return void
     */
    public function testGetIncludeSql(): void
    {
        $this->assertTrue(method_exists(Order::class, 'getIncludeSql'));
    }

    /**
     * Tests the getFilterSql method.
     *
     * @return void
     */
    public function testGetFilterSql(): void
    {
        $this->assertTrue(method_exists(Order::class, 'getFilterSql'));
    }

    /**
     * Tests the getFilterEnd method.
     *
     * @return void
     */
    public function testGetFilterEnd(): void
    {
        $this->assertTrue(method_exists(Order::class, 'getFilterEnd'));
    }

    /**
     * Tests the getFilterStart method.
     *
     * @return void
     */
    public function testGetFilterStart(): void
    {
        $this->assertTrue(method_exists(Order::class, 'getFilterStart'));
    }

    /**
     * Tests the getFilterBy method.
     *
     * @return void
     */
    public function testGetFilterBy(): void
    {
        $this->assertTrue(method_exists(Order::class, 'getFilterBy'));
    }

    /**
     * Tests the getSortSql method.
     *
     * @return void
     */
    public function testGetSortSql(): void
    {
        $this->assertTrue(method_exists(Order::class, 'getSortSql'));
    }

    /**
     * Tests the getTypeSql method.
     *
     * @return void
     */
    public function testGetTypeSql(): void
    {
        $this->assertTrue(method_exists(Order::class, 'getTypeSql'));
    }

    /**
     * Tests the getOrdersStatistics method.
     *
     * @return void
     */
    public function testGetOrdersStatistics(): void
    {
        $statistics = Order::getOrdersStatistics(
            1,
            1,
            'time_of_creation',
            '20.01.2019',
            '22.01.2019'
        );

        $this->assertIsArray($statistics);

        $this->assertEquals('1', $statistics['total']);
        $this->assertEquals(24990.00, $statistics['total_cost']);
    }

    /**
     * Tests the getOrders method.
     *
     * @depends testDeleteById
     *
     * @return void
     */
    public function testGetOrders(): void
    {
        $orders = Order::getOrders(1, 0);
        $this->assertIsArray($orders);
        $this->assertArrayHasKey('id', $orders[0]);
        $this->assertArrayHasKey('order_id_in_shop', $orders[0]);
        $this->assertArrayHasKey('order_price', $orders[0]);
        $this->assertArrayHasKey('goods', $orders[0]);
        $this->assertArrayHasKey('status', $orders[0]);
        $this->assertArrayHasKey('status', $orders[0]);
        $this->assertArrayHasKey('time_of_creation', $orders[0]);
        $this->assertArrayHasKey('tracking_code', $orders[0]);
        $this->assertArrayHasKey('delivery_service_name', $orders[0]);
        $this->assertArrayHasKey('mfi_name', $orders[0]);

        $orders = Order::getOrders(1, 1);
        $this->assertIsArray($orders);

        $orders = Order::getOrders(1, 2);
        $this->assertIsArray($orders);

        $orders = Order::getOrders(1, 3);
        $this->assertIsArray($orders);

        $orders = Order::getOrders(1, 4);
        $this->assertIsArray($orders);

        $orders = Order::getOrders(1, 5);
        $this->assertIsArray($orders);

        $orders = Order::getOrders(1, 6);
        $this->assertIsArray($orders);

        $orders = Order::getOrders(1, 7);
        $this->assertIsArray($orders);

        $orders = Order::getOrders(1, 8);
        $this->assertIsArray($orders);

        $orders = Order::getOrders(
            1,
            3,
            0,
            10,
            'desc',
            'time_of_creation',
            'time_of_creation',
            '20.01.2019',
            '22.01.2019'
        );
        $this->assertIsArray($orders);

        $orders = Order::getOrders(
            1,
            3,
            0,
            10,
            'desc',
            'status',
            'status',
            'declined_by_shop'
        );
        $this->assertIsArray($orders);

        $orders = Order::getOrders(
            1,
            8,
            0,
            10,
            'desc',
            'tracking_code',
            'tracking_code',
            'RA644000002RU'
        );
        $this->assertIsArray($orders);

        $orders = Order::getOrders(
            1,
            8,
            0,
            10,
            'desc',
            'order_id_in_shop',
            'order_id_in_shop',
            1,
            10,
            '1, 2, 3, 4'
        );
        $this->assertIsArray($orders);
    }

    /**
     * Tests the getOrder method.
     *
     * @return void
     */
    public function testGetOrder(): void
    {
        $order = Order::getOrder(1);

        $this->assertIsArray($order);

        $this->assertArrayHasKey('id', $order);
        $this->assertArrayHasKey('order_id_in_shop', $order);
        $this->assertArrayHasKey('order_price', $order);
        $this->assertArrayHasKey('goods', $order);
        $this->assertArrayHasKey('status', $order);
        $this->assertArrayHasKey('time_of_creation', $order);
        $this->assertArrayHasKey('tracking_code', $order);
        $this->assertArrayHasKey('request_id', $order);
        $this->assertArrayHasKey('loan_id', $order);
        $this->assertArrayHasKey('mfi_customer_id', $order);
        $this->assertArrayHasKey('mfi_contract_id', $order);
        $this->assertArrayHasKey('is_mfi_paid', $order);
        $this->assertArrayHasKey('customer_name', $order);
        $this->assertArrayHasKey('customer_phone', $order);
        $this->assertArrayHasKey('customer_additional_phone', $order);
        $this->assertArrayHasKey('mfi_name', $order);
        $this->assertArrayHasKey('delivery_service_name', $order);
    }

    /**
     * Tests the getUniqueOrderIdInShop method.
     *
     * @return void
     */
    public function testGetUniqueOrderIdInShop(): void
    {
        $this->assertIsNumeric(Order::getUniqueOrderIdInShop(1));
    }

    /**
     * Tests the normalizeGoods method.
     *
     * @return void
     */
    public function testNormalizeGoods(): void
    {
        $goods_serialized = '[{"name":"\u0421\u043c\u0430\u0440\u0442\u0444\u043e\u043d Apple iPhone XR 64GB RED",'
            . '"price":63990,"quantity":1,"is_returnable":1}]';

        $this->assertEquals(
            'Смартфон Apple iPhone XR 64GB RED — 63990 руб. — 1 шт.',
            Order::normalizeGoods($goods_serialized)
        );
    }

    /**
     * Tests the getByDeliveryServiceSlug method.
     *
     * @return void
     */
    public function testGetByDeliveryServiceSlug(): void
    {
        $order = Order::getByDeliveryServiceSlug('russian_post');

        $this->assertIsArray($order);
        $this->assertArrayHasKey('id', $order[0]);
        $this->assertArrayHasKey('shop_id', $order[0]);
        $this->assertArrayHasKey('order_id_in_shop', $order[0]);
        $this->assertArrayHasKey('order_price', $order[0]);
        $this->assertArrayHasKey('goods', $order[0]);
        $this->assertArrayHasKey('status', $order[0]);
        $this->assertArrayHasKey('time_of_creation', $order[0]);
        $this->assertArrayHasKey('delivery_service_id', $order[0]);
        $this->assertArrayHasKey('tracking_code', $order[0]);
    }

    /**
     * Tests the deleteById method.
     *
     * @param Order $order The order model.
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testDeleteById(Order $order)
    {
        $this->assertTrue(Order::deleteById($order->getId()));
    }

    /**
     * Tests the findByOrderIdInShop method.
     *
     * @return void
     */
    public function testFindByOrderIdInShop(): void
    {
        $order = Order::findByOrderIdInShop(1, 1);

        $this->assertIsObject($order);
        $this->assertObjectHasAttribute('errors', $order);
        $this->assertObjectHasAttribute('id', $order);
        $this->assertObjectHasAttribute('shop_id', $order);
        $this->assertObjectHasAttribute('order_id_in_shop', $order);
        $this->assertObjectHasAttribute('order_price', $order);
        $this->assertObjectHasAttribute('goods', $order);
        $this->assertObjectHasAttribute('status', $order);
        $this->assertObjectHasAttribute('time_of_creation', $order);
        $this->assertObjectHasAttribute('delivery_service_id', $order);
        $this->assertObjectHasAttribute('tracking_code', $order);
    }

    /**
     * Tests the findByToken method.
     *
     * @return void
     * @throws \Exception
     */
    public function testFindByToken(): void
    {
        $order = Order::findByToken('43d987204b339d8637c72341185e9429');

        $this->assertIsObject($order);
        $this->assertObjectHasAttribute('errors', $order);
        $this->assertObjectHasAttribute('id', $order);
        $this->assertObjectHasAttribute('shop_id', $order);
        $this->assertObjectHasAttribute('order_id_in_shop', $order);
        $this->assertObjectHasAttribute('order_price', $order);
        $this->assertObjectHasAttribute('goods', $order);
        $this->assertObjectHasAttribute('status', $order);
        $this->assertObjectHasAttribute('time_of_creation', $order);
        $this->assertObjectHasAttribute('delivery_service_id', $order);
        $this->assertObjectHasAttribute('tracking_code', $order);
    }

    /**
     * Tests the findById method.
     *
     * @return void
     */
    public function testFindById(): void
    {
        $order = Order::findById(1);

        $this->assertIsObject($order);
        $this->assertObjectHasAttribute('errors', $order);
        $this->assertObjectHasAttribute('id', $order);
        $this->assertObjectHasAttribute('shop_id', $order);
        $this->assertObjectHasAttribute('order_id_in_shop', $order);
        $this->assertObjectHasAttribute('order_price', $order);
        $this->assertObjectHasAttribute('goods', $order);
        $this->assertObjectHasAttribute('status', $order);
        $this->assertObjectHasAttribute('time_of_creation', $order);
        $this->assertObjectHasAttribute('delivery_service_id', $order);
        $this->assertObjectHasAttribute('tracking_code', $order);
    }

    /**
     * Get the constructor data.
     *
     * @param array $data The data.
     *
     * @return array The constructor data.
     */
    private function getConstructorData(array $data = []): array
    {
        return [
            'shop_id'          => array_key_exists('shop_id', $data) ? $data['shop_id'] : 1,
            'order_id_in_shop' => array_key_exists('order_id_in_shop', $data) ? $data['order_id_in_shop'] : 10,
            'order_price'      => array_key_exists('order_price', $data) ? $data['order_price'] : 3000,
            'goods'            => array_key_exists('goods', $data) ? $data['goods'] : 'a:1:{i:0;a:4:{s:4:"name";'
                . 's:69:"Наушники внутриканальные Sony MDR-EX15LP Black";s:5:"price";i:3000;s:8:"quantity";'
                . 'i:1;s:13:"is_returnable";i:1;}}',
            'status'           => array_key_exists('status', $data) ? $data['status'] : 'pending_by_mfi',
        ];
    }
}
