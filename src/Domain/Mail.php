<?php

namespace EmailSender\Domain;

use EmailSender\Domain\Model\Mail\MailId;

class Mail
{
    public function __construct(
        private Subject $subject,
        private Sender $sender,
        private Recipient $recipient,
        private HtmlContent $htmlContent,
        private Attachment $attachment,
        private MailId $mailId = new MailId(),
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

    public function getRecipientName(int $rank): string
    {
        return $this->recipient->getRecipientName($rank);
    }

    public function getRecipientAddress(int $rank): string
    {
        return $this->recipient->getRecipientAddress($rank);
    }

    /**
     * @return array<string, string>
     */
    public function getRecipientData(int $rank): array
    {
        return ['name' => $this->getRecipientName($rank), 'email' => $this->getRecipientAddress($rank)];
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

    public function getMailId(): MailId
    {
        return $this->mailId;
    }
}
