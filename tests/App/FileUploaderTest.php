<?php

namespace Tests\App;

use App\FileUploader;
use PHPUnit\Framework\TestCase;

/**
 * Class FileUploaderTest.
 *
 * @package Tests\App
 */
class FileUploaderTest extends TestCase
{
    /**
     * Tests the getTargetDirectory method.
     *
     * @return void
     * @throws \Exception
     */
    public function testGetTargetDirectory(): void
    {
        $file_uploader = new FileUploader('/test');

        $this->assertEquals('/test', $file_uploader->getTargetDirectory());
    }

    /**
     * Tests the upload method.
     *
     * @return void
     * @throws \Exception
     */
    public function testUpload(): void
    {
        $this->assertTrue(method_exists(FileUploader::class, 'upload'));
    }
}
