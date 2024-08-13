<?php

namespace EmailSender\Domain\Model\Mail;

class HtmlContent
{
    public function __construct(private string $htmlContent = "")
    {
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    public function __toString()
    {
        return $this->htmlContent;
    }
}
