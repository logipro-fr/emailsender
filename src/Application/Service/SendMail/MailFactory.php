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
    public function buildMailFromRequest(
        SendMailRequest $request,
        MailId $mailId = new MailId(),
        string $separator = ", "
    ): Mail {
        if (empty($separator)) {
            throw new InvalidArgumentException(
                "Error : Invalid seperator provided in the mail factory",
                500
            );
        }

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
        $tab = explode($separator, $sentence);
        return new Contact($tab[0], $tab[1]);
    }

    /**
     * @param array<int, string> $recipients
     * @param non-empty-string $separator
     * @return Recipient
     */
    private function buildRecipient(array $recipients, string $separator): Recipient
    {
        /** @var array<Int, Contact> */
        $result = array();
        for ($i = 0; $i < count($recipients); $i++) {
            $result[$i] = $this->buildContact($recipients[$i], $separator);
        }
        return new Recipient($result);
    }
}
