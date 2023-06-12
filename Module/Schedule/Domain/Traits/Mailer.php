<?php
declare(strict_types=1);
namespace Module\Schedule\Domain\Traits;

use Zodream\Infrastructure\Mailer\Mailer as MailerProvider;

trait Mailer {

    private function sendToEmails(array $files) {
        if (empty($this->emailTo)) {
            return;
        }
        $mailer = new MailerProvider();
        $mailer->isHtml();
        foreach ($this->emailTo as $item) {
            $mailer->addAddress($item);
        }
        foreach ($files as $filename) {
            $mailer->addAttachment($filename);
        }
        $mailer->send('Cronjob execution', '<q>Cronjob output attached</q>');
    }
}