<?php

namespace EmailSender\Infrastructure\Persistence\Mail;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use EmailSender\Domain\Model\Mail\EmailSenderRepositoryInterface;
use EmailSender\Domain\Model\Mail\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Infrastructure\Persistence\Mail\Exceptions\MailNotFoundException;

/**
 * @extends EntityRepository<Mail>
 */
class EmailSenderRepositoryDoctrine extends EntityRepository implements EmailSenderRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        $class = $em->getClassMetadata(Mail::class);
        parent::__construct($em, $class);
    }

    public function add(Mail $mailData): void
    {
        $this->getEntityManager()->persist($mailData);
    }

    public function findById(MailId $id): Mail
    {
        $mail = $this->getEntityManager()->find(Mail::class, $id);
        if ($mail === null) {
            throw new MailNotFoundException(
                sprintf("Error can't find the mailId %s", $id),
                MailNotFoundException::ERROR_CODE
            );
        }
        return $mail;
    }
}
