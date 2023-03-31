<?php

use App\Http\Controllers\BotManController;
use App\Repositories\TodoRepositoryInterface;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);

Route::get('test', function (TodoRepositoryInterface $repository) {
    dd($repository->list());
});
