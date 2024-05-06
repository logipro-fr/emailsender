<?php

namespace EmailSender\Domain;

class HtmlContent
{
    public function __construct(private string $htmlContent = "")
    {
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }
}
