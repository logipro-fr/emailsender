<?php

namespace EmailSender\Infrastructure\Api\V1;

use EmailSender\Application\Service\SendMail\SendMailRequest;
use EmailSender\Application\Service\SendMail\SendMail;
use EmailSender\Domain\EmailSenderRepositoryInterface;
use EmailSender\Infrastructure\Persistance\EmailSenderRepositoryInMemory;
use EmailSender\Infrastructure\Provider\Brevo\BrevoSender;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendMailController
{
    public function __construct(private EmailSenderRepositoryInMemory $repository, private Client $client)
    {
    }

    #[Route('/api/v1/sendmail', 'sendmail', methods: ['POST'])]
    public function execute(Request $request): Response
    {
        try {
            $request1 = $this->buildSendMailRequest($request);
            $mail = new SendMail($this->repository, new BrevoSender('', $this->client));
            $mail->execute($request1);
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'success' => false,
                    'ErrorCode' => $e::class,
                    'data' => '',
                    'message' => $e->getMessage(),
                ],
                400,
            );
        }
        $response = $mail->getResponse();
        return new JsonResponse(
            [
                'success' => true,
                'ErrorCode' => "",
                'data' => ['MailId' =>  $response->mailId],
                'message' => "",
            ],
            201
        );
    }

    private function buildSendMailRequest(Request $request): SendMailRequest
    {
        $content = $request->getContent();
        /** @var array<array<float>|string> */
        $data = json_decode($content, true);
        /** @var string */
        $sender = $data['sender'];
        /** @var array<string> */
        $recipient = $data['recipient'];
        /** @var string */
        $subject = $data['subject'];
        /** @var string*/
        $content = $data['content'];

        return new SendMailRequest($sender, $recipient, $subject, $content);
    }
}
