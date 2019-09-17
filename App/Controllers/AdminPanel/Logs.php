<?php

namespace App\Controllers\AdminPanel;

use App\Helper;
use App\SiteInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

/**
 * Class Logs.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\AdminPanel
 */
class Logs extends AdminPanel
{
    /**
     * Downloads logs.
     *
     * @return BinaryFileResponse
     */
    public function downloadLogsAction(): BinaryFileResponse
    {
        $zip = new ZipArchive();

        $document_root = SiteInfo::getDocumentRoot();

        $path_zip = $document_root . '/tmp/logs.zip';

        Helper::compressDir("$document_root/logs", $path_zip, $zip);

        return $this->sendBinaryFileResponse($path_zip, 200, [], false);
    }
}
