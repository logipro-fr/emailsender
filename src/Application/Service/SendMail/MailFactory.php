<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Domain\Attachment;
use EmailSender\Domain\Contact;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Domain\Recipient;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use InvalidArgumentException;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

class MailFactory
{
    public function buildMailFromRequest(SendMailRequest $request, MailId $mailId = new MailId()): Mail
    {
        return new Mail(
            new Subject($request->subject),
            new Sender(
                $this->buildContact($request->sender, ", ")
            ),
            $this->buildRecipient($request->recipient, ", "),
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
            throw new InvalidArgumentException("Separator cannot be empty");
        } else {
            return $separator;
        }
    }
}
