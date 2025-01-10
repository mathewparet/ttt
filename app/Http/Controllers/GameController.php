<?php

namespace App\Http\Controllers;

use App\Models\Winner;
use App\Services\GameLogic;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class GameController extends Controller
{
    public function show()
    {
        return Inertia::render('Welcome');
    }

    public function save(Request $request)
    {
        abort_unless($request->session()->getId() === $request->session, 403, 'Invalid session.');
        
        Winner::create([
            'name' => $request->name,
            'grid_size' => $request->gridSize,
            'matrix' => $request->grid,
            'duration' => $request->duration,
        ]);

        $request->session()->regenerate(true);

        return redirect()->route('game.leaders');
    }

    public function leaderboard()
    {
        $winners = Winner::orderBy('grid_size', 'desc')->orderBy('duration', 'asc')->limit(20)->get();

        $total_winners = Winner::count();

        return Inertia::render('Leaderboard', [
            'winners' => $winners,
            'total_winners' => $total_winners,
        ]);
    }

    public function play(Request $request, GameLogic $gameLogic)
    {
        $gameLogic->setMatrix($request->matrix);

        $isGameOver = $gameLogic->isGameOver();
        $isPlayerWin = $gameLogic->doWeHaveWinner();
        $isComputerWin = false;
        
        if (!$gameLogic->isGameOver()) {
            list($row, $col) = $gameLogic->makeMove();
            $isComputerWin = $gameLogic->doWeHaveWinner();
        }

        return back()->with('flash', [
            'is_game_over' => $gameLogic->isGameOver(),
            'is_player_win' => $isPlayerWin,
            'is_computer_win' => $isComputerWin,
            'row' => $row ?? null,
            'col' => $col ?? null,
            'winner_link' => $isGameOver ? URL::temporarySignedRoute('game.save', now()->addMinutes(5), [
                'session' => $request->session()->getId(),
            ]) : null,
        ]);
    }
}
