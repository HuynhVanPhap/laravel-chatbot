<?php

namespace App\Conversations\Todo;

use App\Repositories\TodoRepositoryInterface;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class AddTodoConversation extends Conversation
{
    /**
     * @var TodoRepositoryInterface
     */
    protected $repo;

    protected $title;

    protected $desc;

    /**
     * @param TodoRepositoryInterface $repo
     */
    public function __construct()
    {
        $this->repo = app(TodoRepositoryInterface::class);
    }

    public function askReason()
    {
        $this->askTitle();
    }

    public function askTitle()
    {
        $this->ask('Việc cần làm muốn nhập !', function (Answer $answer) {
            $this->title = $answer->getText();

            $this->say('Việc cần làm là : '.$this->title);

            $this->askDesc();
        });
    }

    public function askDesc()
    {
        $this->ask('Nhập thông tin chi tiết về việc cần làm !', function (Answer $answer) {
            $this->desc = $answer->getText();

            $this->say('Thông tin chi tiết là : '.$this->desc);

            $this->addTodo();
        });
    }

    public function addTodo()
    {
        $this->repo->store([
            'title' => $this->title,
            'description' => $this->desc
        ]);

        $this->bot->reply('Lưu việc cần làm mới thành công... Chúc một ngày tốt lành !');
    }

    public function run()
    {
        $this->askReason();
    }
}
