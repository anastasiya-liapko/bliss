<?php

namespace Tests\App;

use App\Helper;
use App\SiteInfo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class HelperTest.
 *
 * @package Tests\App
 */
class HelperTest extends TestCase
{
    /**
     * Tests the getFileInBase64 method.
     *
     * @return void
     */
    public function testGetFileInBase64(): void
    {
        $this->assertFalse(Helper::getFileInBase64(''));

        $this->assertIsString(
            Helper::getFileInBase64(SiteInfo::getDocumentRoot() . '/public/assets/front/img/favicon.png')
        );
    }

    /**
     * Tests the getCleanPhone method.
     *
     * @return void
     */
    public function testGetCleanPhone(): void
    {
        $this->assertEquals('+71111111111', Helper::getCleanPhone('+7(111)111-11-11'));
    }

    /**
     * Tests the isSerialized method.
     *
     * @return void
     */
    public function testIsSerialized(): void
    {
        $string = '[{"name":"Apple iPhone X","price":69999,"is_returnable":1}]';
        $this->assertFalse(Helper::isSerialized($string));

        $string = 'a:1:{i:0;a:4:{s:4:"name";s:69:"Наушники внутриканальные Sony MDR-EX15LP Black";s:5:"price";i:3000;'
            . 's:8:"quantity";i:1;s:13:"is_returnable";i:1;}}';
        $this->assertTrue(Helper::isSerialized($string));
    }

    /**
     * Tests the execInBackground method.
     *
     * @return void
     */
    public function testExecInBackground(): void
    {
        $this->assertTrue(method_exists(Helper::class, 'execInBackground'));
    }

    /**
     * Tests the fileForceDownload method.
     *
     * @return void
     */
    public function testFileForceDownload(): void
    {
        $this->assertTrue(method_exists(Helper::class, 'fileForceDownload'));
    }

    /**
     * Tests the compressDir method.
     *
     * @return void
     */
    public function testCompressDir(): void
    {
        /** @var \ZipArchive|MockObject $stub */
        $stub = $this
            ->getMockBuilder(\ZipArchive::class)
            ->setMethods(['open', 'close', 'addFile'])
            ->getMock();

        $stub->method('open')
             ->willReturn(true);

        $stub->method('addFile')
             ->willReturn(true);

        $stub->method('close')
             ->willReturn(true);

        $this->assertFalse(Helper::compressDir('/not-exist', '/not-exist.zip', $stub));
        $this->assertTrue(Helper::compressDir(__DIR__ . '/../../logs', __DIR__ . '/../../logs.zip', $stub));
    }

    /**
     * Tests the uniqueMultidimensionalArray method.
     *
     * @return void
     */
    public function testUniqueMultidimensionalArray(): void
    {
        $array = [
            [
                'body' => 'Hi! It\'s auto test.',
                'type' => 'success',
            ],
            [
                'body' => 'Hi! It\'s auto test.',
                'type' => 'success',
            ]
        ];

        $array = Helper::uniqueMultidimensionalArray($array, 'body');

        $this->assertEquals(1, count($array));

        $array = [
            [
                'body' => 'Hi! It\'s auto test.',
                'type' => 'success',
            ],
            [
                'body' => 'Hi! It\'s another auto test.',
                'type' => 'success',
            ]
        ];

        $array = Helper::uniqueMultidimensionalArray($array, 'body');

        $this->assertEquals(2, count($array));
    }

    /**
     * Tests the generateSmsCode method.
     *
     * @return void
     */
    public function testGenerateSmsCode(): void
    {
        $code = Helper::generateSmsCode();
        $this->assertIsNumeric($code);
        $this->assertEquals(4, strlen($code));

        $code = Helper::generateSmsCode(5);
        $this->assertEquals(5, strlen($code));
    }

    /**
     * Tests the generatePassword method.
     *
     * @return void
     */
    public function testGeneratePassword(): void
    {
        $password = Helper::generatePassword(10, false, false);
        $this->assertEquals(10, strlen($password));

        $password = Helper::generatePassword(12, true, true);
        $this->assertEquals(12, strlen($password));

        $password = Helper::generatePassword();
        $this->assertEquals(12, strlen($password));
    }
}
