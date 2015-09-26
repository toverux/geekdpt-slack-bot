<?php

namespace AppBundle\SlackBot;

use Buzz\Browser;

use AppBundle\SlackBot\Bot;

class IncomingApiSender
{
    private $browser;

    private $apiEndpoint;


    public function __construct(Browser $browser, $apiEndpoint)
    {
        $this->browser     = $browser;
        $this->apiEndpoint = $apiEndpoint;
    }

    public function send(Bot $bot)
    {
        $this->browser->post($this->apiEndpoint, [
            'Content-Type' => 'application/json'
        ], json_encode($bot));
    }
}
