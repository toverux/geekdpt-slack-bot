<?php

namespace AppBundle\Slack;

use Buzz\Browser;

class IncomingApiSender
{
    private $browser;

    private $apiEndpoint;


    public function __construct(Browser $browser, $apiEndpoint)
    {
        $this->browser     = $browser;
        $this->apiEndpoint = $apiEndpoint;
    }

    public function send(WebhookBot $bot)
    {
        $this->browser->post(
            $this->apiEndpoint,
            ['Content-Type' => 'application/json'],
            json_encode($bot)
        );
    }
}
