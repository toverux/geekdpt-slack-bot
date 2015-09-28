<?php

namespace AppBundle\Command;

trait FancyCommandTrait
{
    protected $outputAsCode = true;


    public function getOutputStyle()
    {
        return [
            'outputAsCode' => $this->outputAsCode
        ];
    }
}
