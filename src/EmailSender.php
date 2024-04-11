<?php

namespace EmailSender;

class EmailSender
{

    public function isAuthenticated(string $user): bool
    {
        if ($user !== null) {
            return true;
        }
    }

    private const BREAK_LINE = "\n";
    private const BREAK_EMAIL = self::BREAK_LINE .
        "===========================================================" .
        self::BREAK_LINE;
    private const DATE_LABEL = "### Date:";
    private const MESSAGE_LABEL = "### Message:";
    private const TO_LABEL = "### To:";
    private const SUBJECT_LABEL = "### Subject:";

    public function sendMail(string $to, string $subject, string $message): bool 
    {
        if ($to == '') {
            return false;
        }
        $result = false;
        $log = fopen('logfile.txt', 'a+');
        if ($log !== false) {
            fwrite($log, self::BREAK_LINE . self::DATE_LABEL . date("d/M/Y H:i:s"));
            fwrite($log, self::BREAK_LINE . self::TO_LABEL . $to);
            fwrite($log, self::BREAK_LINE . self::SUBJECT_LABEL . $subject);
            fwrite($log, self::BREAK_LINE . self::MESSAGE_LABEL . $message);
            fwrite($log, self::BREAK_EMAIL);
            $result = fclose($log);
        }

        return $result;
    }

    public function mailConfirmation(string $to, string $subject, string $message): string
    {
        if ($this->sendMail($to, $subject, $message)) {
            return "Le mail a été envoyé avec succès";
        } else {
            return "Echec lors de l'envoi du mail";
        }
    }
}