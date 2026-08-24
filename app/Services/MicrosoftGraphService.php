<?php

namespace App\Services;

use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;

class MicrosoftGraphService
{
    protected GraphServiceClient $client;

    public function __construct()
    {
        $tenantId = config('services.microsoft.tenant_id');
        $clientId = config('services.microsoft.client_id');
        $clientSecret = config('services.microsoft.client_secret');

        $tokenContext = new ClientCredentialContext(
            $tenantId,
            $clientId,
            $clientSecret
        );

        $this->client = new GraphServiceClient(
            $tokenContext,
            ['https://graph.microsoft.com/.default']
        );
    }

    public function client(): GraphServiceClient
    {
        return $this->client;
    }
}