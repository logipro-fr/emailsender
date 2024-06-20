<?php

namespace EmailSender\Infrastructure\Api\V1;

use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Application\Service\SendMail\EmailSender;
use EmailSender\Application\Service\SendMail\MailFactory;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Domain\EmailSenderRepositoryInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendMailController
{
    public function __construct(private EmailSender $emailSender)
    {
    }

    #[Route('/api/v1/sendMail', 'sendMail', methods: ['GET'])]
    public function execute(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);

        // Validation des données reçues
        if (!is_array($requestData) || !isset($requestData['sender'], $requestData['to'], $requestData['subject'], $requestData['htmlContent'])) {
            return new Response('Invalid request data', 400);
        }

        if (!isset($requestData['sender']['name'], $requestData['sender']['email'])) {
            return new Response('Invalid sender data', 400);
        }

        foreach ($requestData['to'] as $recipient) {
            if (!isset($recipient['name'], $recipient['email'])) {
                return new Response('Invalid recipient data', 400);
            }
        }

        $requestEmailSender = new RequestEmailSender(
            $requestData['sender']['name'] . ', ' . $requestData['sender']['email'],
            array_map(fn($recipient) => $recipient['name'] . ', ' . $recipient['email'], $requestData['to']),
            $requestData['subject'],
            $requestData['htmlContent']
        );

        try {
            $this->emailSender->execute($requestEmailSender);
            $idMailResponse = $this->emailSender->getResponse();

            if (json_encode($idMailResponse) === false) {
                throw new Exception('Failed to encode response data to JSON');
            }

            $response = new Response(json_encode($idMailResponse), 200);
        } catch (Exception $e) {
            $response = new Response('Error sending email: ' . $e->getMessage(), 500);
        }

        return $response;
    }
}
