<?php

define('PROJECT_ROOT', realpath(__DIR__) . '/');

require_once PROJECT_ROOT . 'vendor/autoload.php';

use PHPMailer\PHPMailer\Exception as ExceptionMailer;
use SpamEngine\SpamEngine\SpamEngine;

$spam = new SpamEngine();

$spam->prepareData();

try {
    $spam->letSpam();
    $spam->sendReport();
} catch (ExceptionMailer $e) {
    print $e->getMessage();
}
