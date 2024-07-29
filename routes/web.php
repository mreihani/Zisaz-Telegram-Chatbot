<?php

use App\Models\Action\Action;
use Illuminate\Support\Facades\Route;
use App\Services\TelegramBotPhp\Telegram;
use App\Services\ZisazBot\Sections\UserPrompts;
use App\Models\Action\BeamAndBlockRoof\BeamAndBlockRoof;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     $telegram = new Telegram(env('TELEGRAM_BOT_TOKEN'));

//     $action = Action::find(1);

//     dd(
//         $action->subaction
//     );

//     return view('welcome');
// });
