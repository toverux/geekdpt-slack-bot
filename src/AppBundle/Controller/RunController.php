<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * HTTP interface between Slack requests and our app.
 */
class RunController extends Controller
{
    /**
     * HTTP Slack command endpoint.
     * Converts request to Symfony Console command call.
     *
     * @param Request $request The request
     *
     * @return Response The command response (200) or an error (400).
     */
    public function runAction(Request $request)
    {
        $content = (object) parse_ini_string($request->getContent(), false, INI_SCANNER_RAW);

        try {
            $text = trim($content->text);

            #=> Determine the command instance
            $command = explode(' ', $text)[0];
            if($command == 'help') {
                # whitelist help and rewrite command
                $text = str_replace('help ', 'help gk:', $text);
            } else {
                # otherwise prefix with "gk:"
                $command = 'gk:'.$command;
            }

            $command = $this->getCommand($command);
            #=> Building I/O
            $input  = new ArgvInput($this->toArgv("dummy_command {$text}"));
            $output = new BufferedOutput;

            #=> Run the command
            $command->run($input, $output);
        } catch(\Exception $ex) {
            #=> Forward all errors to the client
            return new Response($ex->getMessage(), 400);
        }

        return new Response($output->fetch());
    }

    /**
     * Gets a command by name in AppBundle.
     *
     * @param string $name The command name
     *
     * @return \Symfony\Component\Console\Command\Command
     *
     * @throws \InvalidArgumentException When command name given does not exist
     */
    private function getCommand($name)
    {
        $app = new Application;

        $this->get('kernel')->getBundle('AppBundle')->registerCommands($app);

        return $app->get($name);
    }

    /**
     * Converts a shell command to ARGV format.
     *
     * @param string $string Shell command
     *
     * @return array
     */
    private function toArgv($string)
    {
        $out = '';

        $break = true;
        foreach(str_split($string) as $i => $char) {
            if($char == '"' && $string[$i-1] != '\\') {
                $break = !$break;
                continue;
            } elseif($char == ' ' && $break) {
                $char = "\0";
            }

            $out .= $char;
        }

        $out = str_replace('\"', '"', $out);
        return explode("\0", $out);
    }
}
