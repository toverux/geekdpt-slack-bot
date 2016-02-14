<?php

namespace AppBundle\Slack\WebHooks;

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

    public function send(WebHookBot $bot)
    {
        $this->browser->post(
            $this->apiEndpoint,
            ['Content-Type' => 'application/json'],
            json_encode($bot)
        );
    }
}
