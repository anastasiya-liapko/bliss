<?php

namespace Tests\App\Models;

use App\Models\Client;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest.
 *
 * @package Tests\App\Models
 */
class ClientTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new Client([]));
    }

    /**
     * Tests the uploadPhotos method.
     *
     * @return void
     */
    public function testUploadPhotos(): void
    {
        $this->assertTrue(method_exists(Client::class, 'uploadPhotos'));
    }

    /**
     * Tests the validateAge method.
     *
     * @return void
     */
    public function testValidateAge(): void
    {
        $this->assertTrue(method_exists(Client::class, 'validateAge'));
    }

    /**
     * Tests the validatePassportIssuedDate method.
     *
     * @return void
     */
    public function testValidatePassportIssuedDate(): void
    {
        $this->assertTrue(method_exists(Client::class, 'validatePassportIssuedDate'));
    }

    /**
     * Tests the validateOnUpdate method.
     *
     * @return void
     */
    public function testValidateOnUpdate(): void
    {
        $this->assertTrue(method_exists(Client::class, 'validateOnUpdate'));
    }

    /**
     * Tests the validateOnCreate method.
     *
     * @return void
     */
    public function testValidateOnCreate(): void
    {
        $this->assertTrue(method_exists(Client::class, 'validateOnCreate'));
    }

    /**
     * Tests the getPhotoClientFaceWithPassportMainSpread method.
     *
     * @return void
     */
    public function testGetPhotoClientFaceWithPassportMainSpread(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPhotoClientFaceWithPassportMainSpread());
    }

    /**
     * Tests the getPhotoPassportMainSpread method.
     *
     * @return void
     */
    public function testGetPhotoPassportMainSpread(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPhotoPassportMainSpread());
    }

    /**
     * Tests the getAdditionalPhone method.
     *
     * @return void
     */
    public function testGetAdditionalPhone(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getAdditionalPhone());
    }

    /**
     * Tests the getPhone method.
     *
     * @return void
     */
    public function testGetPhone(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPhone());
    }

    /**
     * Tests the getEmail method.
     *
     * @return void
     */
    public function testGetEmail(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getEmail());
    }

    /**
     * Tests the getFactApartment method.
     *
     * @return void
     */
    public function testGetFactApartment(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getFactApartment());
    }

    /**
     * Tests the getFactBuilding method.
     *
     * @return void
     */
    public function testGetFactBuilding(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getFactBuilding());
    }

    /**
     * Tests the getFactStreet method.
     *
     * @return void
     */
    public function testGetFactStreet(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getFactStreet());
    }

    /**
     * Tests the getFactCity method.
     *
     * @return void
     */
    public function testGetFactCity(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getFactCity());
    }

    /**
     * Tests the getFactZipCode method.
     *
     * @return void
     */
    public function testGetFactZipCode(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getFactZipCode());
    }

    /**
     * Tests the getIsAddressMatched method.
     *
     * @return void
     */
    public function testGetIsAddressMatched(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsAddressMatched());
    }

    /**
     * Tests the getRegApartment method.
     *
     * @return void
     */
    public function testGetRegApartment(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getRegApartment());
    }

    /**
     * Tests the getRegBuilding method.
     *
     * @return void
     */
    public function testGetRegBuilding(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getRegBuilding());
    }

    /**
     * Tests the getRegStreet method.
     *
     * @return void
     */
    public function testGetRegStreet(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getRegStreet());
    }

    /**
     * Tests the getRegCity method.
     *
     * @return void
     */
    public function testGetRegCity(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getRegCity());
    }

    /**
     * Tests the getRegZipCode method.
     *
     * @return void
     */
    public function testGetRegZipCode(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getRegZipCode());
    }

    /**
     * Tests the getSalary method.
     *
     * @return void
     */
    public function testGetSalary(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSalary());
    }

    /**
     * Tests the getWorkplace method.
     *
     * @return void
     */
    public function testGetWorkplace(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getWorkplace());
    }

    /**
     * Tests the getPassportIssuedDate method.
     *
     * @return void
     */
    public function testGetPassportIssuedDate(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPassportIssuedDate());
    }

    /**
     * Tests the getPassportIssuedBy method.
     *
     * @return void
     */
    public function testGetPassportIssuedBy(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPassportIssuedBy());
    }

    /**
     * Tests the getPassportDivisionCode method.
     *
     * @return void
     */
    public function testGetPassportDivisionCode(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPassportDivisionCode());
    }

    /**
     * Tests the getPassportNumber method.
     *
     * @return void
     */
    public function testGetPassportNumber(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPassportNumber());
    }

    /**
     * Tests the getSnils method.
     *
     * @return void
     */
    public function testGetSnils(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSnils());
    }

    /**
     * Tests the getTin method.
     *
     * @return void
     */
    public function testGetTin(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTin());
    }

    /**
     * Tests the getPreviousLastName method.
     *
     * @return void
     */
    public function testGetPreviousLastName(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPreviousLastName());
    }

    /**
     * Tests the getIsLastNameChanged method.
     *
     * @return void
     */
    public function testGetIsLastNameChanged(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsLastNameChanged());
    }

    /**
     * Tests the getSex method.
     *
     * @return void
     */
    public function testGetSex(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSex());
    }

    /**
     * Tests the getBirthPlace method.
     *
     * @return void
     */
    public function testGetBirthPlace(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBirthPlace());
    }

    /**
     * Tests the getBirthDate method.
     *
     * @return void
     */
    public function testGetBirthDate(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBirthDate());
    }

    /**
     * Tests the getMiddleName method.
     *
     * @return void
     */
    public function testGetMiddleName(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getMiddleName());
    }

    /**
     * Tests the getFirstName method.
     *
     * @return void
     */
    public function testGetFirstName(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getFirstName());
    }

    /**
     * Tests the getLastName method.
     *
     * @return void
     */
    public function testGetLastName(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLastName());
    }

    /**
     * Tests the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
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
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the getDateForProfile method.
     *
     * @return void
     */
    public function testGetDataForProfile(): void
    {
        /** @var Client|MockObject $stub */
        $stub = $this->getMockBuilder(Client::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $expected = [
            'last_name'              => 'Иванов',
            'first_name'             => 'Иван',
            'middle_name'            => 'Иванович',
            'birth_date'             => '1970-01-01',
            'birth_place'            => 'г. Москва',
            'sex'                    => 'male',
            'is_last_name_changed'   => 1,
            'previous_last_name'     => 'Петров',
            'tin'                    => 222222222222,
            'snils'                  => '222-222-222 22',
            'passport_number'        => '22 22 222222',
            'passport_division_code' => '770-001',
            'passport_issued_by'     => 'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР. МОСКВЕ',
            'passport_issued_date'   => '2010-01-01',
            'workplace'              => 'ООО "Ромашка"',
            'salary'                 => 20000,
            'reg_zip_code'           => 101000,
            'reg_city'               => 'Москва',
            'reg_street'             => 'Ленина',
            'reg_building'           => 10,
            'reg_apartment'          => 24,
            'is_address_matched'     => 0,
            'fact_zip_code'          => 625030,
            'fact_city'              => 'Тюмень',
            'fact_street'            => 'Мира',
            'fact_building'          => 5,
            'fact_apartment'         => 1,
            'email'                  => 'ivanov_ii@mail.ru',
            'phone'                  => '72222222222',
            'additional_phone'       => '+7(333)333-33-33',
        ];

        $this->assertEquals($expected, $stub->getDataForProfile());
    }

    /**
     * Tests the update method.
     *
     * @depends testDeleteById
     *
     * @throws \Exception
     */
    public function testUpdate(): void
    {
        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertFalse(
            $client->update(['tin' => 111111111111]),
            'Parameter tin must be unique'
        );
        $this->assertTrue(Client::deleteById($client->getId()));

        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertFalse(
            $client->update(['snils' => '111-111-111 11']),
            'Parameter tin must be unique'
        );
        $this->assertTrue(Client::deleteById($client->getId()));

        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertFalse(
            $client->update(['passport_number' => '11 11 111111']),
            'Parameter tin must be unique'
        );
        $this->assertTrue(Client::deleteById($client->getId()));

        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertFalse(
            $client->update(['email' => 'petrov_pp@mail.ru']),
            'Parameter tin must be unique'
        );
        $this->assertTrue(Client::deleteById($client->getId()));

        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertFalse(
            $client->update(['birth_date' => date('d.m.Y', strtotime('17 years 364 day ago'))]),
            'Age must be over 18 years'
        );
        $this->assertTrue(Client::deleteById($client->getId()));

        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertFalse(
            $client->update(['birth_date' => date('d.m.Y', strtotime('17 years 364 day ago'))]),
            'Age must be over 18 years'
        );
        $this->assertTrue(Client::deleteById($client->getId()));

        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertFalse(
            $client->update(['passport_issued_date' => '30.09.1997']),
            'The passport must be issued no later than 1 October 1997'
        );
        $this->assertTrue(Client::deleteById($client->getId()));

        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertFalse(
            $client->update(['passport_issued_date' => date('d.m.Y', strtotime('tomorrow'))]),
            'Passport cannot be issued in the future'
        );
        $this->assertTrue(Client::deleteById($client->getId()));

        $client = new Client($this->getConstructorData());
        $this->assertTrue($client->create());
        $this->assertTrue(
            $client->update([
                'last_name'   => 'Сидоров',
                'first_name'  => 'Сидр',
                'middle_name' => 'Сидорович',
            ])
        );

        $this->assertEquals('Сидоров', $client->getLastName());
        $this->assertEquals('Сидр', $client->getFirstName());
        $this->assertEquals('Сидорович', $client->getMiddleName());

        $this->assertTrue(Client::deleteById($client->getId()));
    }

    /**
     * Tests the create method.
     *
     * @return Client $client
     * @throws \Exception
     */
    public function testCreate(): Client
    {
        $client = new Client($this->getConstructorData(['tin' => 111111111111]));
        $this->assertFalse($client->create(), 'Parameter tin must be unique');

        $client = new Client($this->getConstructorData(['snils' => '111-111-111 11']));
        $this->assertFalse($client->create(), 'Parameter snils must be unique');

        $client = new Client($this->getConstructorData(['passport_number' => '11 11 111111']));
        $this->assertFalse($client->create(), 'Parameter passport_number must be unique');

        $client = new Client($this->getConstructorData(['email' => 'petrov_pp@mail.ru']));
        $this->assertFalse($client->create(), 'Parameter email must be unique');

        $client = new Client($this->getConstructorData([
            'birth_date' => date('d.m.Y', strtotime('17 years 364 day ago'))
        ]));
        $this->assertFalse($client->create(), 'Age must be over 18 years');

        $client = new Client($this->getConstructorData(['passport_issued_date' => '30.09.1997']));
        $this->assertFalse($client->create(), 'The passport must be issued no later than 1 October 1997');

        $client = new Client($this->getConstructorData([
            'passport_issued_date' => date('d.m.Y', strtotime('tomorrow'))
        ]));
        $this->assertFalse($client->create(), 'Passport cannot be issued in the future');

        $client = new Client($this->getConstructorData([]));
        $this->assertTrue($client->create());

        return $client;
    }

    /**
     * Tests the getPathOfPhotoPassportMainSpread method.
     *
     * @return void
     */
    public function testGetPathOfPhotoPassportMainSpread(): void
    {
        $this->assertEmpty(Client::getPathOfPhotoPassportMainSpread('79999999999'));
    }

    /**
     * Tests the getPathOfPhotoClientFaceWithPassportMainSpread method.
     *
     * @return void
     */
    public function testGetPathOfPhotoClientFaceWithPassportMainSpread(): void
    {
        $this->assertEmpty(Client::getPathOfPhotoClientFaceWithPassportMainSpread('79999999999'));
    }

    /**
     * Tests the deleteById method.
     *
     * @param Client $client
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testDeleteById(Client $client)
    {
        $this->assertTrue(Client::deleteById($client->getId()));
    }

    /**
     * Tests the findByPhone method.
     *
     * @return void
     */
    public function testFindByPhone(): void
    {
        $client = Client::findByPhone('79097391754');

        $this->assertIsObject($client);

        $this->assertObjectHasAttribute('errors', $client);
        $this->assertObjectHasAttribute('id', $client);
        $this->assertObjectHasAttribute('last_name', $client);
        $this->assertObjectHasAttribute('first_name', $client);
        $this->assertObjectHasAttribute('middle_name', $client);
        $this->assertObjectHasAttribute('birth_date', $client);
        $this->assertObjectHasAttribute('birth_place', $client);
        $this->assertObjectHasAttribute('sex', $client);
        $this->assertObjectHasAttribute('is_last_name_changed', $client);
        $this->assertObjectHasAttribute('previous_last_name', $client);
        $this->assertObjectHasAttribute('tin', $client);
        $this->assertObjectHasAttribute('snils', $client);
        $this->assertObjectHasAttribute('passport_number', $client);
        $this->assertObjectHasAttribute('passport_division_code', $client);
        $this->assertObjectHasAttribute('passport_issued_by', $client);
        $this->assertObjectHasAttribute('passport_issued_date', $client);
        $this->assertObjectHasAttribute('workplace', $client);
        $this->assertObjectHasAttribute('salary', $client);
        $this->assertObjectHasAttribute('reg_zip_code', $client);
        $this->assertObjectHasAttribute('reg_city', $client);
        $this->assertObjectHasAttribute('reg_street', $client);
        $this->assertObjectHasAttribute('reg_building', $client);
        $this->assertObjectHasAttribute('reg_apartment', $client);
        $this->assertObjectHasAttribute('is_address_matched', $client);
        $this->assertObjectHasAttribute('fact_zip_code', $client);
        $this->assertObjectHasAttribute('fact_city', $client);
        $this->assertObjectHasAttribute('fact_street', $client);
        $this->assertObjectHasAttribute('fact_building', $client);
        $this->assertObjectHasAttribute('fact_apartment', $client);
        $this->assertObjectHasAttribute('email', $client);
        $this->assertObjectHasAttribute('phone', $client);
        $this->assertObjectHasAttribute('additional_phone', $client);
    }

    /**
     * Tests the findById method.
     *
     * @return void
     */
    public function testFindById(): void
    {
        $client = Client::findById(1);

        $this->assertIsObject($client);

        $this->assertObjectHasAttribute('errors', $client);
        $this->assertObjectHasAttribute('id', $client);
        $this->assertObjectHasAttribute('last_name', $client);
        $this->assertObjectHasAttribute('first_name', $client);
        $this->assertObjectHasAttribute('middle_name', $client);
        $this->assertObjectHasAttribute('birth_date', $client);
        $this->assertObjectHasAttribute('birth_place', $client);
        $this->assertObjectHasAttribute('sex', $client);
        $this->assertObjectHasAttribute('is_last_name_changed', $client);
        $this->assertObjectHasAttribute('previous_last_name', $client);
        $this->assertObjectHasAttribute('tin', $client);
        $this->assertObjectHasAttribute('snils', $client);
        $this->assertObjectHasAttribute('passport_number', $client);
        $this->assertObjectHasAttribute('passport_division_code', $client);
        $this->assertObjectHasAttribute('passport_issued_by', $client);
        $this->assertObjectHasAttribute('passport_issued_date', $client);
        $this->assertObjectHasAttribute('workplace', $client);
        $this->assertObjectHasAttribute('salary', $client);
        $this->assertObjectHasAttribute('reg_zip_code', $client);
        $this->assertObjectHasAttribute('reg_city', $client);
        $this->assertObjectHasAttribute('reg_street', $client);
        $this->assertObjectHasAttribute('reg_building', $client);
        $this->assertObjectHasAttribute('reg_apartment', $client);
        $this->assertObjectHasAttribute('is_address_matched', $client);
        $this->assertObjectHasAttribute('fact_zip_code', $client);
        $this->assertObjectHasAttribute('fact_city', $client);
        $this->assertObjectHasAttribute('fact_street', $client);
        $this->assertObjectHasAttribute('fact_building', $client);
        $this->assertObjectHasAttribute('fact_apartment', $client);
        $this->assertObjectHasAttribute('email', $client);
        $this->assertObjectHasAttribute('phone', $client);
        $this->assertObjectHasAttribute('additional_phone', $client);
    }

    /**
     * Get the constructor data.
     *
     * @param array $data The data
     *
     * @return array The constructor data.
     */
    private function getConstructorData(array $data = []): array
    {
        return [
            'last_name'              => array_key_exists('last_name', $data) ? $data['last_name'] : 'Иванов',
            'first_name'             => array_key_exists('first_name', $data) ? $data['first_name'] : 'Иван',
            'middle_name'            => array_key_exists('middle_name', $data) ? $data['middle_name'] : 'Иванович',
            'birth_date'             => array_key_exists('birth_date', $data) ? $data['birth_date'] : '01.01.1970',
            'birth_place'            => array_key_exists('birth_place', $data) ? $data['birth_place'] : 'г. Москва',
            'sex'                    => array_key_exists('sex', $data) ? $data['sex'] : 'male',
            'is_last_name_changed'   => array_key_exists('is_last_name_changed', $data)
                ? $data['is_last_name_changed'] : 1,
            'previous_last_name'     => array_key_exists('previous_last_name', $data)
                ? $data['previous_last_name'] : 'Петров',
            'tin'                    => array_key_exists('tin', $data) ? $data['tin'] : 222222222222,
            'snils'                  => array_key_exists('snils', $data) ? $data['snils'] : '222-222-222 22',
            'passport_number'        => array_key_exists('passport_number', $data)
                ? $data['passport_number'] : '22 22 222222',
            'passport_division_code' => array_key_exists('passport_division_code', $data)
                ? $data['passport_division_code'] : '770-001',
            'passport_issued_by'     => array_key_exists('passport_issued_by', $data)
                ? $data['passport_issued_by'] : 'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР. МОСКВЕ',
            'passport_issued_date'   => array_key_exists('passport_issued_date', $data)
                ? $data['passport_issued_date'] : '01.01.2010',
            'workplace'              => array_key_exists('workplace', $data) ? $data['workplace'] : 'ООО "Ромашка"',
            'salary'                 => array_key_exists('salary', $data) ? $data['salary'] : 20000,
            'reg_zip_code'           => array_key_exists('reg_zip_code', $data) ? $data['reg_zip_code'] : 101000,
            'reg_city'               => array_key_exists('reg_city', $data) ? $data['reg_city'] : 'Москва',
            'reg_street'             => array_key_exists('reg_street', $data) ? $data['reg_street'] : 'Ленина',
            'reg_building'           => array_key_exists('reg_building', $data) ? $data['reg_building'] : '10',
            'reg_apartment'          => array_key_exists('reg_apartment', $data) ? $data['reg_apartment'] : '24',
            'is_address_matched'     => array_key_exists('is_address_matched', $data) ? $data['is_address_matched'] : 0,
            'fact_zip_code'          => array_key_exists('fact_zip_code', $data) ? $data['fact_zip_code'] : 625030,
            'fact_city'              => array_key_exists('fact_city', $data) ? $data['fact_city'] : 'Тюмень',
            'fact_street'            => array_key_exists('fact_street', $data) ? $data['fact_street'] : 'Мира',
            'fact_building'          => array_key_exists('fact_building', $data) ? $data['fact_building'] : '5',
            'fact_apartment'         => array_key_exists('fact_apartment', $data) ? $data['fact_apartment'] : '1',
            'phone'                  => array_key_exists('phone', $data) ? $data['phone'] : '72222222222',
            'additional_phone'       => array_key_exists('additional_phone', $data)
                ? $data['additional_phone'] : '+7(333)333-33-33',
            'email'                  => array_key_exists('email', $data) ? $data['email'] : 'ivanov_ii@mail.ru',
        ];
    }
}
