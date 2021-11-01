<?php

namespace SpamEngine\SpamEngine;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Yaml\Yaml;

class SpamEngine
{

    private PHPMailer $mailer;

    private array $config;

    function __construct()
    {
        $this->mailer = new PHPMailer();
        $this->config = Yaml::parseFile(__DIR__.'/../../config.yaml');
        var_dump($this->config);
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPDebug = 1;
        $this->mailer->Host = $this->config['smtp']['host'];
        $this->mailer->Port = $this->config['smtp']['port'];
        $this->mailer->Username = $this->config['smtp']['user'];
        $this->mailer->Password = $this->config['smtp']['pass'];
        $this->mailer->SMTPOptions = [
          'ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true,],
        ];
    }

}