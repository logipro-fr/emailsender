<?php
namespace EmailSender\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use EmailSender\Domain\Model\Mail\HtmlContent;
use EmailSender\Infrastructure\Persistence\Doctrine\Types\HtmlContentType;
use EmailSender\Infrastructure\Persistence\Doctrine\Types\SenderType;
use PHPUnit\Framework\TestCase;

class HtmlContentTypeTest extends TestCase {
    public const TEST_HTML_VALUE = "
    <html>
        <body style='font-family: Arial, sans-serif;'>
            <div style='text-align: center;'>
            <h1 style='color: #4CAF50;'>Integration Test Email from Email Sender</h1>
            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
            <p style='font-size: 16px;'>
                This is an integration test email. You can safely ignore this message.
            </p>
            <p style='font-size: 14px; color: #888;'>
                This message was generated automatically as part of our testing process. No action is required on your part.
            </p>
            </div>
            <footer style='text-align: center; font-size: 12px; color: #aaa; margin-top: 20px;'>
            &copy; 2024 Email Sender, Inc. - All rights reserved.
            </footer>
        </body>
    </html>";


    public function testGetName(): void {
        $this->assertEquals('htmlContent' , (new HtmlContentType())->getName());
    }

    public function testConvertToPhpValue(): void {
        $type = new HtmlContentType();
        $htmlContent = $type->convertToPHPValue(self::TEST_HTML_VALUE, new SqlitePlatform());
        $this->assertTrue($htmlContent instanceof HtmlContent);
        $this->assertEquals(self::TEST_HTML_VALUE, $htmlContent->getHtmlContent());
    }

    public function testConvertToDatabaseValue(): void {
        $type = new SenderType();
        $databaseValue = $type->convertToDatabaseValue(
            $htmlContent =  new HtmlContent(self::TEST_HTML_VALUE), 
            new SqlitePlatform
        );

        $this->assertEquals($htmlContent->getHtmlContent(), $databaseValue);

    }
}