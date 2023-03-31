<?php

namespace App\Conversations;

use App\Conversations\Todo\AddTodoConversation;
use App\Conversations\Todo\CompletedTodoConversation;
use App\Conversations\Todo\ListTodoConversation;
use App\Conversations\Todo\NotCompletedTodoConversation;
use App\Repositories\TodoRepository;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class TodoConversation extends Conversation
{
    /**
     * First question
     */
    public function askReason()
    {
        $question = Question::create("Ứng dụng Todo đang chạy. Nhập yêu cầu mà bạn muốn ?")
        ->fallback('Không thể thực hiện câu hỏi...')
        ->callbackId('ask_about_option')
        ->addButtons([
            Button::create('Thêm mới Todo')->value('create_todo'),
            Button::create('Danh sách Todo')->value('list_todo'),
            Button::create('Danh sách Todo đã hoàn thành')->value('completed_todo'),
            Button::create('Danh sách Todo chưa hoàn thành')->value('not_completed_todo'),
        ]);

        $this->bot->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $value = $answer->getValue();

                switch($value) {
                    case 'create_todo':
                        $this->bot->startConversation(new AddTodoConversation());
                        break;
                    case 'list_todo':
                        $this->bot->startConversation(new ListTodoConversation());
                        break;
                    case 'completed_todo':
                        $this->bot->startConversation(new CompletedTodoConversation());
                        break;
                    case 'not_completed_todo':
                        $this->bot->startConversation(new NotCompletedTodoConversation());
                        break;
                }
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}
