<?php

namespace EmailSender\Domain\Model\Mail;

use EmailSender\Domain\Model\Mail\MailId;
use DateTimeImmutable;

class Mail
{
    public function __construct(
        private Subject $subject,
        private Sender $sender,
        private Recipient $recipient,
        private HtmlContent $htmlContent,
        private Attachment $attachment,
        private MailId $mailId = new MailId(),
        private DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getSender(): Sender
    {
        return $this->sender;
    }

    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    public function getHtmlContent(): HtmlContent
    {
        return $this->htmlContent;
    }

    public function getAttachment(): Attachment
    {
        return $this->attachment;
    }

    public function getMailId(): MailId
    {
        return $this->mailId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
