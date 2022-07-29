<?php

define('PROJECT_ROOT', realpath(__DIR__).'/');

require_once PROJECT_ROOT.'vendor/autoload.php';

use SpamEngine\SpamEngine\SpamEngine;

$spam = new SpamEngine();

$spam->prepareData();

try {
    $spam->letSpam();
    $spam->sendReport();
} catch (\PHPMailer\PHPMailer\Exception $e) {
    print $e->getMessage();
}
