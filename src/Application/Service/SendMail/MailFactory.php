<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Domain\Model\Mail\Attachment;
use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\HtmlContent;
use EmailSender\Domain\Model\Mail\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Domain\Model\Mail\Recipient;
use EmailSender\Domain\Model\Mail\Sender;
use EmailSender\Domain\Model\Mail\Subject;
use InvalidArgumentException;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

class MailFactory
{
    public function buildMailFromRequest(SendMailRequest $request, MailId $mailId = new MailId(), string $separator = ", "): Mail
    {
        return new Mail(
            new Subject($request->subject),
            new Sender(
                $this->buildContact($request->sender, $separator)
            ),
            $this->buildRecipient($request->recipient, $separator),
            new HtmlContent($request->content),
            new Attachment(),
            $mailId
        );
    }

    /**
     * @param non-empty-string $separator
     */
    private function buildContact(string $sentence, string $separator): Contact
    {
        $this->verifySeparator($separator);

        $tab = explode($separator, $sentence);
        return new Contact($tab[0], $tab[1]);
    }

    /**
     * @param array<string> $recipients
     * @param non-empty-string $separator
     */
    private function buildRecipient(array $recipients, string $separator): Recipient
    {
        $this->verifySeparator($separator);

        /** @var array<Int, Contact> */
        $result = array();
        for ($i = 0; $i < count($recipients); $i++) {
            $this->verifySeparator($separator);
            $result[$i] = $this->buildContact($recipients[$i], $separator);
        }
        return new Recipient($result);
    }

    private function verifySeparator(string $separator): string
    {
        if (empty($separator)) {
            throw new InvalidArgumentException("Error : Invalid seperator provided in the mail factory", 500);
        } else {
            return $separator;
        }
    }
}
