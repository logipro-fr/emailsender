<?php

namespace EmailSender\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use EmailSender\Application\Service\SendMail\AbstractFactoryEmailProvider;
use EmailSender\Application\Service\SendMail\SendMailRequest;
use EmailSender\Application\Service\SendMail\SendMail;
use EmailSender\Domain\Model\Mail\EmailSenderRepositoryInterface;
use Exception;
use Infection\Configuration\Schema\InvalidFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendMailController
{
    public function __construct(
        private EmailSenderRepositoryInterface $repository,
        private AbstractFactoryEmailProvider $emailProviderFactory,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/api/v1/email/send', 'sendmail', methods: ['POST'])]
    public function execute(Request $request): Response
    {
        try {
            $request = $this->buildSendMailRequest($request);
            $service = new SendMail($this->repository, $this->emailProviderFactory);
            $service->execute($request);
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'success' => false,
                    'ErrorCode' => $e::class,
                    'data' => "",
                    'message' => $e->getMessage(),
                ],
                400,
            );
        }
        $response = $service->getResponse();
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'success' => true,
                'ErrorCode' => "",
                'data' => ['mailId' =>  $response->mailId],
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
        /** @var string */
        $provider = $data['provider'];

        return new SendMailRequest($sender, $recipient, $subject, $content, $provider);
    }
}
