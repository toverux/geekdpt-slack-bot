<?php

namespace AppBundle\Slack\WebHooks;

class WebHookBot implements \JsonSerializable
{
    private $channel;

    private $text;

    private $name;

    private $iconUrl;

    private $iconEmoji;


    public function __construct($channel, $text, $name = null, $avatar = null)
    {
        if($channel[0] !== '@' && $channel[0] !== '#') {
            $channel = "#{$channel}";
        }

        $this->channel = $channel;
        $this->text = $text;
        $this->name = $name;

        if(!is_string($avatar)) return;
        if($avatar[0] == ':') {
            $this->iconEmoji = $avatar;
        } else {
            $this->iconUrl = $avatar;
        }
    }

    public function jsonSerialize()
    {
        $avatar = $this->iconUrl
            ? ['icon_url' => $this->iconUrl]
            : ($this->iconEmoji
                ? ['icon_emoji' => $this->iconEmoji]
                : []);

        $username = $this->name ? ['username' => $this->name] : [];

        return $avatar + $username + [
            'channel' => $this->channel,
            'text'    => $this->text
        ];
    }
}
