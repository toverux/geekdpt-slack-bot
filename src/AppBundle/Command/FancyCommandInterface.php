<?php

namespace AppBundle\Command;

interface FancyCommandInterface
{
    /**
     * Style for the command.
     * Returns an array of format:
     * [
     *    name   => Displayed bot name
     *    avatar => Wether image URL or ":emoji:"
     * ]
     *
     * @return array
     */
    function getFancyStyle();
}
