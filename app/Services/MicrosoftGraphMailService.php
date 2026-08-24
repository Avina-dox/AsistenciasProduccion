<?php

namespace App\Services;

use Microsoft\Graph\Generated\Models\BodyType;
use Microsoft\Graph\Generated\Models\EmailAddress;
use Microsoft\Graph\Generated\Models\ItemBody;
use Microsoft\Graph\Generated\Models\Message;
use Microsoft\Graph\Generated\Models\Recipient;
use Microsoft\Graph\Generated\Models\FileAttachment;
use Microsoft\Graph\Generated\Users\Item\SendMail\SendMailPostRequestBody;
use GuzzleHttp\Psr7\Utils;




class MicrosoftGraphMailService
{
    public function __construct(
        protected MicrosoftGraphService $graph
    ) {}

    /**
     * Envía un correo en texto plano.
     */
    public function send(
        string $recipient,
        string $subject,
        string $content,
        ?string $sender = null
    ): void {

        $sender = $sender
            ?? auth()->user()?->email
            ?? config('services.microsoft.mail_from');

        $this->sendMessage(
            recipient: $recipient,
            subject: $subject,
            content: $content,
            contentType: 'Text',
            sender: $sender
        );
    }

    /**
     * Envía un correo HTML.
     */
    public function sendHtml(
    array|string $recipients,
    string $subject,
    string $html,
    ?string $sender = null
): void {

    $sender = $sender
        ?? auth()->user()?->email
        ?? config('services.microsoft.mail_from');

    $this->sendMessage(
        recipients: $recipients,
        subject: $subject,
        content: $html,
        contentType: 'HTML',
        sender: $sender
    );
}

    /**
     * Construye y envía el mensaje mediante Microsoft Graph.
     */
    protected function sendMessage(
        array|string $recipients,
        string $subject,
        string $content,
        string $contentType,
        ?string $sender = null
    ): void {

        $sender = $sender ?: config('services.microsoft.mail_from');

        $message = new Message();

        $message->setSubject($subject);

        $body = new ItemBody();

        $body->setContentType(
            new BodyType($contentType)
        );

        $body->setContent($content);

        $message->setBody($body);

        $toRecipients = [];

foreach ((array) $recipients as $recipient) {

    if (empty($recipient)) {
        continue;
    }

    $email = new EmailAddress();

    $email->setAddress($recipient);

    $to = new Recipient();

    $to->setEmailAddress($email);

    $toRecipients[] = $to;
}

$message->setToRecipients($toRecipients);

        $requestBody = new SendMailPostRequestBody();

        $requestBody->setMessage($message);

        $requestBody->setSaveToSentItems(true);

        $this->graph
            ->client()
            ->users()
            ->byUserId($sender)
            ->sendMail()
            ->post($requestBody)
            ->wait();
    }

    public function sendHtmlWithAttachment(
    array|string $recipients,
    string $subject,
    string $html,
    string $filePath,
    string $fileName,
    ?string $sender = null
): void {

    $sender = $sender
        ?? auth()->user()?->email
        ?? config('services.microsoft.mail_from');

    $message = new Message();

    $message->setSubject($subject);

    // Cuerpo HTML
    $body = new ItemBody();

    $body->setContentType(
        new BodyType('HTML')
    );

    $body->setContent($html);

    $message->setBody($body);

    // Destinatarios
    $toRecipients = [];

    foreach ((array) $recipients as $recipient) {

        if (empty($recipient)) {
            continue;
        }

        $email = new EmailAddress();
        $email->setAddress($recipient);

        $to = new Recipient();
        $to->setEmailAddress($email);

        $toRecipients[] = $to;
    }

    $message->setToRecipients($toRecipients);

    // Archivo adjunto
    $attachment = new FileAttachment();

    $attachment->setOdataType('#microsoft.graph.fileAttachment');

    $attachment->setName($fileName);

    $attachment->setContentType('application/pdf');

    $attachment->setContentBytes(
    Utils::streamFor(
        base64_encode(file_get_contents($filePath))
    )
);

    $message->setAttachments([
        $attachment
    ]);

    // Request
    $requestBody = new SendMailPostRequestBody();

    $requestBody->setMessage($message);

    $requestBody->setSaveToSentItems(true);

    $this->graph
        ->client()
        ->users()
        ->byUserId($sender)
        ->sendMail()
        ->post($requestBody)
        ->wait();
}
}
