<?php

define('PROJECT_ROOT', realpath(__DIR__).'/');

require_once PROJECT_ROOT.'vendor/autoload.php';

use SpamEngine\SpamEngine\SpamEngine;

$spam = new SpamEngine();

$spam->prepareData();

$spam->letSpam();

$spam->sendReport();
