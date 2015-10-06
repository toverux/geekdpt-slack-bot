# geekdpt-slack-bot

Custom bots and commands for the GeekDpt team on Slack.
The application uses the Symfony Console component to map a standard command (useable via CLI) over HTTP with the Slack Outgoing/Incoming APIs.

In the Symfony CLI context, the commands are available under the `gk:` namespace between the Symfony-provided commands. Over HTTP, a "virtual" CLI application is used, containing only the `gk:` commands.

Test the commands with Symfony like any other command, eg. with `man` :

    $ bin/console gk:man welcome
    
Use the same command on Slack:

    /gk man welcome
    
Or the shortcut:

    /man welcome
    
To display help on a command usage:

    /gk help man
    
List commands:

    /gk list
