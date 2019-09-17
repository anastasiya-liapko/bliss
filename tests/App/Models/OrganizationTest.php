<?php

namespace Tests\App\Models;

use App\Models\Organization;
use App\Morpher;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class OrganizationTest.
 *
 * @package Tests\App\Models
 */
class OrganizationTest extends TestCase
{
    /**
     * Tests class.
     *
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new Organization());
    }

    /**
     * Tests the createQuestionnaireFl115Document method.
     *
     * @return void
     */
    public function testCreateQuestionnaireFl115Document(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'createQuestionnaireFl115Document'));
    }

    /**
     * Tests the createContractDocument method.
     *
     * @return void
     */
    public function testCreateContractDocument(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'createContractDocument'));
    }

    /**
     * Tests the createJoiningApplicationDocument method.
     *
     * @return void
     */
    public function testCreateJoiningApplicationDocument(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'createJoiningApplicationDocument'));
    }

    /**
     * Tests the getSignedDocuments method.
     *
     * @return void
     */
    public function testGetSignedDocuments(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'getSignedDocuments'));
    }

    /**
     * Tests the getDocuments method.
     *
     * @return void
     */
    public function testGetDocuments(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'getDocuments'));
    }

    /**
     * Tests the validateAge method.
     *
     * @return void
     */
    public function testValidateAge(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'validateAge'));
    }

    /**
     * Tests the validatePassportIssuedDate method.
     *
     * @return void
     */
    public function testValidatePassportIssuedDate(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'validatePassportIssuedDate'));
    }

    /**
     * Tests the validateOnCreate method.
     *
     * @return void
     */
    public function testValidateOnCreate(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'validateOnCreate'));
    }

    /**
     * Tests the getDocumentJoiningApplication method.
     *
     * @return void
     */
    public function testGetDocumentJoiningApplication(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentJoiningApplication());
    }

    /**
     * Tests the getDocumentQuestionnaireFl115 method.
     *
     * @return void
     */
    public function testGetDocumentQuestionnaireFl115(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentQuestionnaireFl115());
    }

    /**
     * Tests the getDocumentContract method.
     *
     * @return void
     */
    public function testGetDocumentContract(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentContract());
    }

    /**
     * Tests the getDocumentStatuteOfCurrentEdition method.
     *
     * @return void
     */
    public function testGetDocumentStatuteOfCurrentEdition(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentStatuteOfCurrentEdition());
    }

    /**
     * Tests the getDocumentOrderOnAppointment method.
     *
     * @return void
     */
    public function testGetDocumentOrderOnAppointment(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentOrderOnAppointment());
    }

    /**
     * Tests the getDocumentBin method.
     *
     * @return void
     */
    public function testGetDocumentBin(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentBin());
    }

    /**
     * Tests the getDocumentParticipantsDecision method.
     *
     * @return void
     */
    public function testGetDocumentParticipantsDecision(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentParticipantsDecision());
    }

    /**
     * Tests the getDocumentStatuteWithTaxMark method.
     *
     * @return void
     */
    public function testGetDocumentStatuteWithTaxMark(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentStatuteWithTaxMark());
    }

    /**
     * Tests the getDocumentPassport method.
     *
     * @return void
     */
    public function testGetDocumentPassport(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentPassport());
    }

    /**
     * Tests the getDocumentSnils method.
     *
     * @return void
     */
    public function testGetDocumentSnils(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getDocumentSnils());
    }

    /**
     * Tests the getPhone method.
     *
     * @return void
     */
    public function testGetPhone(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPhone());
    }

    /**
     * Tests the getIsDocumentsChecked method.
     *
     * @return void
     */
    public function testGetIsDocumentsChecked(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsDocumentsChecked());
    }

    /**
     * Tests the getEmail method.
     *
     * @return void
     */
    public function testGetEmail(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getEmail());
    }

    /**
     * Tests the getBossBirthPlace method.
     *
     * @return void
     */
    public function testGetBossBirthPlace(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossBirthPlace());
    }

    /**
     * Tests the getBossBirthDate method.
     *
     * @return void
     */
    public function testGetBossBirthDate(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossBirthDate());
    }

    /**
     * Tests the getBossPassportIssuedBy method.
     *
     * @return void
     */
    public function testGetBossPassportIssuedBy(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossPassportIssuedBy());
    }

    /**
     * Tests the getBossPassportDivisionCode method.
     *
     * @return void
     */
    public function testGetBossPassportDivisionCode(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossPassportDivisionCode());
    }

    /**
     * Tests the getBossPassportIssuedDate method.
     *
     * @return void
     */
    public function testGetBossPassportIssuedDate(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossPassportIssuedDate());
    }

    /**
     * Tests the getBossPassportNumber method.
     *
     * @return void
     */
    public function testGetBossPassportNumber(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossPassportNumber());
    }

    /**
     * Tests the getBossBasisActsIssuedDate method.
     *
     * @return void
     */
    public function testGetBossBasisActsIssuedDate(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossBasisActsIssuedDate());
    }

    /**
     * Tests the getBossBasisActsNumber method.
     *
     * @return void
     */
    public function testGetBossBasisActsNumber(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossBasisActsNumber());
    }

    /**
     * Tests the getBossBasisActs method.
     *
     * @return void
     */
    public function testGetBossBasisActs(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossBasisActs());
    }

    /**
     * Tests the getBossPosition method.
     *
     * @return void
     */
    public function testGetBossPosition(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossPosition());
    }

    /**
     * Tests the getBossFullName method.
     *
     * @return void
     */
    public function testGetBossFullName(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBossFullName());
    }

    /**
     * Tests the getSettlementAccount method.
     *
     * @return void
     */
    public function testGetSettlementAccount(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSettlementAccount());
    }

    /**
     * Tests the getCorrespondentAccount method.
     *
     * @return void
     */
    public function testGetCorrespondentAccount(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getCorrespondentAccount());
    }

    /**
     * Tests the getBankName method.
     *
     * @return void
     */
    public function testGetBankName(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBankName());
    }

    /**
     * Tests the getBik method.
     *
     * @return void
     */
    public function testGetBik(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getBik());
    }

    /**
     * Tests the getFactAddress method.
     *
     * @return void
     */
    public function testGetFactAddress(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getFactAddress());
    }

    /**
     * Tests the getRegistrationAddress method.
     *
     * @return void
     */
    public function testGetRegistrationAddress(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getRegistrationAddress());
    }

    /**
     * Tests the getLegalAddress method.
     *
     * @return void
     */
    public function testGetLegalAddress(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLegalAddress());
    }

    /**
     * Tests the getCategoryId method.
     *
     * @return void
     */
    public function testGetCategoryId(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getCategoryId());
    }

    /**
     * Tests the getLicenseNumber method.
     *
     * @return void
     */
    public function testGetLicenseNumber(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLicenseNumber());
    }

    /**
     * Tests the getLicenseType method.
     *
     * @return void
     */
    public function testGetLicenseType(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLicenseType());
    }

    /**
     * Tests the getIsLicensedActivity method.
     *
     * @return void
     */
    public function testGetIsLicensedActivity(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsLicensedActivity());
    }

    /**
     * Tests the getBin method.
     *
     * @return void
     */
    public function testGetBin(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getBin());
    }

    /**
     * Tests the getCio method.
     *
     * @return void
     */
    public function testGetCio(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getCio());
    }

    /**
     * Tests the getTin method.
     *
     * @return void
     */
    public function testGetTin(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTin());
    }

    /**
     * Tests the getLegalName method.
     *
     * @return void
     */
    public function testGetLegalName(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLegalName());
    }

    /**
     * Tests the getVat method.
     *
     * @return void
     */
    public function testGetVat(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getVat());
    }

    /**
     * Tests the getType method.
     *
     * @return void
     */
    public function testGetType(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getType());
    }

    /**
     * Tests the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
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
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the createDocuments method.
     *
     * @return void
     */
    public function testCreateDocuments(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'createDocuments'));
    }

    /**
     * Tests the uploadFiles method.
     *
     * @return void
     */
    public function testUploadFiles(): void
    {
        $this->assertTrue(method_exists(Organization::class, 'uploadFiles'));
    }

    /**
     * Tests the saveSignedDocuments method.
     *
     * @return void
     */
    public function testSaveSignedDocuments(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles'])
                     ->getMock();

        $stub->method('uploadFiles')
             ->willReturn(true);

        $this->assertTrue($stub->saveSignedDocuments([], [], []));
    }

    /**
     * Tests the getBossBasisActsFullInfo method.
     *
     * @return void
     */
    public function testGetBossBasisActsFullInfo(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(['getBossBasisActsName', 'getBossBasisActsNumber', 'getBossBasisActsIssuedDate'])
                     ->getMock();

        $stub->method('getBossBasisActsName')
             ->will($this->onConsecutiveCalls('Доверенности', ''));

        $stub->method('getBossBasisActsNumber')
             ->willReturn('55 АА 1662602');

        $stub->method('getBossBasisActsIssuedDate')
             ->willReturn('12.09.2015');

        $this->assertEquals('Доверенности 55 АА 1662602 от 12.09.2015', $stub->getBossBasisActsFullInfo());
        $this->assertEquals('', $stub->getBossBasisActsFullInfo());
    }

    /**
     * Tests the getBossBasisActsName method.
     *
     * @return void
     */
    public function testGetBossBasisActsName(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(['getBossBasisActs'])
                     ->getMock();

        $stub->method('getBossBasisActs')
             ->will($this->onConsecutiveCalls('statute', 'proxy', 'trust_management_agreement', 'certificate', ''));

        $this->assertEquals('Устава', $stub->getBossBasisActsName());
        $this->assertEquals('Доверенности', $stub->getBossBasisActsName());
        $this->assertEquals('Договора доверительного управления', $stub->getBossBasisActsName());
        $this->assertEquals('Свидетельства', $stub->getBossBasisActsName());
        $this->assertEquals('', $stub->getBossBasisActsName());
    }

    /**
     * Tests the maybeGetBossFullNameInGenitiveCase method.
     *
     * @return void
     * @throws \Exception
     */
    public function testMaybeGetBossFullNameInGenitiveCase(): void
    {
        /** @var Morpher|MockObject $stub_murpher */
        $stub_morpher = $this->getMockBuilder(Morpher::class)
                             ->setMethods(['getInclinedWord'])
                             ->getMock();

        $stub_morpher->method('getInclinedWord')
                     ->will($this->onConsecutiveCalls(
                         (object)[
                             'Р'   => 'Иванова Ивана Ивановича',
                             'Д'   => 'Иванову Ивану Ивановичу',
                             'В'   => 'Иванова Ивана Ивановича',
                             'Т'   => 'Ивановым Иваном Ивановичем',
                             'П'   => 'Иванове Иване Ивановиче',
                             'ФИО' => [
                                 'Ф' => 'Иванов',
                                 'И' => 'Иван',
                                 'О' => 'Иванович',
                             ],
                         ],
                         false
                     ));

        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(),
                         $this->getEntrepreneurConstructorFiles(),
                         $stub_morpher
                     ])
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals('Иванова Ивана Ивановича', $stub->maybeGetBossFullNameInGenitiveCase());
        $this->assertEquals('Иванов Иван Иванович', $stub->maybeGetBossFullNameInGenitiveCase());
    }

    /**
     * Tests the getBossNameAndInitials method.
     *
     * @return void
     */
    public function testGetBossNameAndInitials(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(['getBossFullName'])
                     ->getMock();

        $stub->method('getBossFullName')
             ->will($this->onConsecutiveCalls(
                 'Петров Пётр Петрович',
                 'Петров Пётр',
                 'Петров',
                 '  Петров  ',
                 ''
             ));

        $this->assertEquals('Петров П.П.', $stub->getBossNameAndInitials());
        $this->assertEquals('Петров П.', $stub->getBossNameAndInitials());
        $this->assertEquals('Петров', $stub->getBossNameAndInitials());
        $this->assertEquals('Петров', $stub->getBossNameAndInitials());
        $this->assertEquals('', $stub->getBossNameAndInitials());
    }

    /**
     * Tests the getOrganizationName method.
     *
     * @return void
     */
    public function testGetOrganizationName(): void
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setMethods(['getType', 'getLegalName', 'getBossFullName'])
                     ->getMock();

        $stub->method('getType')
             ->will($this->onConsecutiveCalls('llc', 'entrepreneur'));

        $stub->method('getLegalName')
             ->willReturn('ООО "Мега"');

        $stub->method('getBossFullName')
             ->willReturn('Петров Пётр Петрович');

        $this->assertEquals('Общество с ограниченной ответственностью «Мега»', $stub->getOrganizationName());
        $this->assertEquals('ИП «Петров Пётр Петрович»', $stub->getOrganizationName());
    }

    /**
     * Tests the create method.
     *
     * @return Organization
     * @throws \Rakit\Validation\RuleQuashException
     */
    public function testCreate(): Organization
    {
        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(['tin' => '111111111111']),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles', 'createDocuments'])
                     ->getMock();
        $stub->method('uploadFiles')
             ->willReturn(true);
        $stub->method('createDocuments')
             ->willReturn(true);
        $this->assertFalse($stub->create(), 'Parameter tin must be unique');

        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(['bin' => '111111111111111']),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles', 'createDocuments'])
                     ->getMock();
        $stub->method('uploadFiles')
             ->willReturn(true);
        $stub->method('createDocuments')
             ->willReturn(true);
        $this->assertFalse($stub->create(), 'Parameter bin must be unique');

        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(['settlement_account' => '11111111111111111111']),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles', 'createDocuments'])
                     ->getMock();
        $stub->method('uploadFiles')
             ->willReturn(true);
        $stub->method('createDocuments')
             ->willReturn(true);
        $this->assertFalse($stub->create(), 'Parameter settlement_account must be unique');

        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(['email' => 'petrov_pp@mail.ru']),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles', 'createDocuments'])
                     ->getMock();
        $stub->method('uploadFiles')
             ->willReturn(true);
        $stub->method('createDocuments')
             ->willReturn(true);
        $this->assertFalse($stub->create(), 'Parameter email must be unique');

        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(['boss_passport_issued_date' => '30.09.1997']),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles', 'createDocuments'])
                     ->getMock();
        $stub->method('uploadFiles')
             ->willReturn(true);
        $stub->method('createDocuments')
             ->willReturn(true);
        $this->assertFalse($stub->create(), 'The passport must be issued no later than 1 October 1997');

        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(['boss_birth_date' => '01.01.2019']),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles', 'createDocuments'])
                     ->getMock();
        $stub->method('uploadFiles')
             ->willReturn(true);
        $stub->method('createDocuments')
             ->willReturn(true);
        $this->assertFalse($stub->create(), 'Boss must be of legal age');

        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData([
                             'boss_passport_issued_date' => date('d.m.Y', strtotime('tomorrow'))
                         ]),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles', 'createDocuments'])
                     ->getMock();
        $stub->method('uploadFiles')
             ->willReturn(true);
        $stub->method('createDocuments')
             ->willReturn(true);
        $this->assertFalse($stub->create(), 'Passport cannot be issued in the future');

        /** @var Organization|MockObject $stub */
        $stub = $this->getMockBuilder(Organization::class)
                     ->setConstructorArgs([
                         $this->getEntrepreneurConstructorData(),
                         $this->getEntrepreneurConstructorFiles(),
                     ])
                     ->setMethods(['uploadFiles', 'createDocuments'])
                     ->getMock();

        $stub->method('uploadFiles')
             ->willReturn(true);
        $stub->method('createDocuments')
             ->willReturn(true);

        $this->assertTrue($stub->create());

        return $stub;
    }

    /**
     * Tests the getRussianDocumentName method.
     *
     * @return void
     */
    public function testGetRussianDocumentName(): void
    {
        $this->assertEquals('СНИЛС', Organization::getRussianDocumentName('document_snils'));
        $this->assertEquals('Копия паспорта', Organization::getRussianDocumentName('document_passport'));
        $this->assertEquals(
            'Устав с отметкой налогового органа',
            Organization::getRussianDocumentName('document_statute_with_tax_mark')
        );
        $this->assertEquals(
            'Протокол общего собрания участников',
            Organization::getRussianDocumentName('document_participants_decision')
        );
        $this->assertEquals('ОГРН', Organization::getRussianDocumentName('document_bin'));
        $this->assertEquals(
            'Приказ о назначении единоличного исполнительного органа',
            Organization::getRussianDocumentName('document_order_on_appointment')
        );
        $this->assertEquals(
            'Устав действующей редакции',
            Organization::getRussianDocumentName('document_statute_of_current_edition')
        );
        $this->assertEquals('Соглашение', Organization::getRussianDocumentName('document_contract'));
        $this->assertEquals(
            'Анкета-опросник по 115 ФЗ',
            Organization::getRussianDocumentName('document_questionnaire_fl_115')
        );
        $this->assertEquals(
            'Заявление о присоединении',
            Organization::getRussianDocumentName('document_joining_application')
        );
        $this->assertEquals('', Organization::getRussianDocumentName('test'));
    }

    /**
     * Tests the getTemplateUrl method.
     *
     * @return void
     */
    public function testGetTemplateUrl(): void
    {
        $this->assertEquals(
            '/documents/templates/soglashenie.docx',
            Organization::getTemplateUrl('soglashenie')
        );
    }

    /**
     * Tests the getTransliteratedDocumentName method.
     *
     * @return void
     */
    public function testGetTransliteratedDocumentName(): void
    {
        $this->assertEquals('snils', Organization::getTransliteratedDocumentName('document_snils'));
        $this->assertEquals('pasport', Organization::getTransliteratedDocumentName('document_passport'));
        $this->assertEquals(
            'ustav_s_otmetkoj_nalogovogo_organa',
            Organization::getTransliteratedDocumentName('document_statute_with_tax_mark')
        );
        $this->assertEquals(
            'protokol_obshchego_sobraniya_uchastnikov',
            Organization::getTransliteratedDocumentName('document_participants_decision')
        );
        $this->assertEquals('ogrn', Organization::getTransliteratedDocumentName('document_bin'));
        $this->assertEquals(
            'prikaz_o_naznachenii_edinolichnogo_ispolnitelnogo_organa',
            Organization::getTransliteratedDocumentName('document_order_on_appointment')
        );
        $this->assertEquals(
            'ustav_dejstvuyushchej_redakcii',
            Organization::getTransliteratedDocumentName('document_statute_of_current_edition')
        );
        $this->assertEquals('soglashenie', Organization::getTransliteratedDocumentName('document_contract'));
        $this->assertEquals(
            'anketa_fz_115',
            Organization::getTransliteratedDocumentName('document_questionnaire_fl_115')
        );
        $this->assertEquals(
            'zayavlenie_o_prisoedinenii',
            Organization::getTransliteratedDocumentName('document_joining_application')
        );
        $this->assertEquals('', Organization::getTransliteratedDocumentName('test'));
    }

    /**
     * Tests the getOrganizationCategoryName method.
     *
     * @return void
     */
    public function testGetOrganizationCategoryName(): void
    {
        $this->assertEquals('прочее', Organization::getOrganizationCategoryName(1));
    }

    /**
     * Tests the getOrganizationCategories method.
     *
     * @return void
     */
    public function testGetOrganizationCategories(): void
    {
        $organization_categories = Organization::getOrganizationCategories();

        $this->assertIsArray($organization_categories);
        $this->assertArrayHasKey('id', $organization_categories[0]);
        $this->assertArrayHasKey('name', $organization_categories[0]);
    }

    /**
     * Tests the deleteById method.
     *
     * @param Organization $organization
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testDeleteById(Organization $organization)
    {
        $this->assertTrue(Organization::deleteById($organization->getId()));
    }

    /**
     * Tests the findById method.
     *
     * @return void
     */
    public function testFindById(): void
    {
        $organization = Organization::findById(1);

        $this->assertIsObject($organization);

        $this->assertObjectHasAttribute('errors', $organization);
        $this->assertObjectHasAttribute('type', $organization);
        $this->assertObjectHasAttribute('vat', $organization);
        $this->assertObjectHasAttribute('legal_name', $organization);
        $this->assertObjectHasAttribute('tin', $organization);
        $this->assertObjectHasAttribute('cio', $organization);
        $this->assertObjectHasAttribute('bin', $organization);
        $this->assertObjectHasAttribute('is_licensed_activity', $organization);
        $this->assertObjectHasAttribute('license_type', $organization);
        $this->assertObjectHasAttribute('license_number', $organization);
        $this->assertObjectHasAttribute('category_id', $organization);
        $this->assertObjectHasAttribute('legal_address', $organization);
        $this->assertObjectHasAttribute('registration_address', $organization);
        $this->assertObjectHasAttribute('fact_address', $organization);
        $this->assertObjectHasAttribute('bik', $organization);
        $this->assertObjectHasAttribute('bank_name', $organization);
        $this->assertObjectHasAttribute('correspondent_account', $organization);
        $this->assertObjectHasAttribute('settlement_account', $organization);
        $this->assertObjectHasAttribute('boss_full_name', $organization);
        $this->assertObjectHasAttribute('boss_position', $organization);
        $this->assertObjectHasAttribute('boss_basis_acts', $organization);
        $this->assertObjectHasAttribute('boss_basis_acts_number', $organization);
        $this->assertObjectHasAttribute('boss_basis_acts_issued_date', $organization);
        $this->assertObjectHasAttribute('boss_passport_number', $organization);
        $this->assertObjectHasAttribute('boss_passport_issued_date', $organization);
        $this->assertObjectHasAttribute('boss_passport_division_code', $organization);
        $this->assertObjectHasAttribute('boss_passport_issued_by', $organization);
        $this->assertObjectHasAttribute('boss_birth_date', $organization);
        $this->assertObjectHasAttribute('boss_birth_place', $organization);
        $this->assertObjectHasAttribute('email', $organization);
        $this->assertObjectHasAttribute('phone', $organization);
        $this->assertObjectHasAttribute('is_documents_checked', $organization);
    }

    /**
     * Get the entrepreneur constructor data.
     *
     * @param array $data The data.
     *
     * @return array The constructor data.
     */
    private function getEntrepreneurConstructorData(array $data = [])
    {
        return [
            'type'                        => array_key_exists('type', $data) ? $data['type'] : 'entrepreneur',
            'vat'                         => array_key_exists('vat', $data) ? $data['vat'] : 10,
            'legal_name'                  => array_key_exists('legal_name', $data) ? $data['legal_name'] : null,
            'tin'                         => array_key_exists('tin', $data) ? $data['tin'] : '222222222222',
            'cio'                         => array_key_exists('cio', $data) ? $data['cio'] : null,
            'bin'                         => array_key_exists('bin', $data) ? $data['bin'] : '222222222222222',
            'is_licensed_activity'        => array_key_exists('is_licensed_activity', $data)
                ? $data['is_licensed_activity'] : 0,
            'license_type'                => array_key_exists('license_type', $data) ? $data['license_type'] : null,
            'license_number'              => array_key_exists('license_number', $data) ? $data['license_number'] : null,
            'category_id'                 => array_key_exists('category_id', $data) ? $data['category_id'] : 1,
            'legal_address'               => array_key_exists('legal_address', $data) ? $data['legal_address'] : null,
            'registration_address'        => array_key_exists('registration_address', $data)
                ? $data['registration_address'] : 'г. Москва, ул. Ленина, д. 10, кв. 20',
            'fact_address'                => array_key_exists('fact_address', $data)
                ? $data['fact_address'] : 'г. Москва, ул. Ленина, д. 10, кв. 20',
            'bik'                         => array_key_exists('bik', $data) ? $data['bik'] : '222222222',
            'bank_name'                   => array_key_exists('bank_name', $data) ? $data['bank_name'] : 'Сбербанк',
            'correspondent_account'       => array_key_exists('correspondent_account', $data)
                ? $data['correspondent_account'] : '22222222222222222222',
            'settlement_account'          => array_key_exists('settlement_account', $data)
                ? $data['settlement_account'] : '22222222222222222222',
            'boss_full_name'              => array_key_exists('boss_full_name', $data)
                ? $data['boss_full_name'] : 'Иванов Иван Иванович',
            'boss_position'               => array_key_exists('boss_position', $data) ? $data['boss_position'] : null,
            'boss_basis_acts'             => array_key_exists('boss_basis_acts', $data)
                ? $data['boss_basis_acts'] : null,
            'boss_basis_acts_number'      => array_key_exists('boss_basis_acts_number', $data)
                ? $data['boss_basis_acts_number'] : null,
            'boss_basis_acts_issued_date' => array_key_exists('boss_basis_acts_issued_date', $data)
                ? $data['boss_basis_acts_issued_date'] : null,
            'boss_passport_number'        => array_key_exists('boss_passport_number', $data)
                ? $data['boss_passport_number'] : '22 22 222222',
            'boss_passport_issued_date'   => array_key_exists('boss_passport_issued_date', $data)
                ? $data['boss_passport_issued_date'] : '01.01.2010',
            'boss_passport_division_code' => array_key_exists('boss_passport_division_code', $data)
                ? $data['boss_passport_division_code'] : '770-001',
            'boss_passport_issued_by'     => array_key_exists('boss_passport_issued_by', $data)
                ? $data['boss_passport_issued_by'] : 'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР. МОСКВЕ',
            'boss_birth_date'             => array_key_exists('boss_birth_date', $data)
                ? $data['boss_birth_date'] : '01.01.1980',
            'boss_birth_place'            => array_key_exists('boss_birth_place', $data)
                ? $data['boss_birth_place'] : 'Москва',
            'email'                       => array_key_exists('email', $data) ? $data['email'] : 'ivanov_ii@mail.ru',
            'phone'                       => array_key_exists('phone', $data) ? $data['phone'] : '+7(222)222-22-22',
        ];
    }

    /**
     * Get entrepreneur constructor files.
     *
     * @return array The constructor data.
     */
    private function getEntrepreneurConstructorFiles(): array
    {
        return [
            'document_snils'    => [],
            'document_passport' => [],
            'document_bin'      => [],
        ];
    }
}
