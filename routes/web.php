<?php

use App\Http\Controllers\GameController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [GameController::class, 'show'])->name('game');
Route::post('/play', [GameController::class, 'play'])->name('game.play');
Route::middleware('signed')->post('/save', [GameController::class, 'save'])->name('game.save');
Route::get('/leaderboard', [GameController::class, 'leaderboard'])->name('game.leaders');
