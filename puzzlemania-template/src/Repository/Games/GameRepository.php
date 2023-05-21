<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Repository\Games;

use Salle\PuzzleMania\Model\Game;

interface GameRepository {
    public function createGame(Game $game): Game;
    public function getLastGame(): ?Game;
    public function getGameById(int $id): ?Game;
    public function getGamesByUser(int $user_id): ?array;
    public function getAllGames(): ?array;
    public function updateGameScore(int $id, int $score): void;
}