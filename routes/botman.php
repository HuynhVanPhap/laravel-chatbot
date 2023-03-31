<?php

use App\Conversations\TodoConversation;
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Incoming\Answer;

// ? resolve()
$botman = resolve('botman');

$botman->hears('hi', function($bot) {
    $bot->ask("Xin chào, bạn tên gì ? ?", function (Answer $answer, $bot) {
        $name = $answer->getText();

        $bot->say($name."Rất vui được biết bạn, đó là một cái tên đẹp");
    });
});

$botman->hears('todo', function($bot) {
    $bot->startConversation(new TodoConversation());
});

$botman->fallback(function ($bot) {
    $bot->reply("Xin lỗi tôi chưa hiểu câu hỏi ?");
});

// $botman->hears('{message}', function($botman, $message) {
//     if ($message == 'hi') {
//         // $botman->ask("Hello ! What your name ?", function (Answer $answer) {
//         //     $name = $answer->getText();

//         //     $this->say("Nice to meet you ".$name);
//         // });
//         $botman->reply("Every Body say Hi !");
//     } else {
//         $botman->reply("Write 'hi' for testing...");
//     }
// });

// function askName($botman) {
//     $botman->ask("Hello ! What your name ?", function (Answer $answer) {
//         $name = $answer->getText();

//         $this->say("Nice to meet you ".$name);
//     });
// }
