<?php

namespace App\Conversations\Todo;

use BotMan\BotMan\Messages\Conversations\Conversation;

class NotCompletedTodoConversation extends Conversation
{
    public function askReason()
    {

    }

    public function run()
    {
        $this->askReason();
    }
}
