<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Domain\Attachment;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use EmailSender\Domain\To;

class RequestEmailSender implements RequestInterface
{
    public function __construct(
        public readonly Subject $subject,
        public readonly Sender $sender,
        public readonly To $to,
        public readonly HtmlContent $htmlContent,
        public readonly Attachment $attachment
    ) {
    }
}
