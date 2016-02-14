<?php

namespace AppBundle\Command;

/**
 * By implementing this interface, Bots could have a better
 * style on their output !
 * Recommended: FancyCommandTrait.
 */
interface SlackBotCommandInterface
{
    /**
     * Avatar of the bot displayed on Slack.
     * Returns an array of format:
     * [
     *    string name  => Displayed bot name
     *    string image => Wether image URL or ":emoji:"
     * ]
     *
     * @return array
     */
    function getAvatar();

    /**
     * Style for the command.
     * Returns an array of format:
     * [
     *    bool outputAsCode => Wether the output is surrounded by markdown's triple-backticks.
     * ]
     *
     * @return array
     */
    function getOutputStyle();
}
