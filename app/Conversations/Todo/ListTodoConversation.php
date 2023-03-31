<?php

namespace App\Conversations\Todo;

use App\Repositories\TodoRepositoryInterface;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ListTodoConversation extends Conversation
{
    /**
     * @var TodoRepositoryInterface
     */
    protected $repo;

    /**
     * @param TodoRepositoryInterface $repo
     */
    public function __construct()
    {
        $this->repo = app(TodoRepositoryInterface::class);
    }

    public function askReason()
    {
        $todos = $this->repo->list();

        $buttons = [];

        foreach ($todos as $todo) {
            $buttons[] = Button::create($todo->title)
                        ->value($todo->id)
                        ->name('todo');
        }

        $question = Question::create("Danh sách những việc cần làm : ")
        ->fallback("Không có danh sách để hiển thị")
        ->callbackId("ask_list_todo")
        ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->handleValue($answer->getValue());
            }
        });
    }

    public function handleValue(int $id) {
        $question = Question::create("Danh sách những việc cần làm : ")
        ->fallback("Không có danh sách để hiển thị")
        ->callbackId("ask_list_todo")
        ->addButtons([
            Button::create("Sửa đổi")->value("edit_todo")->name("handle_todo"),
            Button::create("Xóa")->value("delete_todo")->name("handle_todo"),
        ]);

        $this->ask($question, function (Answer $answer) use ($id) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == "edit_todo") {
                    $todo = $this->repo->show($id);

                    $this->bot->reply("Bạn đang sửa đổi thông tin việc cần làm : ".$todo->title);

                    $this->ask("Nhập tiêu đề bạn muốn đổi", function (Answer $answer) use ($id) {
                        $title = $answer->getText();
                        $this->say("Đã tiếp nhận tiêu đề muốn đổi là : ".$title);

                        $this->ask("Nhập thông tin chi tiết về tiêu đề : ".$title, function (Answer $answer) use ($id, $title) {
                            $desc = $answer->getText();

                            $this->say("Đã tiếp nhận thông tin chi tiết muốn đổi là : ".$desc);

                            $this->repo->update([
                                'title' => $title,
                                'description' => $desc
                            ], $id);

                            $this->bot->reply("Cập nhật thành công : ".$title);
                        });
                    });

                } elseif ($answer->getValue() == "delete_todo") {
                    $this->repo->delete($id);
                    $this->bot->reply("Đã xóa thành công ");
                }
            }
        });
    }

    public function run()
    {
        $this->askReason();
    }
}
