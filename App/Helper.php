<?php

namespace App;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

/**
 * Class Helper.
 *
 * @package App
 */
class Helper
{
    /**
     * Creates the passwords.
     *
     * @param int $length Length of the password.
     * @param bool $special_chars Includes special chars in the password.
     * @param bool $extra_special_chars Includes extra special chars in the password.
     *
     * @return string $password The password.
     */
    public static function generatePassword(
        int $length = 12,
        bool $special_chars = true,
        bool $extra_special_chars = false
    ): string {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ($special_chars) {
            $chars .= '!@#$%^&*()';
        }

        if ($extra_special_chars) {
            $chars .= '-_ []{}<>~`+=,.;:/?|';
        }

        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }

        return $password;
    }

    /**
     * Creates the sms-code.
     *
     * @param int $length Length of the code.
     *
     * @return string $code Code.
     */
    public static function generateSmsCode(int $length = 4): string
    {
        $chars = '0123456789';

        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }

        return $code;
    }

    /**
     * Uniques a multidimensional array.
     *
     * @param array $array The array.
     * @param string $key The key.
     *
     * @return array
     */
    public static function uniqueMultidimensionalArray(array $array, string $key): array
    {
        $i          = 0;
        $temp_array = [];
        $key_array  = [];

        foreach ($array as $val) {
            if (! in_array($val[$key], $key_array)) {
                $key_array[$i]  = $val[$key];
                $temp_array[$i] = $val;
            }

            $i++;
        }

        return $temp_array;
    }

    /**
     * Compresses a dir.
     *
     * @param string $dir_path The dir path.
     * @param string $archive_path The archive path.
     * @param ZipArchive $zip The ZipArchive object.
     *
     * @return bool True if success, false otherwise.
     */
    public static function compressDir(string $dir_path, string $archive_path, ZipArchive $zip): bool
    {
        $path = realpath($dir_path);

        if (! is_dir($path)) {
            return false;
        }

        $zip->open($archive_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (! $file->isDir()) {
                $file_path     = $file->getRealPath();
                $relative_path = substr($file_path, strlen($path) + 1);
                $zip->addFile($file_path, $relative_path);
            }
        }

        return $zip->close();
    }

    /**
     * Downloads file.
     *
     * @codeCoverageIgnore
     *
     * @param string $file_path The file path.
     *
     * @return void
     */
    public static function fileForceDownload(string $file_path): void
    {
        if (file_exists($file_path)
            && function_exists('apache_get_modules')
            && in_array('mod_xsendfile', apache_get_modules())
        ) {
            header('X-SendFile: ' . realpath($file_path));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_path));
        } elseif (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_path));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));

            if ($file = fopen($file_path, 'rb')) {
                while (! feof($file)) {
                    print fread($file, 1024);
                }

                fclose($file);
            }
        }

        exit;
    }

    /**
     * Exec in the background.
     *
     * @codeCoverageIgnore
     *
     * @param string $cmd The command.
     *
     * @return void
     */
    public static function execInBackground(string $cmd): void
    {
        if (substr(php_uname(), 0, 7) == 'Windows' && defined('WINDOWS_PHP_EXE')) {
            pclose(popen('start /B ' . WINDOWS_PHP_EXE . ' ' . $cmd, 'r'));
        } else {
            exec("/usr/bin/php $cmd > /dev/null &");
        }
    }

    /**
     * Checks if a string is serialized.
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isSerialized(string $string): bool
    {
        return (@unserialize($string) !== false);
    }

    /**
     * Gets the clean site phone number.
     *
     * @param string $phone The phone number.
     *
     * @return string
     */
    public static function getCleanPhone(string $phone): string
    {
        return str_replace(['(', ')', ' ', '-'], '', $phone);
    }

    /**
     * Gets the file path.
     *
     * @param string $file_path The file path.
     * @return string|false
     */
    public static function getFileInBase64(string $file_path)
    {
        if (! file_exists($file_path)) {
            return false;
        }

        $type      = mime_content_type($file_path);
        $file_data = file_get_contents($file_path);

        return 'data:' . $type . ';base64,' . base64_encode($file_data);
    }
}
