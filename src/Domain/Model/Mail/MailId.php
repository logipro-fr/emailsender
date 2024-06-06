<?php

namespace EmailSender\Domain\Model\Mail;

class MailId
{
    private string $id;

    public function __construct(string $id = "")
    {
        if (empty($id)) {
            $this->id = uniqid("mai_");
        } else {
            $this->id = $id;
        }
    }

    public function __toString()
    {
        return $this->id;
    }

    public function equals(MailId $otherId): bool
    {
        if ($this->id === $otherId->id) {
            return true;
        }
        return false;
    }
}
