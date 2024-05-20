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

    public function getSubject(): string
    {
        return $this->subject->getSubject();
    }

    public function getSenderName(): string
    {
        return $this->sender->getSenderName();
    }

    public function getSenderAddress(): string
    {
        return $this->sender->getSenderAddress();
    }

    /**
     * @return array<string, string>
     */
    public function getSenderData(): array
    {
        return ['name' => $this->getSenderName(), 'email' => $this->getSenderAddress()];
    }

    public function getRecipientName(): string
    {
        return $this->recipient->getRecipientName(0);
    }

    public function getRecipientAddress(): string
    {
        return $this->recipient->getRecipientAddress(0);
    }

    /**
     * @return array<string, string>
     */
    public function getRecipientData(): array
    {
        return ['name' => $this->getRecipientName(), 'email' => $this->getRecipientAddress()];
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent->getHtmlContent();
    }

    /**
     * @return array<string, Attachment> $attachment
     */
    public function getAttachment(): array
    {
        return $this->attachment->getAttachment();
    }
}
