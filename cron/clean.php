<?php

/**
 * Composer.
 */
require(__DIR__ . '/../vendor/autoload.php');

/**
 * Error and Exception handling.
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

set_time_limit(0);

/**
 * Deletes expired clients records.
 */
\App\Models\RememberedClient::deleteExpiredRecords();
/**
 * Deletes expired shops tokens records.
 */
\App\Models\ShopToken::deleteExpiredRecords();

/**
 * Deletes expired locked phone records.
 */
\App\Models\LockedPhone::deleteExpiredRecords();

die();
