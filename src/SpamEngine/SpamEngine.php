<?php

namespace SpamEngine\SpamEngine;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Yaml\Yaml;

class SpamEngine
{

    const INVALID_EMAIL = 'Invalid email %s';

    const  INVALID_DOMAIN = 'Invalid domain %s';

    const DISABLED_DOMAIN = 'Disabled domain %s';

    const DISABLED_SPAM = 'Spam disabled %s';

    const TO_YOUNG = 'User to young %s';

    const DATA_ERROR = 'Data file not found %s';

    const SMTP_ERROR = 'Mail send error: %s';

    private array $succes;

    private PHPMailer $mailer;

    private array $config;

    private array $errors;

    private array $spam_data;

    function __construct()
    {
        $this->config = Yaml::parseFile(PROJECT_ROOT.'config.yaml');
        $this->mailer = new PHPMailer();
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
        $this->mailer->setFrom($this->config['fromMail']);
    }

    public function prepareData()
    {
        $users = [];
        $file = PROJECT_ROOT.$this->config['data'];
        if (file_exists($file)) {
            require_once $file;
        } else {
            $this->errors[] = sprintf(self::DATA_ERROR, $file);

            return;
        }
        $_users = [];
        foreach ($users as $k => $v) {
            if (!array_key_exists('email', $v)) {
                continue;
            }
            if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = sprintf(self::INVALID_EMAIL, "$v[name]: $v[email]");
                continue;
            }
            [, $domain] = explode('@', $v['email']);
            if (in_array($domain, $this->config['ignore_domains'])) {
                $this->errors[] = sprintf(self::DISABLED_DOMAIN, "$v[name]: $v[email]");
                continue;
            }
            if ($v['spam_disable']) {
                $this->errors[] = sprintf(self::DISABLED_SPAM, "$v[name]: $v[email]");
                continue;
            }
            if ($v['age'] < $this->config['min_age']) {
                $this->errors[] = sprintf(self::TO_YOUNG, "$v[name]: $v[email]");
                continue;
            }
            if (!checkdnsrr($domain, 'MX')) {
                $this->errors[] = sprintf(self::INVALID_DOMAIN, "$v[name]: $v[email]");
                continue;
            }
            $_users[$v['date_registration'].'_'.$k] = $v;
        }
        ksort($_users);
        $this->spam_data = $_users;
    }

    public function letSpam()
    {

        $this->mailer->Subject = $this->config['subject'];

        foreach ($this->spam_data as $data) {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($data['email'], $data['name']);
            $this->mailer->msgHTML(sprintf($this->config['body'], $data['name']));
            try {
                if (!$this->mailer->send()) {
                    $this->errors[] = sprintf(self::SMTP_ERROR, $this->mailer->ErrorInfo);
                    $this->mailer->ErrorInfo = '';
                } else {
                    $this->succes[] = [$data['email'], $data['name']];
                }
            } catch (Exception $exception) {
                $this->errors[] = sprintf(self::SMTP_ERROR, $exception->getMessage());
            }
        }
    }

    public function sendReport()
    {
        $this->mailer->Subject = 'Sending report';
        $msg = '<div>Sent succes: '.count($this->succes).'</div><hr>';
        $msg .= 'Errors: <ul>'.implode('<li>', $this->errors).'</ul>';
        $this->mailer->msgHTML($msg);
        $this->mailer->clearAddresses();
        $this->mailer->addAddress($this->config['reportMail']);
        $this->mailer->send();
    }

}