<?php

namespace EmailSender\Domain;

class Mail
{
    public function __construct(
        private Subject $subject,
        private Sender $sender,
        private To $to,
        private HtmlContent $htmlContent,
        private Attachment $attachment
    ) {
    }

    public function getMailSubject(): string
    {
        return $this->subject->getSubject();
    }

    /**
     * @return array<string, string> $sender
     */
    public function getMailSender(): array
    {
        return $this->sender->getSender();
    }

    /**
     * @return array<int, array<string, string>> $to
     */
    public function getMailTo(): array
    {
        return $this->to->getTo();
    }

    public function getMailHtmlContent(): string
    {
        return $this->htmlContent->getHtmlContent();
    }

    /**
     * @return array<string, Attachment> $attachment
     */
    public function getMailAttachment(): array
    {
        return $this->attachment->getAttachment();
    }
}
