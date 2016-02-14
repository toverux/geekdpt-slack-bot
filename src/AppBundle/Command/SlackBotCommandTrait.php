<?php

namespace AppBundle\Command;

trait SlackBotCommandTrait
{
    protected $outputAsCode = true;


    public function getOutputStyle()
    {
        return [
            'outputAsCode' => $this->outputAsCode
        ];
    }
}
