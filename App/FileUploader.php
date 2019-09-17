<?php

namespace App;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader.
 *
 * @package App
 */
class FileUploader
{
    /**
     * The target directory.
     *
     * @var string
     */
    private $target_directory;

    /**
     * FileUploader constructor.
     *
     * @param string $target_directory
     */
    public function __construct(string $target_directory)
    {
        $this->target_directory = $target_directory;
    }

    /**
     * Uploads.
     *
     * @codeCoverageIgnore
     *
     * @param UploadedFile|array $file
     * @param string $file_name
     *
     * @return void
     */
    public function upload($file, string $file_name): void
    {
        if (is_array($file)) {
            $counter = 1;

            foreach ($file as $sub_file) {
                if ($sub_file instanceof UploadedFile) {
                    $safe_file_name = $file_name . '-' . $counter++ . '.' . $sub_file->guessExtension();
                    $sub_file->move($this->getTargetDirectory(), $safe_file_name);
                }
            }
        } elseif ($file instanceof UploadedFile) {
            $safe_file_name = $file_name . '.' . $file->guessExtension();
            $file->move($this->getTargetDirectory(), $safe_file_name);
        }
    }

    /**
     * Gets the target directory.
     *
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->target_directory;
    }
}
