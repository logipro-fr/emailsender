<?php

namespace EmailSender\Infrastructure\Shared;

class CurrentWorkDirPath
{
    public static function getPath(): string
    {
        return dirname(__DIR__, 3);
    }
}
