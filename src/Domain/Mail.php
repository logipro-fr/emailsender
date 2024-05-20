<?php

namespace EmailSender\Domain;

class Mail
{
    public function __construct(
        private Subject $subject,
        private Sender $sender,
        private Recipient $recipient,
        private HtmlContent $htmlContent,
        private Attachment $attachment
    ) {
    }

    public function getMailSubject(): string
    {
        return $this->subject->getSubject();
    }

    public function getMailSenderName(): string
    {
        return $this->sender->getSenderName();
    }

    public function getMailSenderAddress(): string
    {
        return $this->sender->getSenderAddress();
    }

    /**
     * @return array<string, string>
     */
    public function getMailSenderData(): array
    {
        return ['name' => $this->getMailSenderName(), 'email' => $this->getMailSenderAddress()];
    }

    public function getMailRecipientName(): string
    {
        return $this->recipient->getRecipientName(0);
    }

    public function getMailRecipientAddress(): string
    {
        return $this->recipient->getRecipientAddress(0);
    }

    /**
     * @return array<string, string>
     */
    public function getMailRecipientData(): array
    {
        return ['name' => $this->getMailRecipientName(), 'email' => $this->getMailRecipientAddress()];
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
