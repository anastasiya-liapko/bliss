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
try {
    \App\Models\RememberedClient::deleteExpiredRecords();
} catch (Exception $exception) {
    // I'm busy doing nothing.
}

/**
 * Deletes expired shops tokens records.
 */
try {
    \App\Models\ShopToken::deleteExpiredRecords();
} catch (Exception $exception) {
    // I'm busy doing nothing.
}

/**
 * Deletes expired locked phone records.
 */
try {
    \App\Models\LockedPhone::deleteExpiredRecords();
} catch (Exception $exception) {
    // I'm busy doing nothing.
}

die();
