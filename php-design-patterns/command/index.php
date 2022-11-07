<?php

class UserService
{
    private $mailSender;
    private $mailRenderer;

    public function __construct(MailSender $mailSender, ConfirmationMailRenderer $mailRenderer)
    {
        $this->mailRenderer = $mailRenderer;
        $this->mailSender = $mailSender;
    }

    public function register($email)
    {
        // saving user to the database
        $content = $this->mailRenderer->renderTemplate("register", "token");
        $message = new Message(
            $content,
            "noreply@letscode.hu",
            $email,
            "Erősítse meg az e-mail címét!"
        );
        return new ConfirmationMailCommand($message, $this->mailSender);
    }
}

interface Command
{
    public function execute();
}

class ConfirmationMailCommand implements Command
{
    private $message;
    private $mailSender;

    public function __construct(Message $message, MailSender $mailSender)
    {
        $this->message = $message;
        $this->mailSender = $mailSender;
    }

    public function execute()
    {
        $this->mailSender->sendMail($this->message);
    }
}

class EchoCommand implements Command
{
    public function execute()
    {
        echo "Ez is lehetne.".PHP_EOL;
    }
}


class CommandExecutor
{
    public function process(Command $command)
    {
        $command->execute();
    }
}

class ConfirmationMailRenderer
{
    public function renderTemplate($templateName, $token)
    {
        return "some content";
    }
}

class Message
{
    private $content;
    private $from;
    private $to;
    private $subject;

    public function __construct($content, $from, $to, $subject)
    {
        $this->content = $content;
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
    }
}

class MailSender
{
    public function sendMail(Message $message)
    {
        var_dump($message);
    }
}

$executor = new CommandExecutor;

$userService = new UserService(new MailSender, new ConfirmationMailRenderer);

$command = $userService->register("fejlesztes@letscode.hu");

$serializedCommand = serialize($command);

$deserializedCommand = unserialize($serializedCommand);

$executor->process($deserializedCommand);
$executor->process(new EchoCommand);
