<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

use AppBundle\Command\CustomApplication;
use AppBundle\Command\FancyCommandInterface;
use AppBundle\SlackBot\Bot;

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
        $slackdata = (object) $request->request->all();

        $text = trim($slackdata->text);

        #=> Determine the command instance
        $command = explode(' ', $text)[0];

        switch($command) {
            case 'help':
                # whitelist help and rewrite command
                $text = str_replace('help ', 'help gk:', $text);
                break;
            case '':
            case 'list':
                # whitelist list and support no command
                $command = 'list';
                break;
            default:
                # otherwise prefix with "gk:"
                $command = 'gk:'.$command;
                break;
        }

        #=> Building I/O
        $input  = new ArgvInput($this->toArgv("dummy_command {$text}"));
        $output = new BufferedOutput;

        $start = microtime(true);

        $tags = [];

        try {
            $command = $this->getCommand($command);
            # ensure container is set
            if($command instanceof ContainerAwareCommand) {
                $command->setContainer($this->container);
            }

            #=> Run the command
            $command->run($input, $output);

            #=> It's all good
            $return = $output->fetch();
            $status = 200;
        }

        catch(\Exception $ex) {
            #=> Forward all errors to the client
            $return = $ex->getMessage();
            $tags[] = get_class($ex);
            $status = 400;
        }

        $time = microtime(true) - $start;

        #=> Command tags
        $tags = [
            $text,
            sprintf('took %fs', $time),
            sprintf('issuedby %s', $slackdata->user_name)
        ] + $tags;

        #=> Determine command style
        if($command instanceof FancyCommandInterface) {
            $avatar = (object) $command->getAvatar();
            $style  = (object) $command->getOutputStyle();
        } else {
            $avatar = (object) ['name' => null, 'avatar' => null];
            $style  = (object) ['outputAsCode' => true];
        }

        #=> Enable (or not) auto markdown output
        if(!$input->hasParameterOption(['--no-markdown'])) {
            $return = $this->markdownize($return, $style->outputAsCode, $tags);
        }

        #=> Share (or not) command result
        if($input->hasParameterOption(['-s', '--share']) && is_object($command)) {
            $bot = new Bot($slackdata->channel_name, $return, $avatar->name, $avatar->image);

            $this->get('slack_bot.incoming_api_sender')->send($bot);

            return new Response(null, 204);
        }

        return new Response($return, $status);
    }

    /**
     * Surrounds command output by markdown for better formatting.
     *
     * @param string $output The output to display as code
     * @param bool $codeSurround Wether the $output is displayed as code or not
     *
     * @return string
     */
    private function markdownize($output, $codeSurround, array $tags)
    {
        $tagsstr = '';
        foreach($tags as $tag) {
            if($tag) $tagsstr .= "`{$tag}` ";
        }

        $code = $codeSurround ? "\n```" : '';

        return "{$tags}{$code}\n{$output}{$code}";
    }

    /**
     * Gets a command by name in AppBundle.
     *
     * @param string $name The command name
     *
     * @return \Symfony\Component\Console\Command\Command
     *
     * @throws \InvalidArgumentException When the command does not exist
     */
    private function getCommand($name)
    {
        $app = new CustomApplication('GeekDpt Commander', 'alpha');

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
