<?php

namespace EmailSender\Tests\Domain\Model\Mail\Exceptions;

use EmailSender\Domain\Model\Mail\Exceptions\LoggedException;

class LoggedExceptionTester extends LoggedException
{
    public function publicEnsureLogDirectoryExists(string $directoryPath): void
    {
        $this->ensureLogDirectoryExists($directoryPath);
    }

    public function publicEnsureLogFileExists(string $filePath): void
    {
        $this->ensureLogFileExists($filePath);
    }
}
