<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Games;

use Salle\PuzzleMania\Model\Game;

interface GameRepository {
    public function createGame(Game $game): void;
    public function getLastGame();
    public function getGameById(int $id);
    public function getGamesByUser(int $user_id);
    public function getAllGames();
}