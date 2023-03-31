<?php

namespace App\Conversations\Todo;

use App\Models\Models\Todo;
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

        $question = Question::create("Danh sách những việc cần làm")
        ->fallback("Không có danh sách để hiển thị")
        ->callbackId("ask_list_todo")
        ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->askOptions($answer->getValue());
            }
        });
    }

    public function askOptions(int $id)
    {
        $question = Question::create("Danh sách những việc cần làm")
        ->fallback("Không có danh sách để hiển thị")
        ->callbackId("ask_list_todo")
        ->addButtons([
            Button::create("Sửa đổi")->value("edit_todo")->name("handle_todo"),
            Button::create("Xóa")->value("delete_todo")->name("handle_todo"),
        ]);

        $this->ask($question, function (Answer $answer) use ($id) {
            if ($answer->isInteractiveMessageReply()) {
                $todo = $this->repo->show($id);

                switch ($answer->getValue()) {
                    case "edit_todo" : $this->askEdit($todo); break;
                    case "delete_todo" : $this->askDelete($todo); break;
                }
            }
        });
    }

    public function askEdit(Todo $todo)
    {
        $this->bot->reply("Bạn đang sửa đổi thông tin việc cần làm : ".$todo->title);

        $this->ask("Nhập tiêu đề bạn muốn đổi", function (Answer $answer) use ($todo) {
            $title = $answer->getText();

            $this->say("Đã tiếp nhận tiêu đề muốn đổi là : ".$title);

            $this->ask("Nhập thông tin chi tiết về tiêu đề : ".$title, function (Answer $answer) use ($todo, $title) {
                $desc = $answer->getText();

                $this->say("Đã tiếp nhận thông tin chi tiết muốn đổi là : ".$desc);

                $this->repo->update([
                    'title' => $title,
                    'description' => $desc
                ], $todo->id);

                $this->bot->reply("Cập nhật thành công : ".$title);
            });
        });
    }

    public function askDelete(Todo $todo)
    {
        $question = Question::create("Xác thực thao tác")
        ->fallback("Thao tác không thể thực thi")
        ->callbackId("ask_delete_todo_continue")
        ->addButtons([
            Button::create("Đồng ý")->value("yes_continue")->name("handle_todo"),
            Button::create("Hủy")->value("no_continue")->name("handle_todo"),
        ]);

        $this->ask($question, function (Answer $answer) use ($todo) {
            if ($answer->getValue() === "yes_continue") {
                $this->repo->delete($todo->id);
                $this->bot->reply("Đã xóa thành công : ".$todo->title );
            }

            $this->bot->reply("Thực thi thành công !");
        });
    }

    public function run()
    {
        $this->askReason();
    }
}
