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

$crediting = new \App\Crediting($argv[1]);
$crediting->startCrediting();

die();
